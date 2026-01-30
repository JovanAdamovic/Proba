<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predmet extends Model
{

    protected $table = 'predmeti';

    protected $fillable = [
        'profesor_id',
        'naziv',
        'sifra',
        'godina_studija',
    ];

    //Jedan predmet ima više zadataka
    public function zadaci()
    {
        return $this->hasMany(Zadatak::class, 'predmet_id');
    }

    //Jedan predmet ima više upisa
    public function upisi()
    {
        return $this->hasMany(Upis::class, 'predmet_id');
    }

    //Predmet ima više studenata
    //Student ima više predmeta
    public function studenti()
    {
        return $this->belongsToMany(User::class, 'upisi', 'predmet_id', 'student_id');
    }

    //predmet ima jednog glavnog profesora
    public function profesor()
    {
        return $this->belongsTo(User::class, 'profesor_id');
    }
    
    //predmet može imati više profesora
    public function profesori() // povezano sa pivot tabelom
    {
        return $this->belongsToMany(User::class, 'predmet_profesor', 'predmet_id', 'profesor_id');
    }
}
