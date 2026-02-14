<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zadatak extends Model
{

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

    public function predmet()
    {
        return $this->belongsTo(Predmet::class, 'predmet_id');
    }

    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    public function predaje()
    {
        return $this->hasMany(Predaja::class, 'zadatak_id');
    }

}

