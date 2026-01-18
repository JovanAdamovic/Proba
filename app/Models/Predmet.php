<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predmet extends Model
{
    use HasFactory;

    protected $table = 'predmeti';

    protected $fillable = [
        'naziv',
        'sifra',
        'godina_studija',
    ];

    
      public function zadaci()
    {
        return $this->hasMany(Zadatak::class, 'predmet_id');
    }

    public function upisi()
    {
        return $this->hasMany(Upis::class, 'predmet_id');
    }

    public function studenti()
    {
        return $this->belongsToMany(User::class, 'upisi', 'predmet_id', 'student_id');
    }



}

