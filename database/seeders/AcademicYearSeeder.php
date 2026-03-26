<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\GradingConfig;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        // Désactiver toutes les années existantes
        AcademicYear::where('is_active', true)->update(['is_active' => false]);

        $year = AcademicYear::firstOrCreate(
            ['label' => '2025-2026'],
            [
                'start_date' => '2025-09-15',
                'end_date'   => '2026-07-04',
                'is_active'  => true,
            ]
        );

        // S'assurer qu'elle est active
        $year->update(['is_active' => true]);

        // Config de notation par défaut (devoirs 40%, composition 60%)
        GradingConfig::firstOrCreate(
            ['academic_year_id' => $year->id],
            [
                'homework_weight' => 40,
                'exam_weight'     => 60,
            ]
        );

        $this->command->info("✅ Année scolaire active : {$year->label}");
    }
}
