<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Predmet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\PredmetResource;
use Illuminate\Support\Facades\Validator;

class PredmetController extends Controller
{
    // GET /api/predmeti
    public function index()
    {
        /*  $predmeti = Predmet::orderBy('godina_studija')
            ->orderBy('naziv')
            ->get(); */

        //return PredmetResource::collection(Predmet::all());
        return $this->moji();
    }

    // GET /api/predmeti/{predmet}
    public function show($id)
    {
        $user = auth()->user();
        $predmet = Predmet::findOrFail($id);

        // ADMIN vidi sve
        if ($user->uloga === 'ADMIN') {
            return new PredmetResource($predmet);
        }

        // STUDENT vidi samo predmete na koje je upisan
        if ($user->uloga === 'STUDENT') {
            $upisan = $user->predmeti()
                ->where('predmeti.id', $predmet->id)
                ->exists();

            if (!$upisan) {
                return response()->json(['message' => 'Zabranjeno'], 403);
            }
        }

        // PROFESOR vidi samo predmete koje predaje
        if ($user->uloga === 'PROFESOR') {
            if ((int)$predmet->profesor_id !== (int)$user->id) {
                return response()->json(['message' => 'Zabranjeno'], 403);
            }
        }

        return new PredmetResource($predmet);
    }



    // POST /api/predmeti
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->uloga !== 'ADMIN') {
            return response()->json(['message' => 'Zabranjeno'], 403);
        }

        $validator = Validator::make($request->all(), [
            'profesor_id' => ['nullable', 'exists:users,id'],
            'naziv' => ['required', 'string', 'max:255'],
            'sifra' => ['required', 'string', 'max:50', 'unique:predmeti,sifra'],
            'godina_studija' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $predmet = Predmet::create($data);

        return response()->json(new PredmetResource($predmet), 201);
    }

    // PUT /api/predmeti/{predmet}
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->uloga !== 'ADMIN') {
            return response()->json(['message' => 'Zabranjeno'], 403);
        }

        $predmet = Predmet::find($id);

        if (!$predmet) {
            return response()->json(['message' => 'Predmet nije pronađen.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'profesor_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'naziv' => ['sometimes', 'required', 'string', 'max:255'],
            'sifra' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('predmeti', 'sifra')->ignore($predmet->id)],
            'godina_studija' => ['sometimes', 'required', 'integer', 'min:1', 'max:8'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors'  => $validator->errors(),
            ], 422);
        }
        $data = $validator->validated();
        $predmet->update($data);

        return response()->json(new PredmetResource($predmet), 200);
    }

    // DELETE /api/predmeti/{predmet}
    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->uloga !== 'ADMIN') {
            return response()->json(['message' => 'Zabranjeno'], 403);
        }

        $predmet = Predmet::find($id);

        if (!$predmet) {
            return response()->json(['message' => 'Predmet nije pronađen.'], 404);
        }

        $predmet->delete();
        return response()->json(['message' => 'Predmet je obrisan.'], 200);
    }

    public function moji()
    {
        $user = auth()->user();

        if ($user->uloga === 'STUDENT') {
            return PredmetResource::collection(
                $user->predmeti()->get()
            );
        }

        if ($user->uloga === 'PROFESOR') {
            return PredmetResource::collection(
                Predmet::where('profesor_id', $user->id)->get()
            );
        }

        // ADMIN
        return PredmetResource::collection(Predmet::all());
    }
}
