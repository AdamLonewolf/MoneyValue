<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Il s'agira du seeder qui permettra d'envoyer les informations de l'admin
     */
    public function run(): void
    {
        $user = new User();
        $user->name = "Adam Coulibaly";
        $user->email = "adam@gmail.com";
        $user->password = Hash::make("2403");
        $user->role_id = 1;
        $user->save();
    }
}
