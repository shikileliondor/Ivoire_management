<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Vider le cache des permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Toutes les permissions ────────────────────────────────────────
        $permissions = [
            // Élèves
            'students.view', 'students.create', 'students.edit', 'students.delete',

            // Classes
            'classrooms.view', 'classrooms.create', 'classrooms.edit', 'classrooms.delete',

            // Personnel
            'staff.view', 'staff.create', 'staff.edit', 'staff.delete',

            // Emploi du temps
            'timetable.view', 'timetable.manage',

            // Notes
            'grades.view', 'grades.create', 'grades.edit',

            // Absences
            'absences.view', 'absences.create', 'absences.edit',

            // Paiements
            'payments.view', 'payments.create', 'payments.edit', 'payments.delete',

            // Documents
            'documents.view', 'documents.create', 'documents.delete',

            // Rapports
            'reports.view', 'reports.export',

            // Paramètres
            'settings.manage',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // ── Rôle : Directeur (accès total) ───────────────────────────────
        $directeur = Role::firstOrCreate(['name' => 'Directeur']);
        $directeur->syncPermissions($permissions);

        // ── Rôle : Secrétaire ─────────────────────────────────────────────
        $secretaire = Role::firstOrCreate(['name' => 'Secrétaire']);
        $secretaire->syncPermissions([
            'students.view', 'students.create', 'students.edit', 'students.delete',
            'classrooms.view',
            'absences.view', 'absences.create', 'absences.edit',
            'payments.view', 'payments.create', 'payments.edit',
            'documents.view', 'documents.create', 'documents.delete',
            'reports.view', 'reports.export',
        ]);

        // ── Rôle : Enseignant ─────────────────────────────────────────────
        $enseignant = Role::firstOrCreate(['name' => 'Enseignant']);
        $enseignant->syncPermissions([
            'students.view',
            'classrooms.view',
            'timetable.view',
            'grades.view', 'grades.create', 'grades.edit',
            'absences.view', 'absences.create', 'absences.edit',
            'documents.view',
            'reports.view',
        ]);

        $this->command->info('✅ Rôles et permissions créés.');
    }
}