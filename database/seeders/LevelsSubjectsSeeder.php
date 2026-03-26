<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class LevelsSubjectsSeeder extends Seeder
{
    public function run(): void
    {
        // ── Niveaux (ordre croissant MENA CI) ─────────────────────────────
        $levels = [
            ['name' => 'CP1', 'order' => 1],
            ['name' => 'CP2', 'order' => 2],
            ['name' => 'CE1', 'order' => 3],
            ['name' => 'CE2', 'order' => 4],
            ['name' => 'CM1', 'order' => 5],
            ['name' => 'CM2', 'order' => 6],
        ];

        foreach ($levels as $level) {
            Level::firstOrCreate(
                ['name' => $level['name']],
                ['order' => $level['order']]
            );
        }

        $this->command->info('✅ Niveaux créés : CP1 → CM2');

        // ── Matières (programme primaire ivoirien MENA) ───────────────────
        $subjects = [
            ['name' => 'Français',               'code' => 'FR',  'coefficient' => 3],
            ['name' => 'Mathématiques',           'code' => 'MA',  'coefficient' => 3],
            ['name' => 'Sciences d\'Éveil',       'code' => 'SE',  'coefficient' => 2],
            ['name' => 'Histoire-Géographie',     'code' => 'HG',  'coefficient' => 2],
            ['name' => 'Éducation Civique',       'code' => 'EC',  'coefficient' => 1],
            ['name' => 'Anglais',                 'code' => 'AN',  'coefficient' => 2],
            ['name' => 'Éducation Physique',      'code' => 'EPS', 'coefficient' => 1],
            ['name' => 'Dessin / Arts Plastiques','code' => 'ART', 'coefficient' => 1],
            ['name' => 'Chant / Musique',         'code' => 'MUS', 'coefficient' => 1],
            ['name' => 'Informatique',            'code' => 'INF', 'coefficient' => 1],
        ];

        foreach ($subjects as $subject) {
            Subject::firstOrCreate(
                ['code' => $subject['code']],
                [
                    'name'        => $subject['name'],
                    'coefficient' => $subject['coefficient'],
                    'is_active'   => true,
                ]
            );
        }

        $this->command->info('✅ Matières créées (' . count($subjects) . ' matières).');
    }
}