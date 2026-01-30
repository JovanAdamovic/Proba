<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveraPlagijata extends Model
{

    protected $table = 'provera_plagijata';

    protected $fillable = [
        'predaja_id',
        'procenat_slicnosti',
        'status',
    ];

    protected $casts = [
        'procenat_slicnosti' => 'decimal:2',
    ];

    //Jedna provera plagijata ima jednu predaju
    public function predaja()
    {
        return $this->belongsTo(Predaja::class, 'predaja_id');
    }

}
