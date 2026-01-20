<?php

namespace App\Http\Controllers;

use App\Http\Resources\PredajaResource;
use App\Models\Predaja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PredajaController extends Controller
{
    public function index()
    {
        return PredajaResource::collection(
            Predaja::with(['student', 'zadatak'])->get()
        );
    }

    public function show($id)
    {
        $predaja = Predaja::with(['student', 'zadatak'])->findOrFail($id);
        return new PredajaResource($predaja);
    }

    public function store(Request $request)
    {
        // Status vrednosti (prilagodi ako imas druge)
        $allowedStatus = ['PREDATO', 'OCENJENO', 'VRACENO', 'ZAKASNJENO'];

        $validator = Validator::make($request->all(), [
            'zadatak_id' => 'required|integer|exists:zadaci,id',
            'student_id' => 'required|integer|exists:users,id',

            // ako hoces da dozvolis samo jedan rad po zadatku po studentu:
            // 'zadatak_id' => ['required','integer','exists:zadaci,id', Rule::unique('predaje')->where(fn($q)=>$q->where('student_id',$request->student_id))],

            'status' => ['required', Rule::in($allowedStatus)],
            'ocena' => 'nullable|numeric|min:0|max:10',
            'komentar' => 'nullable|string',
            'file_path' => 'required|string|max:255',
            'submitted_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $predaja = Predaja::create($validator->validated());

        return response()->json([
            'message' => 'Predaja je uspešno kreirana.',
            'data' => new PredajaResource($predaja->load(['student','zadatak'])),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $predaja = Predaja::find($id);
        if (!$predaja) {
            return response()->json(['message' => 'Predaja nije pronađena.'], 404);
        }

        $allowedStatus = ['PREDATO', 'OCENJENO', 'VRACENO', 'ZAKASNJENO'];

        $validator = Validator::make($request->all(), [
            'zadatak_id' => 'sometimes|integer|exists:zadaci,id',
            'student_id' => 'sometimes|integer|exists:users,id',

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
            new PredajaResource($predaja->load(['student','zadatak'])),
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
}
