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
         Schema::create('upisi', function (Blueprint $table) {
        $table->id();

        $table->foreignId('student_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->foreignId('predmet_id')
            ->constrained('predmeti')
            ->cascadeOnDelete();

        // student ne može 2x isti predmet
        $table->unique(['student_id', 'predmet_id']);

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upisi');
    }
};
