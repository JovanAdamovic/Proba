<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProveraPlagijataResource;
use App\Models\Predaja;
use App\Models\Predmet;
use App\Models\ProveraPlagijata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProveraPlagijataController extends Controller
{
    private function proveriProfesora()
    {
        $user = auth()->user();
        if (!$user || $user->uloga !== 'PROFESOR') {
            abort(response()->json(['message' => 'Zabranjeno'], 403));
        }
        return $user;
    }



    public function index()
    {
        $user = $this->proveriProfesora();

        return ProveraPlagijataResource::collection(
            ProveraPlagijata::with(['predaja.zadatak.predmet'])
                ->whereHas('predaja.zadatak.predmet', fn($q) => $q->where('profesor_id', $user->id))
                ->get()
        );
    }

    public function show($id)
    {
        $user = $this->proveriProfesora();

        $provera = ProveraPlagijata::with(['predaja.zadatak.predmet'])->findOrFail($id);

        $predmetId = $provera->predaja?->zadatak?->predmet_id;

        $ok = Predmet::where('id', $predmetId)
            ->where('profesor_id', $user->id)
            ->exists();

        if (!$ok) return response()->json(['message' => 'Zabranjeno'], 403);

        return new ProveraPlagijataResource($provera);
    }


    public function store(Request $request)
    {
        return response()->json(['message' => 'Zabranjeno'], 403);
    }

    public function update(Request $request, $id)
    {
        return response()->json(['message' => 'Zabranjeno'], 403);
    }

    public function destroy($id)
    {
        return response()->json(['message' => 'Zabranjeno'], 403);
    }


    public function pokreni($predajaId)
{
    $user = auth()->user();

    if ($user->uloga !== 'PROFESOR') {
        return response()->json(['message' => 'Zabranjeno'], 403);
    }

    $predaja = Predaja::with(['zadatak.predmet'])->findOrFail($predajaId);

    $predmetId = $predaja->zadatak?->predmet_id;

    $ok = Predmet::where('id', $predmetId)
        ->where('profesor_id', $user->id)
        ->exists();

    if (!$ok) {
        return response()->json(['message' => 'Zabranjeno'], 403);
    }

    $postojeca = ProveraPlagijata::where('predaja_id', $predajaId)->first();
    if ($postojeca) {
        $line = "Provera plagijata: {$postojeca->procenat_slicnosti}% ({$postojeca->status})";

        $trenutni = $predaja->komentar ?? '';
        if (stripos($trenutni, 'Provera plagijata:') === false) {
            $novi = trim($trenutni);
            $novi = $novi ? ($novi . "\n" . $line) : $line;
            $predaja->update(['komentar' => $novi]);
        }

        return response()->json([
            'predaja_id' => (int) $predajaId,
            'procenat_slicnosti' => (float) $postojeca->procenat_slicnosti,
            'status' => $postojeca->status,
        ], 200);
    }

    $apiUrl = config('services.plagiarism_api.url');
    $apiToken = config('services.plagiarism_api.token');

    if (!$apiUrl) {
        return response()->json(['message' => 'Plagiarism API nije konfigurisan.'], 500);
    }

    $payload = [
        'predaja_id' => $predaja->id,
        'file_path' => $predaja->file_path,
        'student_id' => $predaja->student_id,
        'zadatak_id' => $predaja->zadatak_id,
    ];

    $request = Http::timeout(15);
    if ($apiToken) {
        $request = $request->withToken($apiToken);
    }

    $response = $request->post($apiUrl, $payload);

    if (!$response->successful()) {
        return response()->json([
            'message' => 'Provera plagijata nije uspela.',
            'status' => $response->status(),
        ], 502);
    }

    $data = $response->json();
    $procenat = $data['procenat_slicnosti'] ?? $data['similarity'] ?? null;

    if ($procenat === null) {
        return response()->json([
            'message' => 'Nevažeći odgovor API-ja za plagijat.',
        ], 502);
    }

    $provera = ProveraPlagijata::create([
        'predaja_id' => $predajaId,
        'procenat_slicnosti' => $procenat,
        'status' => 'ZAVRSENO',
    ]);

    $line = "Provera plagijata: {$provera->procenat_slicnosti}% ({$provera->status})";
    $trenutni = $predaja->komentar ?? '';
    $novi = trim($trenutni);
    $novi = $novi ? ($novi . "\n" . $line) : $line;

    $predaja->update([
        'komentar' => $novi
    ]);

    return response()->json([
        'predaja_id' => (int) $predajaId,
        'procenat_slicnosti' => (float) $provera->procenat_slicnosti,
        'status' => $provera->status,
    ], 201);
}

}
