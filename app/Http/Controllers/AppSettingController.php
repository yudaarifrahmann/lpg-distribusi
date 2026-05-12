<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    public function edit()
    {
        $settings = AppSetting::landingValues();

        return view('settings.landing', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:80'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'landing_badge' => ['required', 'string', 'max:120'],
            'landing_title' => ['required', 'string', 'max:140'],
            'landing_subtitle' => ['required', 'string', 'max:300'],
            'landing_primary_button' => ['required', 'string', 'max:40'],
            'landing_secondary_button' => ['required', 'string', 'max:40'],
            'landing_feature_1_title' => ['required', 'string', 'max:80'],
            'landing_feature_1_body' => ['required', 'string', 'max:220'],
            'landing_feature_2_title' => ['required', 'string', 'max:80'],
            'landing_feature_2_body' => ['required', 'string', 'max:220'],
            'landing_feature_3_title' => ['required', 'string', 'max:80'],
            'landing_feature_3_body' => ['required', 'string', 'max:220'],
            'landing_stat_1_value' => ['required', 'string', 'max:30'],
            'landing_stat_1_label' => ['required', 'string', 'max:60'],
            'landing_stat_2_value' => ['required', 'string', 'max:30'],
            'landing_stat_2_label' => ['required', 'string', 'max:60'],
            'landing_stat_3_value' => ['required', 'string', 'max:30'],
            'landing_stat_3_label' => ['required', 'string', 'max:60'],
            'landing_stat_4_value' => ['required', 'string', 'max:30'],
            'landing_stat_4_label' => ['required', 'string', 'max:60'],
            'whatsapp_admin' => ['required', 'string', 'max:20'],
        ]);

        if ($request->hasFile('logo')) {
            $oldLogo = AppSetting::getValue('logo_path');

            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $validated['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        unset($validated['logo']);

        foreach ($validated as $key => $value) {
            AppSetting::setValue($key, $value);
        }

        return back()->with('success', 'Pengaturan landing page berhasil diperbarui.');
    }
}
