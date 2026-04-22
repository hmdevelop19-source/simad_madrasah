<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemSettingController extends Controller
{
    /**
     * Show Institution Profile
     */
    public function indexInstitution()
    {
        $settings = SystemSetting::where('group', 'institution')->get()->pluck('value', 'key');
        return view('settings.school-profile', compact('settings'));
    }

    /**
     * Show App Profile
     */
    public function indexApp()
    {
        $settings = SystemSetting::where('group', 'app')->get()->pluck('value', 'key');
        return view('settings.app-profile', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $group = $request->input('group');
        // Ambil semua input termasuk file
        $inputs = $request->all();
        
        // Hapus token dan group agar tidak masuk dalam flattening
        unset($inputs['_token'], $inputs['group']);

        // Ratakan array nested (titik) menjadi flat key (contoh: app.name)
        $flattened = \Illuminate\Support\Arr::dot($inputs);

        foreach ($flattened as $key => $value) {
            // Handle file upload (Request::hasFile mendukung dot notation)
            if ($request->hasFile($key)) {
                $oldValue = SystemSetting::getValue($key);
                if ($oldValue && Storage::disk('public')->exists($oldValue)) {
                    Storage::disk('public')->delete($oldValue);
                }
                
                $path = $request->file($key)->store('settings', 'public');
                SystemSetting::set($key, $path, $group, 'file');
            } else {
                // Hanya simpan jika nilai bukan null (empty string tetap disimpan)
                if ($value !== null) {
                    SystemSetting::set($key, $value, $group, 'text');
                }
            }
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}
