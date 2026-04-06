<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Voucher;
use Illuminate\Support\Str;

class VoucherManager extends Component
{
    use WithPagination;

    // List / Search
    public string $search = '';
    public string $filterStatus = '';

    // Form state
    public bool $showForm = false;
    public bool $isEditing = false;
    public ?int $editingId = null;

    // Form fields
    public string $code = '';
    public string $name = '';
    public string $description = '';
    public string $type = 'fixed';
    public string $value = '';
    public string $min_spend = '0';
    public string $max_discount = '';
    public string $usage_limit = '';
    public string $per_user_limit = '1';
    public bool $is_active = true;
    public string $starts_at = '';
    public string $expires_at = '';

    // Delete confirm
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;

    protected function rules(): array
    {
        return [
            'code' => 'required|string|min:3|max:30|unique:vouchers,code,' . ($this->editingId ?? 'NULL'),
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'type' => 'required|in:fixed,percentage',
            'value' => 'required|numeric|min:0',
            'min_spend' => 'required|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
        ];
    }

    protected array $messages = [
        'code.required' => 'Kode voucher wajib diisi.',
        'code.unique' => 'Kode voucher sudah digunakan.',
        'name.required' => 'Nama voucher wajib diisi.',
        'value.required' => 'Nilai diskon wajib diisi.',
        'value.numeric' => 'Nilai diskon harus berupa angka.',
        'expires_at.after_or_equal' => 'Tanggal kedaluwarsa harus setelah tanggal mulai.',
    ];

    public function updatedSearch(): void { $this->resetPage(); }
    public function updatedFilterStatus(): void { $this->resetPage(); }

    public function generateCode(): void
    {
        $this->code = strtoupper(Str::random(8));
    }

    public function openCreateForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->isEditing = false;
    }

    public function openEditForm(int $id): void
    {
        $voucher = Voucher::findOrFail($id);
        $this->editingId = $id;
        $this->code = $voucher->code;
        $this->name = $voucher->name;
        $this->description = $voucher->description ?? '';
        $this->type = $voucher->type;
        $this->value = (string) $voucher->value;
        $this->min_spend = (string) $voucher->min_spend;
        $this->max_discount = $voucher->max_discount ?? '';
        $this->usage_limit = $voucher->usage_limit ?? '';
        $this->per_user_limit = (string) $voucher->per_user_limit;
        $this->is_active = $voucher->is_active;
        $this->starts_at = $voucher->starts_at ? $voucher->starts_at->format('Y-m-d\TH:i') : '';
        $this->expires_at = $voucher->expires_at ? $voucher->expires_at->format('Y-m-d\TH:i') : '';
        $this->showForm = true;
        $this->isEditing = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'code' => strtoupper(trim($this->code)),
            'name' => $this->name,
            'description' => $this->description ?: null,
            'type' => $this->type,
            'value' => (float) $this->value,
            'min_spend' => (float) $this->min_spend,
            'max_discount' => $this->max_discount !== '' ? (float) $this->max_discount : null,
            'usage_limit' => $this->usage_limit !== '' ? (int) $this->usage_limit : null,
            'per_user_limit' => (int) $this->per_user_limit,
            'is_active' => $this->is_active,
            'starts_at' => $this->starts_at ?: null,
            'expires_at' => $this->expires_at ?: null,
        ];

        if ($this->isEditing && $this->editingId) {
            Voucher::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'Voucher berhasil diperbarui!');
        } else {
            Voucher::create($data);
            session()->flash('success', 'Voucher berhasil dibuat!');
        }

        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Voucher::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Voucher berhasil dihapus.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function toggleActive(int $id): void
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->update(['is_active' => !$voucher->is_active]);
    }

    public function cancelForm(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->showForm = false;
        $this->isEditing = false;
        $this->editingId = null;
        $this->code = '';
        $this->name = '';
        $this->description = '';
        $this->type = 'fixed';
        $this->value = '';
        $this->min_spend = '0';
        $this->max_discount = '';
        $this->usage_limit = '';
        $this->per_user_limit = '1';
        $this->is_active = true;
        $this->starts_at = '';
        $this->expires_at = '';
        $this->resetValidation();
    }

    public function render()
    {
        $query = Voucher::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('code', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterStatus === 'active') {
            $query->active();
        } elseif ($this->filterStatus === 'inactive') {
            $query->where('is_active', false);
        } elseif ($this->filterStatus === 'expired') {
            $query->where('expires_at', '<', now());
        }

        $vouchers = $query->latest()->paginate(10);

        return view('livewire.voucher-manager', compact('vouchers'))->layout('layouts.app');
    }
}
