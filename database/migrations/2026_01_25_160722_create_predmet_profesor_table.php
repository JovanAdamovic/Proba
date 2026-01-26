<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */ 
    public function up(): void  //up() pravi izmene u bazi
    {
        Schema::create('predmet_profesor', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->foreignId('predmet_id') //kolona predmet_id povezana sa predmeti.id, 
                ->constrained('predmeti')
                ->cascadeOnDelete(); // ako se predmet obriše → brišu se i veze u pivot tabeli
            $table->foreignId('profesor_id') // profesor je zapravo user users.id se koristi kao profesor
                ->constrained('users')
                ->cascadeOnDelete(); //  ako se user/profesor obriše → brišu se njegove veze
            $table->unique(['predmet_id', 'profesor_id']); // samo jednom može isti profesor biti vezan za isti predmet
            $table->timestamps(); //created_at , upadated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void  //down()poništava te izmene
    {
        Schema::dropIfExists('predmet_profesor'); 
    }
};