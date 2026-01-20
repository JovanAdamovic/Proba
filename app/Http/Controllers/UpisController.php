<?php

namespace App\Http\Controllers;

use App\Http\Resources\UpisResource;
use App\Models\Upis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpisController extends Controller
{
    public function index()
    {
        return UpisResource::collection(
            Upis::with(['student', 'predmet'])->get()
        );
    }

    public function show($id)
    {
        $upis = Upis::with(['student', 'predmet'])->findOrFail($id);
        return new UpisResource($upis);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|integer|exists:users,id',
            'predmet_id' => 'required|integer|exists:predmeti,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $upis = Upis::create($validator->validated());

        return response()->json([
            'message' => 'Upis je uspešno kreiran.',
            'data' => new UpisResource($upis->load(['student','predmet'])),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $upis = Upis::find($id);
        if (!$upis) {
            return response()->json(['message' => 'Upis nije pronađen.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'student_id' => 'sometimes|integer|exists:users,id',
            'predmet_id' => 'sometimes|integer|exists:predmeti,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $upis->update($validator->validated());

        return response()->json(new UpisResource($upis->load(['student','predmet'])), 200);
    }

    public function destroy($id)
    {
        $upis = Upis::find($id);
        if (!$upis) {
            return response()->json(['message' => 'Upis nije pronađen.'], 404);
        }

        $upis->delete();

        return response()->json(['message' => 'Upis je uspešno obrisan.'], 200);
    }
}
