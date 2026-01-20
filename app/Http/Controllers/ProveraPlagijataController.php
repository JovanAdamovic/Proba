<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProveraPlagijataResource;
use App\Models\Predaja;
use App\Models\ProveraPlagijata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProveraPlagijataController extends Controller
{
    public function index()
    {
        return ProveraPlagijataResource::collection(
            ProveraPlagijata::with(['predaja'])->get()
        );
    }

    public function show($id)
    {
        $provera = ProveraPlagijata::with(['predaja'])->findOrFail($id);
        return new ProveraPlagijataResource($provera);
    }

    public function store(Request $request)
    {
        // ako ti je u migraciji enum, ovde treba da bude isto
        $allowedStatus = ['U_TOKU', 'ZAVRSENO', 'GRESKA'];

        $validator = Validator::make($request->all(), [
            'predaja_id' => [
                'required',
                'integer',
                'exists:predaje,id',
                // ako hoces 1 provera po predaji (preporuka)
                Rule::unique('provera_plagijata', 'predaja_id'),
            ],
            'procenat_slicnosti' => 'required|numeric|min:0|max:100',
            'status' => ['required', Rule::in($allowedStatus)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $provera = ProveraPlagijata::create($validator->validated());

        return response()->json([
            'message' => 'Provera plagijata je uspešno kreirana.',
            'data' => new ProveraPlagijataResource($provera->load('predaja')),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $provera = ProveraPlagijata::find($id);
        if (!$provera) {
            return response()->json(['message' => 'Provera nije pronađena.'], 404);
        }

        $allowedStatus = ['U_TOKU', 'ZAVRSENO', 'GRESKA'];

        $validator = Validator::make($request->all(), [
            'predaja_id' => [
                'sometimes',
                'integer',
                'exists:predaje,id',
                Rule::unique('provera_plagijata', 'predaja_id')->ignore($provera->id),
            ],
            'procenat_slicnosti' => 'sometimes|numeric|min:0|max:100',
            'status' => ['sometimes', Rule::in($allowedStatus)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $provera->update($validator->validated());

        return response()->json(
            new ProveraPlagijataResource($provera->load('predaja')),
            200
        );
    }

    public function destroy($id)
    {
        $provera = ProveraPlagijata::find($id);
        if (!$provera) {
            return response()->json(['message' => 'Provera nije pronađena.'], 404);
        }

        $provera->delete();

        return response()->json(['message' => 'Provera je uspešno obrisana.'], 200);
    }


    public function pokreni($predajaId)
    {
        $predaja = Predaja::find($predajaId);
        if (!$predaja) {
            return response()->json(['message' => 'Predaja nije pronađena.'], 404);
        }

        $postojeca = ProveraPlagijata::where('predaja_id', $predajaId)->first();
        if ($postojeca) {
            return response()->json([
                'predaja_id' => $predajaId,
                'procenat_slicnosti' => (float) $postojeca->procenat_slicnosti,
                'status' => $postojeca->status,
            ], 200);
        }

        $procenat = random_int(0, 100);

        $provera = ProveraPlagijata::create([
            'predaja_id' => $predajaId,
            'procenat_slicnosti' => $procenat,
            'status' => 'ZAVRSENO', // mora postojati u ENUM-u u migraciji
        ]);

        return response()->json([
            'predaja_id' => $predajaId,
            'procenat_slicnosti' => (float) $provera->procenat_slicnosti,
            'status' => $provera->status,
        ], 201);
    }
}
