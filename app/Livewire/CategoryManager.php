<?php

namespace App\Livewire;

use Livewire\Component;

use App\Models\Category;
use Illuminate\Support\Str;

class CategoryManager extends Component
{
    public $name, $slug, $parent_id, $is_active = true, $categoryId;
    public $isEditMode = false;
    public $confirmingDeletion = null;
    public bool $showForm = false;

    protected $rules = [
        'name' => 'required|min:3',
        'slug' => 'required|unique:categories,slug',
        'is_active' => 'boolean',
    ];

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) {
            $this->resetInput();
        }
    }

    public function save()
    {
        try {
            $this->validate($this->isEditMode ? [
                'name' => 'required|min:3',
                'slug' => 'required|unique:categories,slug,' . $this->categoryId,
                'parent_id' => 'nullable',
            ] : $this->rules);

            if ($this->isEditMode && $this->parent_id == $this->categoryId) {
                $this->addError('parent_id', 'Kategori tidak bisa menjadi induk bagi dirinya sendiri.');
                return;
            }

            if ($this->isEditMode) {
                $category = Category::findOrFail($this->categoryId);
                $category->update([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'parent_id' => $this->parent_id ?: null,
                    'is_active' => $this->is_active,
                ]);
                $message = 'Kategori berhasil diperbarui.';
            } else {
                Category::create([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'parent_id' => $this->parent_id ?: null,
                    'is_active' => $this->is_active,
                ]);
                $message = 'Kategori berhasil dibuat.';
            }

            $this->resetInput();
            session()->flash('message', $message);
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $this->resetInput();
            $category = Category::findOrFail($id);
            $this->categoryId = $id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->parent_id = $category->parent_id;
            $this->is_active = $category->is_active;
            $this->isEditMode = true;
            $this->showForm = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memuat data: ' . $e->getMessage());
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = $id;
    }

    public function delete()
    {
        if ($this->confirmingDeletion) {
            try {
                $category = Category::findOrFail($this->confirmingDeletion);
                $category->delete();
                $this->confirmingDeletion = null;
                $this->resetInput();
                session()->flash('message', 'Kategori berhasil dihapus.');
            } catch (\Exception $e) {
                session()->flash('error', 'Gagal menghapus kategori: ' . $e->getMessage());
            }
        }
    }

    public function resetInput()
    {
        $this->name = '';
        $this->slug = '';
        $this->parent_id = null;
        $this->is_active = true;
        $this->isEditMode = false;
        $this->categoryId = null;
        $this->showForm = false;
        $this->resetErrorBag();
    }

    public function render()
    {
        $allCategories = Category::with('parent')->get();
        
        $orderedCategories = collect();
        
        $parents = $allCategories->filter(fn($c) => is_null($c->parent_id));
        
        foreach ($parents as $parent) {
            $parent->depth = 0;
            $orderedCategories->push($parent);
            
            $children = $allCategories->filter(fn($c) => $c->parent_id == $parent->id);
            foreach ($children as $child) {
                $child->depth = 1;
                $orderedCategories->push($child);
                
                // Optional: support grand-children if needed
                $grandChildren = $allCategories->filter(fn($c) => $c->parent_id == $child->id);
                foreach ($grandChildren as $grandChild) {
                    $grandChild->depth = 2;
                    $orderedCategories->push($grandChild);
                }
            }
        }

        return view('livewire.category-manager', [
            'orderedCategories' => $orderedCategories,
            'parentCategories' => $allCategories->where('is_active', true)
        ]);
    }
}
