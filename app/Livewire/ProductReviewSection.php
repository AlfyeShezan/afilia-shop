<?php

namespace App\Livewire;

use Livewire\Component;

use Livewire\WithFileUploads;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductReviewSection extends Component
{
    use WithFileUploads;

    public Product $product;
    public $rating = 5;
    public $comment = '';
    public $images = [];
    public $canReview = false;
    public $hasReviewed = false;

    protected $rules = [
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'required|string|min:10',
        'images.*' => 'nullable|image|max:2048', // 2MB Max
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->checkReviewStatus();
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function checkReviewStatus()
    {
        if (!Auth::check()) {
            $this->canReview = false;
            return;
        }

        // 1. Check if already reviewed
        $this->hasReviewed = ProductReview::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->exists();

        if ($this->hasReviewed) {
            $this->canReview = false;
            return;
        }

        // 2. Check if purchased (Status paid, processing, shipped, or completed)
        $this->canReview = OrderItem::whereHas('order', function($q) {
                $q->where('user_id', Auth::id())
                  ->where(function($query) {
                      $query->whereIn('status', ['paid', 'shipped', 'completed', 'processing'])
                            ->orWhere('payment_status', 'paid');
                  });
            })
            ->where('product_id', $this->product->id)
            ->exists();
    }

    public function saveReview()
    {
        if (!$this->canReview) return;

        $this->validate();

        $imagePaths = [];
        if ($this->images) {
            foreach ($this->images as $image) {
                $imagePaths[] = $image->store('reviews', 'public');
            }
        }

        ProductReview::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'images' => $imagePaths,
        ]);

        $this->comment = '';
        $this->rating = 5;
        $this->images = [];
        $this->checkReviewStatus();

        $this->dispatch('notify', [
            'message' => 'Terima kasih atas ulasan Anda!',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        $reviews = ProductReview::where('product_id', $this->product->id)
            ->with('user')
            ->latest()
            ->get();

        return view('livewire.product-review-section', [
            'reviews' => $reviews
        ]);
    }
}
