<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;



class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class, // 1. Rôles & permissions (en premier)
            LevelsSubjectsSeeder::class,       // 2. Niveaux & matières
            UsersSeeder::class,                // 3. Utilisateurs de test (après les rôles)
        ]);
    }
}