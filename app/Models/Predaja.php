<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Predaja extends Model
{
    use HasFactory;

    protected $table = 'predaje';

    protected $fillable = [
        'zadatak_id',
        'student_id',
        'status',
        'ocena',
        'komentar',
        'file_path',
        'submitted_at',
    ];

    protected $casts = [
        'ocena' => 'decimal:2',
        'submitted_at' => 'datetime',
    ];


    //Jedna predaja pripada jednom zadatku
      public function zadatak()
    {
        return $this->belongsTo(Zadatak::class, 'zadatak_id');
    }

    //Jedna predaja pripada jednom studentu
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    //Jedna predaja ima jednu provjeru plagijata
    public function proveraPlagijata()
    {
        return $this->hasOne(ProveraPlagijata::class, 'predaja_id');
    }

}
