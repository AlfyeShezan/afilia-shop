<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithFileUploads;
use App\Models\Vendor;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class VendorRegistration extends Component
{
    use WithFileUploads;

    public $name;
    public $description;
    public $logo;
    public $banner;

    protected $rules = [
        'name' => 'required|string|min:3|max:255|unique:vendors,name',
        'description' => 'required|string|min:10',
        'logo' => 'nullable|image|max:1024', // 1MB
        'banner' => 'nullable|image|max:2048', // 2MB
    ];

    public function mount()
    {
        if (Auth::user()->vendor) {
            return redirect()->route('dashboard'); // Already a vendor
        }
    }

    public function registerVendor()
    {
        $this->validate();

        $logoPath = $this->logo ? $this->logo->store('vendors/logos', 'public') : null;
        $bannerPath = $this->banner ? $this->banner->store('vendors/banners', 'public') : null;

        // Ensure vendor role exists
        Role::findOrCreate('vendor');

        $vendor = Vendor::create([
            'user_id' => Auth::id(),
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'description' => $this->description,
            'logo' => $logoPath,
            'banner' => $bannerPath,
            'status' => 'pending', // Requires admin approval
        ]);

        Auth::user()->assignRole('vendor');

        session()->flash('message', 'Pendaftaran berhasil dikirim! Silakan tunggu persetujuan admin.');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.vendor-registration');
    }
}
