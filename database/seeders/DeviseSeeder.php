<?php

namespace Database\Seeders;

use App\Models\Devise;
use Illuminate\Database\Seeder;

class DeviseSeeder extends Seeder
{
    public function run(): void
    {
        $devises = [
            ['code' => 'XOF', 'symbole' => 'FCFA', 'nom' => 'Franc CFA'],
            ['code' => 'EUR', 'symbole' => '€', 'nom' => 'Euro'],
            ['code' => 'USD', 'symbole' => '$', 'nom' => 'Dollar américain'],
        ];

        foreach ($devises as $devise) {
            Devise::firstOrCreate(['code' => $devise['code']], $devise);
        }
    }
}