<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopSettingController extends Controller
{
    public function edit(): View
    {
        return view('owner.settings.edit', [
            'shop' => auth()->user()->shop,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $shop = auth()->user()->shop;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'gstin' => ['nullable', 'string', 'max:20'],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('logos', 'public');
        }

        $shop->update($validated);

        return redirect()->route('owner.settings.edit')->with('status', 'Shop settings updated.');
    }
}
