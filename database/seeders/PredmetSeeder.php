<?php

namespace Database\Seeders;

use App\Models\Predmet;
use Illuminate\Database\Seeder;

class PredmetSeeder extends Seeder
{
    public function run(): void
    {
        Predmet::insert([
            [
                'naziv' => 'Internet tehnologije',
                'sifra' => 'IT2025',
                'godina_studija' => 3,
            ],
            [
                'naziv' => 'Baze podataka',
                'sifra' => 'BP2025',
                'godina_studija' => 2,
            ],
            [
                'naziv' => 'Softversko inženjerstvo',
                'sifra' => 'SI2025',
                'godina_studija' => 3,
            ],
        ]);
    }
}
