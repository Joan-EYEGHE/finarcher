<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // L'ordre est important : devises d'abord, puis les données de démo
        $this->call([
            DeviseSeeder::class,
            DemoSeeder::class,
        ]);
    }
}