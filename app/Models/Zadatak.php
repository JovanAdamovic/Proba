<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zadatak extends Model
{
    use HasFactory;

    protected $table = 'zadaci';

    protected $fillable = [
        'predmet_id',
        'profesor_id',
        'naslov',
        'opis',
        'rok_predaje',
    ];

    protected $casts = [
        'rok_predaje' => 'datetime',
    ];


}

