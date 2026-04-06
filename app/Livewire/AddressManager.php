<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Address;
use Illuminate\Support\Facades\Auth;

class AddressManager extends Component
{
    public $addresses;
    public $isEditMode = false;
    public $editingId = null;

    // Form fields
    public $label, $recipient_name, $phone_number, $full_address, $city, $state, $postal_code, $is_default = false;

    protected $rules = [
        'label' => 'required|string|max:255',
        'recipient_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:20',
        'full_address' => 'required|string',
        'city' => 'required|string|max:255',
        'state' => 'nullable|string|max:255',
        'postal_code' => 'required|string|max:10',
        'is_default' => 'boolean',
    ];

    public function mount()
    {
        $this->loadAddresses();
    }

    public function loadAddresses()
    {
        $this->addresses = Address::where('user_id', Auth::id())->orderBy('is_default', 'desc')->get();
    }

    public function resetForm()
    {
        $this->reset(['label', 'recipient_name', 'phone_number', 'full_address', 'city', 'state', 'postal_code', 'is_default', 'isEditMode', 'editingId']);
    }

    public function create()
    {
        $this->resetForm();
        $this->isEditMode = true;
    }

    public function edit($id)
    {
        $address = Address::where('user_id', Auth::id())->findOrFail($id);
        $this->editingId = $id;
        $this->label = $address->label;
        $this->recipient_name = $address->recipient_name;
        $this->phone_number = $address->phone_number;
        $this->full_address = $address->full_address;
        $this->city = $address->city;
        $this->state = $address->state;
        $this->postal_code = $address->postal_code;
        $this->is_default = $address->is_default;
        $this->isEditMode = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->is_default) {
            Address::where('user_id', Auth::id())->update(['is_default' => false]);
        }

        if ($this->editingId) {
            $address = Address::where('user_id', Auth::id())->findOrFail($this->editingId);
            $address->update([
                'label' => $this->label,
                'recipient_name' => $this->recipient_name,
                'phone_number' => $this->phone_number,
                'full_address' => $this->full_address,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'is_default' => $this->is_default,
            ]);
            $msg = 'Alamat berhasil diperbarui!';
        } else {
            Address::create([
                'user_id' => Auth::id(),
                'label' => $this->label,
                'recipient_name' => $this->recipient_name,
                'phone_number' => $this->phone_number,
                'full_address' => $this->full_address,
                'city' => $this->city,
                'state' => $this->state,
                'postal_code' => $this->postal_code,
                'is_default' => $this->is_default,
            ]);
            $msg = 'Alamat baru berhasil ditambahkan!';
        }

        $this->resetForm();
        $this->loadAddresses();
        $this->dispatch('notify', ['message' => $msg, 'type' => 'success']);
    }

    public function delete($id)
    {
        Address::where('user_id', Auth::id())->findOrFail($id)->delete();
        $this->loadAddresses();
        $this->dispatch('notify', ['message' => 'Alamat berhasil dihapus!', 'type' => 'success']);
    }

    public function setDefault($id)
    {
        Address::where('user_id', Auth::id())->update(['is_default' => false]);
        Address::where('user_id', Auth::id())->findOrFail($id)->update(['is_default' => true]);
        $this->loadAddresses();
        $this->dispatch('notify', ['message' => 'Alamat utama berhasil diubah!', 'type' => 'success']);
    }

    public function render()
    {
        return view('livewire.address-manager');
    }
}
