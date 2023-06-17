<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Pour connecter tous les seeders
     */
    public function run(): void
    {
       
$this->call([
    UserSeeder::class,
    CurrencySeeder::class,
    ConvertSeeder::class
]);
    }
}
