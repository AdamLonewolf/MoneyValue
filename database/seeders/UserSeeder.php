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
        $user->user_email = "philippe@gmail.com";
        $user->user_password = Hash::make("philippe2023");
        $user->role_id = 1;
        $user->save();
    }
}
