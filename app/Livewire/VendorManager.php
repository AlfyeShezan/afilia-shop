<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vendor;

class VendorManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $editingVendor = null;
    public $vendorId, $name, $description, $status;
    public $isEditMode = false;
    public $confirmingDeletion = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function editVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        $this->vendorId = $id;
        $this->name = $vendor->name;
        $this->description = $vendor->description;
        $this->status = $vendor->status;
        $this->isEditMode = true;
    }

    public function cancelEdit()
    {
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->vendorId = null;
        $this->name = '';
        $this->description = '';
        $this->status = '';
        $this->isEditMode = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:3',
            'status' => 'required|in:pending,active,suspended',
        ]);

        $vendor = Vendor::findOrFail($this->vendorId);
        $vendor->update([
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
        ]);

        session()->flash('message', 'Vendor berhasil diperbarui.');
        $this->resetInput();
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = $id;
    }

    public function deleteVendor()
    {
        if ($this->confirmingDeletion) {
            Vendor::destroy($this->confirmingDeletion);
            $this->confirmingDeletion = null;
            session()->flash('message', 'Vendor berhasil dihapus.');
        }
    }

    public function render()
    {
        $query = Vendor::with('user');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function($uq) {
                      $uq->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        return view('livewire.vendor-manager', [
            'vendors' => $query->latest()->paginate(10),
        ]);
    }
}
