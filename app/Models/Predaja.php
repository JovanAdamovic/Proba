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

}
