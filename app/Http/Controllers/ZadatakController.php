<?php

namespace App\Http\Controllers;

use App\Http\Resources\ZadatakResource;
use App\Models\Zadatak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZadatakController extends Controller
{
    public function index()
    {
        return ZadatakResource::collection(
            Zadatak::with(['predmet', 'profesor'])->get()
        );
    }

    public function show($id)
    {
        $zadatak = Zadatak::with(['predmet', 'profesor'])->findOrFail($id);
        return new ZadatakResource($zadatak);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'predmet_id'   => 'required|exists:predmeti,id',
            'profesor_id'  => 'required|exists:users,id',
            'naslov'       => 'required|string|max:255',
            'opis'         => 'nullable|string',
            'rok_predaje'  => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $zadatak = Zadatak::create($validator->validated());

        return response()->json([
            'message' => 'Zadatak je uspešno kreiran.',
            'data' => new ZadatakResource(
                $zadatak->load(['predmet','profesor'])
            )
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $zadatak = Zadatak::find($id);

        if (!$zadatak) {
            return response()->json(['message' => 'Zadatak nije pronađen.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'predmet_id'   => 'sometimes|exists:predmeti,id',
            'profesor_id'  => 'sometimes|exists:users,id',
            'naslov'       => 'sometimes|string|max:255',
            'opis'         => 'sometimes|nullable|string',
            'rok_predaje'  => 'sometimes|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $zadatak->update($validator->validated());

        return response()->json(
            new ZadatakResource($zadatak->load(['predmet','profesor'])),
            200
        );
    }

    public function destroy($id)
    {
        $zadatak = Zadatak::find($id);

        if (!$zadatak) {
            return response()->json(['message' => 'Zadatak nije pronađen.'], 404);
        }

        $zadatak->delete();

        return response()->json([
            'message' => 'Zadatak je uspešno obrisan.'
        ], 200);
    }
}
