<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SystemSettingManager extends Component
{
    use WithFileUploads;

    public $settings = [];
    public $activeTab = 'branding'; // branding, contact, social, legal
    public $uploads = []; // For temporary file storage

    protected $rules = [
        'settings.*.value' => 'nullable',
        'uploads.*' => 'nullable|image|max:2048', // 2MB max for images
    ];

    public function mount()
    {
        $defaultSettings = [
            // Branding Visual
            ['key' => 'logo_header', 'value' => '', 'group' => 'branding', 'type' => 'file'],
            ['key' => 'logo_dark', 'value' => '', 'group' => 'branding', 'type' => 'file'],
            ['key' => 'logo_footer', 'value' => '', 'group' => 'branding', 'type' => 'file'],
            ['key' => 'favicon', 'value' => '', 'group' => 'branding', 'type' => 'file'],
            ['key' => 'og_image', 'value' => '', 'group' => 'branding', 'type' => 'file'],

            // Informasi Kontak
            ['key' => 'app_name', 'value' => config('app.name'), 'group' => 'contact', 'type' => 'text'],
            ['key' => 'sys_email', 'value' => '', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'support_email', 'value' => '', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'whatsapp', 'value' => '', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'phone', 'value' => '', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'address', 'value' => '', 'group' => 'contact', 'type' => 'textarea'],
            ['key' => 'city', 'value' => '', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'province', 'value' => '', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'postal_code', 'value' => '', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'country', 'value' => '', 'group' => 'contact', 'type' => 'text'],

            // Sosial Media
            ['key' => 'instagram_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'facebook_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'tiktok_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'youtube_url', 'value' => '', 'group' => 'social', 'type' => 'text'],
            ['key' => 'twitter_url', 'value' => '', 'group' => 'social', 'type' => 'text'],

            // Informasi Legal
            ['key' => 'company_official_name', 'value' => '', 'group' => 'legal', 'type' => 'text'],
            ['key' => 'foundation_year', 'value' => date('Y'), 'group' => 'legal', 'type' => 'text'],
            ['key' => 'copyright_text', 'value' => '© ' . date('Y') . ' Afilia Market. All rights reserved.', 'group' => 'legal', 'type' => 'textarea'],
        ];

        foreach ($defaultSettings as $default) {
            Setting::firstOrCreate(
                ['key' => $default['key']],
                ['value' => $default['value'], 'group' => $default['group'], 'type' => $default['type']]
            );
        }

        $this->loadSettings();
    }

    public function loadSettings()
    {
        $this->settings = Setting::all()->keyBy('key')->toArray();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function save()
    {
        $this->validate();

        // Handle File Uploads
        foreach ($this->uploads as $key => $file) {
            if ($file) {
                // Delete old file if exists
                if (!empty($this->settings[$key]['value'])) {
                    Storage::disk('public')->delete($this->settings[$key]['value']);
                }

                $path = $file->store('branding', 'public');
                $this->settings[$key]['value'] = $path;
                
                Setting::where('key', $key)->update(['value' => $path]);
                Cache::forget("setting.{$key}");
            }
        }

        // Handle Text Settings
        foreach ($this->settings as $key => $setting) {
            if ($setting['type'] !== 'file') {
                Setting::where('key', $key)->update(['value' => $setting['value']]);
                Cache::forget("setting.{$key}");
            }
        }

        $this->uploads = [];
        $this->loadSettings();
        session()->flash('success', 'Pengaturan berhasil diperbarui.');
    }

    public function removeFile($key)
    {
        // Delete from storage if exists
        if (!empty($this->settings[$key]['value'])) {
            Storage::disk('public')->delete($this->settings[$key]['value']);
        }

        // Reset in database
        Setting::where('key', $key)->update(['value' => '']);
        
        // Reset in local state
        $this->settings[$key]['value'] = '';
        
        // Clear upload if exists
        if (isset($this->uploads[$key])) {
            unset($this->uploads[$key]);
        }

        Cache::forget("setting.{$key}");
        session()->flash('success', 'Aset branding berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.system-setting-manager')->layout('layouts.app');
    }
}
