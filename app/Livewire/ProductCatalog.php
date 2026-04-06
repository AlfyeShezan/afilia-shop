<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ProductCatalog extends Component
{
    use WithPagination, \App\Traits\HandlesCart, \App\Traits\HandlesWishlist;

    #[Url(history: true)]
    public $search = '';
    
    #[Url(history: true)]
    public $selectedCategory = '';
    
    #[Url(history: true)]
    public $minPrice = '';
    
    #[Url(history: true)]
    public $maxPrice = '';
    
    #[Url(history: true)]
    public $sortBy = 'newest';

    public $perPage = 12;
    public $suggestions = [];
    public $wishlistIds = [];

    public function mount()
    {
        if (Auth::check()) {
            $this->wishlistIds = Auth::user()
                ->wishlists()
                ->pluck('product_id')
                ->map(fn ($id) => (int) $id)
                ->toArray();
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
        if (strlen($this->search) > 2) {
            $this->suggestions = Product::where('status', 'active')
                ->where('name', 'like', '%' . $this->search . '%')
                ->limit(5)
                ->get(['id', 'name', 'slug'])
                ->toArray();
        } else {
            $this->suggestions = [];
        }
    }

    public function updatedSelectedCategory() { $this->resetPage(); }
    public function updatedMinPrice() { $this->resetPage(); }
    public function updatedMaxPrice() { $this->resetPage(); }
    public function updatedSortBy() { $this->resetPage(); }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    public function selectSuggestion($slug)
    {
        return redirect()->route('product.detail', $slug);
    }

    protected function getActiveCategories()
    {
        return Cache::remember('catalog.active_categories_tree.v1', 3600, function () {
            return Category::where('is_active', true)
                ->whereNull('parent_id')
                ->with('children')
                ->get();
        });
    }

    public function render()
    {
        $query = Product::query()
            ->select([
                'id',
                'category_id',
                'name',
                'slug',
                'price',
                'sale_price',
                'status',
                'created_at',
            ])
            ->where('status', 'active')
            ->with([
                'category:id,name',
                'primaryImage:id,product_id,image_path,is_primary,sort_order',
            ]);

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->selectedCategory) {
            $query->where('category_id', $this->selectedCategory);
        }

        if ($this->minPrice !== '') {
            $query->where('price', '>=', $this->minPrice);
        }

        if ($this->maxPrice !== '') {
            $query->where('price', '<=', $this->maxPrice);
        }

        switch ($this->sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'sales':
                $query->withSum('orderItems', 'quantity')
                    ->orderByDesc('order_items_sum_quantity')
                    ->orderByDesc('created_at');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return view('livewire.product-catalog', [
            'products' => $query->paginate($this->perPage),
            'categories' => $this->getActiveCategories(),
        ]);
    }
}
