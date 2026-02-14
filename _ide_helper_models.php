<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $zadatak_id
 * @property int $student_id
 * @property string $status
 * @property numeric|null $ocena
 * @property string|null $komentar
 * @property string|null $file_path
 * @property \Illuminate\Support\Carbon|null $submitted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProveraPlagijata|null $proveraPlagijata
 * @property-read \App\Models\User $student
 * @property-read \App\Models\Zadatak $zadatak
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereKomentar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereOcena($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predaja whereZadatakId($value)
 */
	class Predaja extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $profesor_id
 * @property string $naziv
 * @property string $sifra
 * @property int $godina_studija
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $profesor
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $profesori
 * @property-read int|null $profesori_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $studenti
 * @property-read int|null $studenti_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Upis> $upisi
 * @property-read int|null $upisi_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Zadatak> $zadaci
 * @property-read int|null $zadaci_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet whereGodinaStudija($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet whereNaziv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet whereProfesorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet whereSifra($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Predmet whereUpdatedAt($value)
 */
	class Predmet extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $predaja_id
 * @property numeric|null $procenat_slicnosti
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Predaja $predaja
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProveraPlagijata newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProveraPlagijata newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProveraPlagijata query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProveraPlagijata whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProveraPlagijata whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProveraPlagijata wherePredajaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProveraPlagijata whereProcenatSlicnosti($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProveraPlagijata whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProveraPlagijata whereUpdatedAt($value)
 */
	class ProveraPlagijata extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $student_id
 * @property int $predmet_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Predmet $predmet
 * @property-read \App\Models\User $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Upis newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Upis newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Upis query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Upis whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Upis whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Upis wherePredmetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Upis whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Upis whereUpdatedAt($value)
 */
	class Upis extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $ime
 * @property string $prezime
 * @property string $uloga
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Zadatak> $kreiraniZadaci
 * @property-read int|null $kreirani_zadaci_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Predaja> $predaje
 * @property-read int|null $predaje_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Predmet> $predmeti
 * @property-read int|null $predmeti_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Predmet> $predmetiKojePredaje
 * @property-read int|null $predmeti_koje_predaje_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Upis> $upisi
 * @property-read int|null $upisi_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIme($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePrezime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUloga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $predmet_id
 * @property int $profesor_id
 * @property string $naslov
 * @property string|null $opis
 * @property \Illuminate\Support\Carbon $rok_predaje
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Predaja> $predaje
 * @property-read int|null $predaje_count
 * @property-read \App\Models\Predmet $predmet
 * @property-read \App\Models\User $profesor
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak whereNaslov($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak whereOpis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak wherePredmetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak whereProfesorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak whereRokPredaje($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Zadatak whereUpdatedAt($value)
 */
	class Zadatak extends \Eloquent {}
}

