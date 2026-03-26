<?php

namespace App\Http\Middleware;

use App\Settings\SchoolSettings;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * Template racine chargé au premier rendu de page.
     */
    protected $rootView = 'app';

    /**
     * Version des assets — force un rechargement côté client si elle change.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Données partagées avec toutes les pages Vue via usePage().
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        // Chargement sécurisé des settings (évite un crash avant migrate)
        try {
            $settings = app(SchoolSettings::class);
            $school = [
                'name'          => $settings->name,
                'logo'          => $settings->logo ? asset('storage/' . $settings->logo) : null,
                'address'       => $settings->address,
                'phone'         => $settings->phone,
                'email'         => $settings->email,
                'slogan'        => $settings->slogan,
                'director_name' => $settings->director_name,
            ];
        } catch (\Throwable) {
            $school = ['name' => '', 'logo' => null, 'address' => '', 'phone' => '', 'email' => '', 'slogan' => '', 'director_name' => ''];
        }

        return array_merge(parent::share($request), [

            // ── Utilisateur connecté ──────────────────────────────────────
            'auth' => [
                'user'  => $user ? [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo
                        ? asset('storage/' . $user->photo)
                        : null,
                ] : null,
                'roles' => $user ? $user->getRoleNames() : [],
            ],

            // ── Paramètres de l'école ─────────────────────────────────────
            'school' => $school,

            // ── Permissions (filtre le menu de la sidebar) ────────────────
            'can' => $user ? [
                'students.view'   => $user->can('students.view'),
                'students.create' => $user->can('students.create'),
                'students.edit'   => $user->can('students.edit'),
                'students.delete' => $user->can('students.delete'),

                'classrooms.view'   => $user->can('classrooms.view'),
                'classrooms.create' => $user->can('classrooms.create'),
                'classrooms.edit'   => $user->can('classrooms.edit'),

                'staff.view'   => $user->can('staff.view'),
                'staff.create' => $user->can('staff.create'),
                'staff.edit'   => $user->can('staff.edit'),

                'timetable.view'   => $user->can('timetable.view'),
                'timetable.manage' => $user->can('timetable.manage'),

                'grades.view'   => $user->can('grades.view'),
                'grades.create' => $user->can('grades.create'),
                'grades.edit'   => $user->can('grades.edit'),

                'absences.view'   => $user->can('absences.view'),
                'absences.create' => $user->can('absences.create'),

                'payments.view'   => $user->can('payments.view'),
                'payments.create' => $user->can('payments.create'),

                'documents.view'   => $user->can('documents.view'),
                'documents.create' => $user->can('documents.create'),

                'reports.view'    => $user->can('reports.view'),
                'reports.export'  => $user->can('reports.export'),

                'settings.manage' => $user->can('settings.manage'),
            ] : [],

            // ── Flash messages (affichés en toast par AppLayout) ──────────
            'flash' => [
                'success' => $request->session()->get('success'),
                'error'   => $request->session()->get('error'),
                'warning' => $request->session()->get('warning'),
                'info'    => $request->session()->get('info'),
            ],

            // ── Routes Ziggy (utilisées par route() dans Vue) ─────────────
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ]);
    }
}