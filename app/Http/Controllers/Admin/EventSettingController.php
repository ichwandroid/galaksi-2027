<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventSettingController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'setting' => EventSetting::current(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'event_year' => ['required', 'string', 'max:16'],
            'event_date_label' => ['required', 'string', 'max:120'],
            'open_gate_label' => ['required', 'string', 'max:64'],
            'venue_name' => ['required', 'string', 'max:120'],
            'venue_city' => ['required', 'string', 'max:120'],
            'ticket_form_url' => ['nullable', 'string', 'max:255'],
            'tenant_url' => ['nullable', 'string', 'max:255'],
            'sponsor_url' => ['nullable', 'string', 'max:255'],
        ]);

        $setting = EventSetting::query()->first();

        if ($setting) {
            $setting->update($validated);
        } else {
            EventSetting::query()->create($validated);
        }

        return back()->with('success', 'Pengaturan acara berhasil disimpan.');
    }
}
