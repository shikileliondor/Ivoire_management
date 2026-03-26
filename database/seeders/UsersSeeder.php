<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // ── Directeur ─────────────────────────────────────────────────────
        $directeur = User::firstOrCreate(
            ['email' => 'directeur@edugest.ci'],
            [
                'name'      => 'Directeur Principal',
                'password'  => Hash::make('password'),
                'phone'     => '+2250701000001',
                'is_active' => true,
            ]
        );
        $directeur->assignRole('Directeur');

        // ── Secrétaire ────────────────────────────────────────────────────
        $secretaire = User::firstOrCreate(
            ['email' => 'secretaire@edugest.ci'],
            [
                'name'      => 'Aminata Koné',
                'password'  => Hash::make('password'),
                'phone'     => '+2250701000002',
                'is_active' => true,
            ]
        );
        $secretaire->assignRole('Secrétaire');

        // ── Enseignant ────────────────────────────────────────────────────
        $enseignant = User::firstOrCreate(
            ['email' => 'enseignant@edugest.ci'],
            [
                'name'      => 'Kouassi Brou',
                'password'  => Hash::make('password'),
                'phone'     => '+2250701000003',
                'is_active' => true,
            ]
        );
        $enseignant->assignRole('Enseignant');

        $this->command->info('✅ Utilisateurs de test créés :');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['Directeur',  'directeur@edugest.ci',  'password'],
                ['Secrétaire', 'secretaire@edugest.ci', 'password'],
                ['Enseignant', 'enseignant@edugest.ci', 'password'],
            ]
        );
    }
}