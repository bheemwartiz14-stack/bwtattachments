<?php
declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Setting\GeneralSettingServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected GeneralSettingServices $settingService,
    ) {}

    public function index(): View
    {
        $settings = $this->settingService->getAll();
        $logoUrl = $this->settingService->getMediaUrl('site_logo', 'site_logo');
        $logoId = $this->settingService->getMediaId('site_logo', 'site_logo');
        return view('admin.setting.genral-setting', compact('settings', 'logoUrl', 'logoId'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'site_title' => 'nullable|string|max:255',
            'site_address' => 'nullable|string|max:500',
            'contact_phone' => 'nullable|string|max:50',
            'contact_email' => 'nullable|email|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'footer_text' => 'nullable|string|max:500',
        ]);

        $this->settingService->saveTextSettings($data);

        $allData = $request->all();
        $this->settingService->handleMediaUpload($allData, 'site_logo', 'site_logo');

        return redirect()->route('admin.setting.genral-setting')
            ->with('success', 'Settings updated successfully.');
    }
}
