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

        return PredmetResource::collection(Predmet::all());
    }

    // GET /api/predmeti/{predmet}
    public function show($id)
    {
        //return Predmet::find($id);
        return new PredmetResource(Predmet::findOrFail($id));
    }

    // POST /api/predmeti
    public function store(Request $request)
    {
        $validated = $request->validate([
            'naziv' => ['required', 'string', 'max:255'],
            'sifra' => ['required', 'string', 'max:50', 'unique:predmeti,sifra'],
            'godina_studija' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $predmet = Predmet::create($validated);

        return response()->json([
            'message' => 'Predmet je uspešno kreiran.',
            'data' => new PredmetResource($predmet),
        ], 201);

    }

    // PUT /api/predmeti/{predmet}
    public function update(Request $request, $id)
    {
        $predmet = Predmet::find($id);

           if (!$predmet) {
            return response()->json(['message' => 'Novčanik nije pronađen.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'naziv' => ['sometimes', 'required', 'string', 'max:255'],
            'sifra' => ['sometimes','required','string','max:50',Rule::unique('predmeti', 'sifra')->ignore($predmet->id)],
            'godina_studija' => ['sometimes', 'required', 'integer', 'min:1', 'max:8'],
          ]);

          if ($validator->fails()) {
            return response()->json([
                'message' => 'Validacija nije prošla.',
                'errors'  => $validator->errors(),
            ], 422);
        }
        $data=$validator->validated();
        $predmet->update($data);

       return response()->json(new PredmetResource($predmet), 200);
    }

    // DELETE /api/predmeti/{predmet}
      public function destroy($id)
    {
        $predmet = Predmet::find($id);

        if (!$predmet) {
            return response()->json(['message' => 'Predmet nije pronađen.'], 404);
        }

        $predmet->delete();
        return response()->json(['message' => 'Predmet je obrisan.'], 200);
    }
}
