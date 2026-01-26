<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void //pravi izmene u bazi
    {
        Schema::create('zadaci', function (Blueprint $table) { //pravi se tabela zadaci
        $table->id(); //Primarni ključ zadatka.
 
        $table->foreignId('predmet_id') //U tabeli zadaci napravi kolonu: predmet_id. To je ID predmeta kojem zadatak pripada.
            ->constrained('predmeti') //Vrednost u predmet_id mora postojati kao id u tabeli predmeti
                                      //zadaci.predmet_id  →  predmeti.id
            ->cascadeOnDelete();     //Ako se obriše predmet — automatski obriši sve zadatke koji mu pripadaju

        $table->foreignId('profesor_id')// U tabeli zadaci napravi kolonu profesor_id. To je ID profesora kojem zadaci pripadaju.
            ->constrained('users') //Vrednost profesor_id mora postojati kao id u tabeli users
                                   //zadaci.profesor_id -> profesor.id
            ->cascadeOnDelete(); //Ako se obrise predmet - automatski obrisi sve profesore koji mu pripadaju

        $table->string('naslov'); // Pravi kolonu za naslove zadataka 
        $table->text('opis')->nullable(); //Pravi kolonu gde se pisu opisi zadataka, ako nema opisa moze biti null
        $table->dateTime('rok_predaje'); // pravi kolonu koja daje datum i tacno vreme do kada se predaje zadatak

        $table->timestamps();// automatski pravi dve kolone created_at, updated_at
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zadaci'); // ponistava izmene u bazi
    }
};
