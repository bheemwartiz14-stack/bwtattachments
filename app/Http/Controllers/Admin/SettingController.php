<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService,
    ) {}

    public function general(): View
    {
        return view('admin.setting.general', [
            'settings' => $this->settingService->getAll(),
        ]);
    }

    public function updateGeneral(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_title' => ['required', 'string', 'max:255'],
            'support_email' => ['required', 'email', 'max:255'],
            'support_phone' => ['required', 'string', 'max:50'],
            'address_line_1' => ['required', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pin_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
            'logo_path_temp' => ['nullable', 'string'],
            'logo_favicon_temp' => ['nullable', 'string'],
        ]);

        $this->settingService->update($validated);

        return redirect()->route('admin.settings.general')
            ->with('success', 'Settings updated successfully.');
    }
}
