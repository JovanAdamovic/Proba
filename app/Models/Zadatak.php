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

    //Jedan zadatak pripada jednom predmetu
    public function predmet()
    {
        return $this->belongsTo(Predmet::class, 'predmet_id');
    }

    //Zadatak je postavio jedan profesor
    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }

    //Jedan zadatak ima više predaja
    public function predaje()
    {
        return $this->hasMany(Predaja::class, 'zadatak_id');
    }

}

