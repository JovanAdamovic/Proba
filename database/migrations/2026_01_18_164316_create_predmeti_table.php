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
       Schema::create('predmeti', function (Blueprint $table) {
        $table->engine = 'InnoDB';
        $table->id();
        $table->foreignId('profesor_id')->nullable()
        ->constrained('users')->nullOnDelete();
        $table->string('naziv');
        $table->string('sifra')->unique();
        $table->unsignedInteger('godina_studija');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predmeti');
    }
};