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
        Schema::create('provera_plagijata', function (Blueprint $table) {
        $table->id();

        $table->foreignId('predaja_id')
            ->constrained('predaje')
            ->cascadeOnDelete();

        // jedna predaja -> max jedna provera
        $table->unique('predaja_id');

        $table->decimal('procenat_slicnosti', 5, 2)->nullable();

        $table->enum('status', ['U_TOKU', 'ZAVRSENO', 'NEUSPELO'])
            ->default('U_TOKU');

        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provera_plagijata');
    }
};
