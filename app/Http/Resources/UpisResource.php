<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpisResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'predmet_id' => $this->predmet_id,

            'student' => $this->whenLoaded('student', fn() => [
                'id' => $this->student->id,
                'ime' => $this->student->ime,
                'prezime' => $this->student->prezime,
                'email' => $this->student->email,
            ]),

            'predmet' => $this->whenLoaded('predmet', fn() => [
                'id' => $this->predmet->id,
                'naziv' => $this->predmet->naziv,
                'sifra' => $this->predmet->sifra,
                'godina_studija' => $this->predmet->godina_studija,
            ]),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
