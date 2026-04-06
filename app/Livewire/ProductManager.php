<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductManager extends Component
{
    use WithPagination, WithFileUploads;

    // Search & Filter
    public $search = '';
    public $filterCategory = '';
    public $confirmingDeletion = null;

    // Form Properties
    public $productId;
    public $name, $slug, $description, $short_description, $price, $sale_price, $cost_price, $stock, $sku, $barcode, $category_id, $status = 'active';
    public $weight, $length, $width, $height;
    
    // Images
    public $images = [];
    public $existingImages = [];

    public $isEditMode = false;

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'slug' => 'required|unique:products,slug,' . $this->productId,
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:products,sku,' . $this->productId,
            'images.*' => 'nullable|mimes:jpg,jpeg,png,gif,bmp,svg,webp,avif,heic,heif|max:5120', // 5MB Max
        ];
    }

    protected $messages = [
        'name.required' => 'Nama produk harus diisi.',
        'name.min' => 'Nama produk minimal 3 karakter.',
        'slug.required' => 'Slug harus diisi.',
        'slug.unique' => 'Slug sudah digunakan produk lain.',
        'price.required' => 'Harga harus diisi.',
        'price.numeric' => 'Harga harus berupa angka.',
        'category_id.required' => 'Kategori harus dipilih.',
        'stock.required' => 'Stok harus diisi.',
        'sku.required' => 'SKU harus diisi.',
        'sku.unique' => 'SKU sudah digunakan produk lain.',
        'images.*.image' => 'File harus berupa gambar.',
        'images.*.max' => 'Ukuran gambar maksimal 2MB.',
    ];

    public function updatedName($value)
    {
        $this->slug = Str::slug($value);
    }

    public function openModal()
    {
        $this->resetInput();
        $this->isEditMode = true;
    }

    public function closeModal()
    {
        $this->isEditMode = false;
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->productId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->short_description = '';
        $this->price = '';
        $this->sale_price = '';
        $this->cost_price = '';
        $this->stock = 0;
        $this->sku = '';
        $this->barcode = '';
        $this->category_id = '';
        $this->status = 'active';
        $this->weight = '';
        $this->length = '';
        $this->width = '';
        $this->height = '';
        $this->images = [];
        $this->existingImages = [];
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->price,
            'sale_price' => $this->sale_price ?: null,
            'cost_price' => $this->cost_price ?: null,
            'stock' => $this->stock,
            'sku' => $this->sku,
            'barcode' => $this->barcode ?: null,
            'weight' => $this->weight ?: null,
            'dimensions' => [
                'length' => $this->length,
                'width' => $this->width,
                'height' => $this->height,
            ],
            'status' => $this->status,
        ];

        if ($this->productId) {
            $product = Product::find($this->productId);
            $product->update($data);
            $message = 'Produk berhasil diperbarui.';
        } else {
            $product = Product::create($data);
            $message = 'Produk berhasil dibuat.';
        }

        // Handle Images
        if ($this->images) {
            foreach ($this->images as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $product->images()->count() === 0,
                ]);
            }
        }

        $this->closeModal();
        session()->flash('message', $message);
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $this->productId = $id;
        $this->name = $product->name;
        $this->slug = $product->slug;
        $this->short_description = $product->short_description;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->sale_price = $product->sale_price;
        $this->cost_price = $product->cost_price;
        $this->stock = $product->stock;
        $this->sku = $product->sku;
        $this->barcode = $product->barcode;
        $this->category_id = $product->category_id;
        $this->status = $product->status;
        $this->weight = $product->weight;
        $this->length = $product->dimensions['length'] ?? '';
        $this->width = $product->dimensions['width'] ?? '';
        $this->height = $product->dimensions['height'] ?? '';
        $this->existingImages = $product->images;
        
        $this->isEditMode = true;
    }

    public function deleteImage($imageId)
    {
        $image = ProductImage::find($imageId);
        if ($image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
            $this->existingImages = ProductImage::where('product_id', $this->productId)->get();
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingDeletion = $id;
    }

    public function delete()
    {
        if ($this->confirmingDeletion) {
            $product = Product::findOrFail($this->confirmingDeletion);
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
            $product->delete();
            $this->confirmingDeletion = null;
            session()->flash('message', 'Produk berhasil dihapus.');
        }
    }

    public function setPrimaryImage($imageId)
    {
        ProductImage::where('product_id', $this->productId)->update(['is_primary' => false]);
        ProductImage::where('id', $imageId)->update(['is_primary' => true]);
        $this->existingImages = ProductImage::where('product_id', $this->productId)->get();
    }

    public function render()
    {
        $query = Product::with(['category', 'images' => function($q) {
            $q->where('is_primary', true);
        }]);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('sku', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterCategory) {
            $query->where('category_id', $this->filterCategory);
        }

        return view('livewire.product-manager', [
            'products' => $query->latest()->paginate(10),
            'categories' => Category::where('is_active', true)->get()
        ]);
    }
}
