<?php

namespace App\Http\Controllers;

use App\Http\Resources\UpisResource;
use App\Models\Upis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpisController extends Controller
{
    private function proveriAdmina()
    {
        $user = auth()->user();
        if (!$user || $user->uloga !== 'ADMIN') {
            return response()->json(['message' => 'Zabranjeno'], 403);
        }

        return null;
    }

    public function index()
    {
        if ($response = $this->proveriAdmina()) {
            return $response;
        }

        return UpisResource::collection(
            Upis::with(['student', 'predmet'])->get()
        );
    }

    public function show($id)
    {
        if ($response = $this->proveriAdmina()) {
            return $response;
        }

        $upis = Upis::with(['student', 'predmet'])->findOrFail($id);
        return new UpisResource($upis);
    }

    public function store(Request $request)
    {
        if ($response = $this->proveriAdmina()) {
            return $response;
        }

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
            'data' => new UpisResource($upis->load(['student', 'predmet'])),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->proveriAdmina()) {
            return $response;
        }

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

        return response()->json(new UpisResource($upis->load(['student', 'predmet'])), 200);
    }

    public function destroy($id)
    {
        if ($response = $this->proveriAdmina()) {
            return $response;
        }

        $upis = Upis::find($id);
        if (!$upis) {
            return response()->json(['message' => 'Upis nije pronađen.'], 404);
        }

        $upis->delete();

        return response()->json(['message' => 'Upis je uspešno obrisan.'], 200);
    }
}
