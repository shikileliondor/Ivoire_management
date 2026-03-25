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
        $routeName = $request->route()?->getName();

        if (in_array($routeName, ['login', 'logout'], true) || str_starts_with((string) $routeName, 'settings.school.')) {
            return $next($request);
        }

        if ($request->user() !== null && blank(app(SchoolSettings::class)->name)) {
            return redirect()->route('settings.school.edit');
        }

        return $next($request);
    }
}
