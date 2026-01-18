<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('zadaci', function (Blueprint $table) {
        $table->id();

        $table->foreignId('predmet_id')
            ->constrained('predmeti')
            ->cascadeOnDelete();

        $table->foreignId('profesor_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->string('naslov');
        $table->text('opis')->nullable();
        $table->dateTime('rok_predaje');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zadaci');
    }
};
