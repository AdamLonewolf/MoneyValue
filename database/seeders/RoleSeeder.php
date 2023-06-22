<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Je crée des rôles prédéfinis pour les envoyer dans ma table
     */
    public function run(): void
    {
        $role = new Role();
        $role->user_role = "admin";
        $role->save();

        $role = new Role();
        $role->user_role = "utilisateur";
        $role->save();
    }
}
