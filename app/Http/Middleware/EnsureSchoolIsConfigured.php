<?php

namespace App\Http\Middleware;

use App\Settings\SchoolSettings;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolIsConfigured
{
    public function handle(Request $request, Closure $next): Response
    {
        $user      = $request->user();
        $routeName = (string) ($request->route()?->getName() ?? '');

        // Toujours laisser passer : login, logout, et les routes settings.school.*
        if (
            in_array($routeName, ['login', 'login.store', 'logout'], true)
            || str_starts_with($routeName, 'settings.school.')
        ) {
            return $next($request);
        }

        // Rediriger le Directeur vers la configuration si l'école n'est pas encore paramétrée.
        // Les autres rôles (Secrétaire, Enseignant) passent directement — ils ne peuvent pas configurer.
        try {
            $schoolName = app(SchoolSettings::class)->name;
        } catch (\Throwable) {
            // Table settings absente (avant migrate) — on laisse passer
            return $next($request);
        }

        if ($user !== null && $user->hasRole('Directeur') && blank($schoolName)) {
            return redirect()->route('settings.school.edit')
                ->with('warning', 'Veuillez d\'abord configurer les paramètres de l\'école.');
        }

        return $next($request);
    }
}
