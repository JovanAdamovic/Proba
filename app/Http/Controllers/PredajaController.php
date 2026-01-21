<?php

namespace App\Http\Controllers;

use App\Http\Resources\PredajaResource;
use App\Models\Predaja;
use App\Models\Predmet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PredajaController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->uloga === 'ADMIN') {
            return PredajaResource::collection(
                Predaja::with(['student', 'zadatak.predmet', 'proveraPlagijata'])->get()
            );
        }

        if ($user->uloga === 'STUDENT') {
            return $this->moje();
        }

        // PROFESOR
        return $this->zaMojePredmete();
    }

    public function show($id)
    {
        $user = auth()->user();


        $predaja = Predaja::with(['student', 'zadatak.predmet', 'proveraPlagijata'])
            ->findOrFail($id);

        // ADMIN vidi sve
        if ($user->uloga === 'ADMIN') {
            return new PredajaResource($predaja);
        }

        // STUDENT vidi samo svoje predaje
        if ($user->uloga === 'STUDENT') {
            if ((int)$predaja->student_id !== (int)$user->id) {
                return response()->json(['message' => 'Zabranjeno'], 403);
            }
        }

        // PROFESOR vidi predaje samo za svoje predmete
        if ($user->uloga === 'PROFESOR') {
            $predmetId = $predaja->zadatak?->predmet_id;

            // sigurnosno: ako je nešto polomljeno u relaciji
            if (!$predmetId) {
                return response()->json(['message' => 'Predaja nema vezan predmet.'], 409);
            }

            $predmetJeNjegov = Predmet::where('id', $predmetId)
                ->where('profesor_id', $user->id)
                ->exists();

            if (!$predmetJeNjegov) {
                return response()->json(['message' => 'Zabranjeno'], 403);
            }
        }

        return new PredajaResource($predaja);
    }


    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->uloga !== 'STUDENT') {
            return response()->json(['message' => 'Zabranjeno'], 403);
        }

        $validator = Validator::make($request->all(), [
            'zadatak_id' => 'required|integer|exists:zadaci,id',
            'file_path' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Proveri da li je student upisan na predmet tog zadatka
        $zadatakId = $request->zadatak_id;

        $upisan = $user->predmeti()
            ->whereHas('zadaci', fn($q) => $q->where('zadaci.id', $zadatakId))
            ->exists();

        if (!$upisan) {
            return response()->json(['message' => 'Zabranjeno'], 403);
        }

        // (opciono) zabrani duplu predaju po zadatku (pošto ima unique u bazi)
        $vecPostoji = Predaja::where('student_id', $user->id)
            ->where('zadatak_id', $zadatakId)
            ->exists();
        if ($vecPostoji) {
            return response()->json(['message' => 'Već postoji predaja za ovaj zadatak.'], 409);
        }

        $predaja = Predaja::create([
            'zadatak_id' => $zadatakId,
            'student_id' => $user->id,
            'status' => 'PREDATO',
            'file_path' => $request->file_path,
            'submitted_at' => now(),
        ]);

        return response()->json([
            'message' => 'Predaja je uspešno kreirana.',
            'data' => new PredajaResource($predaja->load(['student', 'zadatak.predmet'])),
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $predaja = Predaja::with('zadatak.predmet')->findOrFail($id);

        if ($user->uloga === 'STUDENT') {
            return response()->json(['message' => 'Zabranjeno'], 403);
        }

        if ($user->uloga === 'PROFESOR') {
            $predmetId = $predaja->zadatak?->predmet_id;
            $predmetJeNjegov = Predmet::where('id', $predmetId)->where('profesor_id', $user->id)->exists();
            if (!$predmetJeNjegov) return response()->json(['message' => 'Zabranjeno'], 403);
        }

        $allowedStatus = ['PREDATO', 'OCENJENO', 'VRACENO', 'ZAKASNJENO'];

        $validator = Validator::make($request->all(), [

            'status' => ['sometimes', Rule::in($allowedStatus)],
            'ocena' => 'sometimes|nullable|numeric|min:0|max:10',
            'komentar' => 'sometimes|nullable|string',
            'file_path' => 'sometimes|string|max:255',
            'submitted_at' => 'sometimes|nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $predaja->update($validator->validated());

        return response()->json(
            new PredajaResource($predaja->load(['student', 'zadatak'])),
            200
        );
    }

    public function destroy($id)
    {
        $predaja = Predaja::find($id);
        if (!$predaja) {
            return response()->json(['message' => 'Predaja nije pronađena.'], 404);
        }

        $predaja->delete();

        return response()->json(['message' => 'Predaja je uspešno obrisana.'], 200);
    }

    public function moje()
    {
        $user = auth()->user();

        return PredajaResource::collection(
            Predaja::with(['student', 'zadatak'])
                ->where('student_id', $user->id)
                ->get()
        );
    }

    public function zaMojePredmete()
    {
        $user = auth()->user();

        return PredajaResource::collection(
            Predaja::with(['student', 'zadatak.predmet', 'proveraPlagijata'])
                ->whereHas('zadatak.predmet', function ($q) use ($user) {
                    $q->where('profesor_id', $user->id);
                })
                ->get()
        );
    }
}
