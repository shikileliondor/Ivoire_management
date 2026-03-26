<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Settings\SchoolSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class SchoolSettingsController extends Controller
{
    /**
     * Affiche le formulaire de configuration de l'école.
     */
    public function edit(SchoolSettings $settings): Response
    {
        return Inertia::render('Settings/School', [
            'settings' => [
                'name'          => $settings->name,
                'logo'          => $settings->logo
                    ? asset('storage/' . $settings->logo)
                    : null,
                'address'       => $settings->address,
                'phone'         => $settings->phone,
                'email'         => $settings->email,
                'slogan'        => $settings->slogan,
                'director_name' => $settings->director_name,
            ],
        ]);
    }

    /**
     * Enregistre la configuration de l'école.
     */
    public function update(Request $request, SchoolSettings $settings): RedirectResponse
    {
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:150'],
            'address'       => ['nullable', 'string', 'max:255'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'email'         => ['nullable', 'email', 'max:150'],
            'slogan'        => ['nullable', 'string', 'max:255'],
            'director_name' => ['nullable', 'string', 'max:150'],
            'logo'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'name.required' => 'Le nom de l\'école est obligatoire.',
            'logo.image'    => 'Le logo doit être une image.',
            'logo.max'      => 'Le logo ne doit pas dépasser 2 Mo.',
        ]);

        // Traitement du logo
        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo si existant
            if ($settings->logo && Storage::disk('public')->exists($settings->logo)) {
                Storage::disk('public')->delete($settings->logo);
            }
            $validated['logo'] = $request->file('logo')->store('logos', 'public');
        } else {
            // Conserver l'ancien logo si aucun nouveau fichier
            unset($validated['logo']);
        }

        // Sauvegarder les paramètres
        $settings->name          = $validated['name'];
        $settings->address       = $validated['address'] ?? '';
        $settings->phone         = $validated['phone'] ?? '';
        $settings->email         = $validated['email'] ?? '';
        $settings->slogan        = $validated['slogan'] ?? '';
        $settings->director_name = $validated['director_name'] ?? '';

        if (isset($validated['logo'])) {
            $settings->logo = $validated['logo'];
        }

        $settings->save();

        return redirect()->route('settings.school.edit')
            ->with('success', 'Paramètres de l\'école enregistrés avec succès.');
    }
}