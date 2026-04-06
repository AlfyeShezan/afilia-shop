<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class Search extends Component
{
    public $query = '';
    public $results = [];
    public $categories = [];
    public $showResults = false;

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->results = [];
            $this->categories = [];
            $this->showResults = false;
            return;
        }

        $this->results = Product::query()
            ->select(['id', 'category_id', 'name', 'slug', 'price', 'sale_price', 'status', 'created_at'])
            ->where('status', 'active')
            ->where(function($q) {
                $q->where('name', 'like', '%' . $this->query . '%')
                  ->orWhere('sku', 'like', '%' . $this->query . '%');
            })
            ->with([
                'primaryImage:id,product_id,image_path,is_primary,sort_order',
                'category:id,name',
            ])
            ->limit(5)
            ->get();

        $catQuery = trim(mb_strtolower($this->query));
        $this->categories = Cache::remember("search.categories.v1." . md5($catQuery), 300, function () use ($catQuery) {
            return Category::where('name', 'like', '%' . $catQuery . '%')
                ->limit(3)
                ->get(['id', 'name']);
        });

        $this->showResults = true;
    }

    public function hideResults()
    {
        $this->showResults = false;
    }

    public function render()
    {
        return view('livewire.search');
    }
}
