<?php

namespace App\Http\Controllers;

use App\Models\Zadatak;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KalendarController extends Controller
{
    public function rokovi(Request $request)
    {
        $user = $request->user();

        if (!in_array($user->uloga, ['STUDENT', 'PROFESOR', 'ADMIN'])) {
            return response()->json(['message' => 'Zabranjeno'], 403);
        }

        $now = now();

        $zadaciQuery = Zadatak::query()
            ->with(['predmet:id,naziv,sifra', 'profesor:id,ime,prezime'])
            ->where('rok_predaje', '>=', $now)
            ->orderBy('rok_predaje');

        if ($user->uloga === 'STUDENT') {
            $predmetIds = $user->predmeti()->pluck('predmeti.id');
            $zadaciQuery->whereIn('predmet_id', $predmetIds);
        }

        if ($user->uloga === 'PROFESOR') {
            $zadaciQuery->where('profesor_id', $user->id);
        }

        $lokalniRokovi = $zadaciQuery->get()->map(function (Zadatak $zadatak) {
            return [
                'id' => 'zadatak-' . $zadatak->id,
                'source' => 'internal',
                'title' => $zadatak->naslov,
                'description' => $zadatak->opis,
                'start' => $zadatak->rok_predaje?->toIso8601String(),
                'end' => $zadatak->rok_predaje?->toIso8601String(),
                'all_day' => false,
                'subject' => $zadatak->predmet?->naziv,
                'subject_code' => $zadatak->predmet?->sifra,
                'profesor' => trim(($zadatak->profesor?->ime ?? '') . ' ' . ($zadatak->profesor?->prezime ?? '')),
            ];
        })->values();

        $eksterniRokovi = $this->fetchGoogleCalendarEvents($now);

        $rokovi = $lokalniRokovi
            ->concat($eksterniRokovi)
            ->sortBy('start')
            ->values();

        return response()->json([
            'data' => $rokovi,
            'meta' => [
                'google_calendar_connected' => $this->googleCalendarConfigured(),
                'today' => [
                    'date' => $now->toDateString(),
                    'day_name' => $now->locale('sr')->isoFormat('dddd'),
                ],
            ],
        ]);
    }

    private function fetchGoogleCalendarEvents(Carbon $now)
    {
        if (!$this->googleCalendarConfigured()) {
            return collect();
        }

        $calendarId = config('services.google_calendar.calendar_id');
        $accessToken = $this->getGoogleAccessToken();

        if (!$accessToken) {
            return collect();
        }

        try {
            $response = Http::timeout(8)
                ->withToken($accessToken)
                ->get(
                    sprintf('https://www.googleapis.com/calendar/v3/calendars/%s/events', urlencode($calendarId)),
                    [
                        'singleEvents' => 'true',
                        'orderBy' => 'startTime',
                        'timeMin' => $now->toIso8601String(),
                        'maxResults' => 50,
                        'timeZone' => config('services.google_calendar.timezone', 'Europe/Belgrade'),
                    ]
                );

            if (!$response->successful()) {
                Log::warning('Google Calendar events fetch failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return collect();
            }

            $items = $response->json('items', []);

            return collect($items)
                ->map(function (array $event) {
                    $start = $event['start']['dateTime'] ?? $event['start']['date'] ?? null;
                    $end = $event['end']['dateTime'] ?? $event['end']['date'] ?? $start;
                    $allDay = isset($event['start']['date']);

                    return [
                        'id' => 'google-' . ($event['id'] ?? uniqid()),
                        'source' => 'google_calendar',
                        'title' => $event['summary'] ?? 'Google Calendar događaj',
                        'description' => $event['description'] ?? null,
                        'start' => $start ? Carbon::parse($start)->toIso8601String() : null,
                        'end' => $end ? Carbon::parse($end)->toIso8601String() : null,
                        'all_day' => $allDay,
                        'subject' => null,
                        'subject_code' => null,
                        'profesor' => null,
                    ];
                })
                ->filter(fn ($event) => !empty($event['start']))
                ->values();
        } catch (\Throwable $e) {
            Log::warning('Google Calendar sync failed', [
                'message' => $e->getMessage(),
            ]);

            return collect();
        }
    }

    private function getGoogleAccessToken(): ?string
    {
        $cacheKey = 'google_calendar_access_token';
        $cachedToken = Cache::get($cacheKey);

        if ($cachedToken) {
            return $cachedToken;
        }

        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post('https://oauth2.googleapis.com/token', [
                    'client_id' => config('services.google_calendar.client_id'),
                    'client_secret' => config('services.google_calendar.client_secret'),
                    'refresh_token' => config('services.google_calendar.refresh_token'),
                    'grant_type' => 'refresh_token',
                ]);

            if (!$response->successful()) {
                Log::warning('Google OAuth token refresh failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            $accessToken = $response->json('access_token');
            $expiresIn = (int) $response->json('expires_in', 3600);

            if (!$accessToken) {
                return null;
            }

            Cache::put($cacheKey, $accessToken, now()->addSeconds(max($expiresIn - 60, 60)));

            return $accessToken;
        } catch (\Throwable $e) {
            Log::warning('Google OAuth token request exception', [
                'message' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function googleCalendarConfigured(): bool
    {
        return (bool) (
            config('services.google_calendar.calendar_id') &&
            config('services.google_calendar.client_id') &&
            config('services.google_calendar.client_secret') &&
            config('services.google_calendar.refresh_token')
        );
    }
}
