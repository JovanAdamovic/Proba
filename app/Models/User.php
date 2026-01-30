<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ime',
        'prezime',
        'email',
        'password',
        'uloga',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //jedan Korisnik ima 0 ili vise zadataka, a svaki zadatak zna kog profesora ima preko profesor_id
    public function kreiraniZadaci()
    {
        return $this->hasMany(Zadatak::class, 'profesor_id');
    }

    //jedan Korisnik ima 0 ili vise upisa, a svaki upis zna kog studenta ima preko student_id
    public function upisi()
    {
        return $this->hasMany(Upis::class, 'student_id');
    }

    //Jedan user može biti upisan na više predmeta, 
    //a jedan predmet može imati više usera (studenata).
    public function predmeti()
    {
        return $this->belongsToMany(Predmet::class, 'upisi', 'student_id', 'predmet_id');
    }

    // Jedan user (student) može imati više predaja
    // Svaka predaja pripada jednom studentu
    public function predaje()
    {
        return $this->hasMany(Predaja::class, 'student_id');
    }

    //Jedan profesor može predavati više predmeta
    //Jedan predmet može imati više profesora
    public function predmetiKojePredaje()
    {
        return $this->belongsToMany(Predmet::class, 'predmet_profesor', 'profesor_id', 'predmet_id');
    }

}
