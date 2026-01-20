<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;      
use App\Models\Predmet;  

class Upis extends Model
{
    use HasFactory;

    protected $table = 'upisi';

    protected $fillable = [
        'student_id',
        'predmet_id',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function predmet()
    {
        return $this->belongsTo(Predmet::class, 'predmet_id');
    }
}
