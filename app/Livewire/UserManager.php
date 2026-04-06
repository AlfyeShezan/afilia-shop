<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserManager extends Component
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    public $userId, $name, $email, $phone, $selectedRole, $is_active = true;
    public $password, $password_confirmation;
    public $isEditMode = false;
    public $confirmingDeletion = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'roleFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetInput();
        $this->isEditMode = true;
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->is_active = $user->is_active;
        $this->selectedRole = $user->getRoleNames()->first();
        $this->isEditMode = true;
    }

    public function cancelEdit()
    {
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->userId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->is_active = true;
        $this->selectedRole = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->isEditMode = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'phone' => 'nullable|string|max:20',
            'selectedRole' => 'required',
            'is_active' => 'boolean',
        ];

        if (!$this->userId) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        $this->validate($rules, [
            'name.required' => 'Nama harus diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi harus diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'selectedRole.required' => 'Peran harus dipilih.',
        ]);

        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'is_active' => $this->is_active,
            ]);
            $message = 'Pengguna berhasil diperbarui.';
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'is_active' => $this->is_active,
                'password' => bcrypt($this->password),
            ]);
            $message = 'Pengguna berhasil dibuat.';
        }

        $user->syncRoles([$this->selectedRole]);

        session()->flash('message', $message);
        $this->resetInput();
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);
        session()->flash('message', 'Status pengguna berhasil diubah.');
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = $id;
    }

    public function deleteUser()
    {
        if ($this->confirmingDeletion) {
            User::destroy($this->confirmingDeletion);
            $this->confirmingDeletion = null;
            session()->flash('message', 'Pengguna berhasil dihapus.');
        }
    }

    public function render()
    {
        $query = User::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->roleFilter) {
            $query->role($this->roleFilter);
        }

        return view('livewire.user-manager', [
            'users' => $query->latest()->paginate(10),
            'roles' => Role::all(),
            'stats' => [
                'total' => User::count(),
                'active' => User::where('is_active', true)->count(),
                'admins' => User::role(['super-admin', 'admin', 'staff'])->count(),
                'vendors' => User::role('vendor')->count(),
            ]
        ]);
    }
}
