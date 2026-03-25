<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Settings\SchoolSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SchoolSettingsController extends Controller
{
    public function edit(SchoolSettings $settings): Response
    {
        return Inertia::render('Settings/School', [
            'settings' => [
                'name' => $settings->name,
                'logo' => $settings->logo,
                'address' => $settings->address,
                'phone' => $settings->phone,
                'email' => $settings->email,
                'slogan' => $settings->slogan,
                'director_name' => $settings->director_name,
            ],
            'showWelcomeMessage' => blank($settings->name),
        ]);
    }

    public function update(Request $request, SchoolSettings $settings): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slogan' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'director_name' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('school', 'public');
        } else {
            unset($data['logo']);
        }

        foreach ($data as $key => $value) {
            $settings->{$key} = $value ?? '';
        }

        $settings->save();

        return back()->with('success', 'Configuration de l\'école enregistrée.');
    }
}
