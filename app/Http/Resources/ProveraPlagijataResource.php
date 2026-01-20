<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProveraPlagijataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'predaja_id' => $this->predaja_id,
            'procenat_slicnosti' => (float) $this->procenat_slicnosti,
            'status' => $this->status,

            // ako ucitas relaciju predaja
            'predaja' => $this->whenLoaded('predaja', fn () => [
                'id' => $this->predaja->id,
                'zadatak_id' => $this->predaja->zadatak_id,
                'student_id' => $this->predaja->student_id,
                'status' => $this->predaja->status,
            ]),

            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s'),
        ];
    }
}
