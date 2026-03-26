<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

/**
 * Migration Spatie Settings — Paramètres de l'école.
 * Initialise toutes les entrées du groupe 'school' en base.
 */
class CreateSchoolSettings extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('school.name', '');
        $this->migrator->add('school.short_name', '');
        $this->migrator->add('school.logo', '');
        $this->migrator->add('school.address', '');
        $this->migrator->add('school.city', 'Abidjan');
        $this->migrator->add('school.phone', '');
        $this->migrator->add('school.email', '');
        $this->migrator->add('school.slogan', '');
        $this->migrator->add('school.director_name', '');
        $this->migrator->add('school.director_signature', '');
        $this->migrator->add('school.current_academic_year_id', null);
        $this->migrator->add('school.ministry_code', '');
        $this->migrator->add('school.district', '');
        $this->migrator->add('school.students_per_class_max', 45);
        $this->migrator->add('school.school_level', 'primaire');
    }
}
