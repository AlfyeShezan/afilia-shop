<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Voucher extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'type', 'value',
        'min_spend', 'max_discount', 'usage_limit', 'usage_count',
        'per_user_limit', 'is_active', 'starts_at', 'expires_at',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Scope to get only currently active and valid vouchers.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereRaw('usage_count < usage_limit');
            });
    }

    /**
     * Check if the voucher is currently valid.
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->isFuture()) return false;
        if ($this->expires_at && $this->expires_at->isPast()) return false;
        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) return false;
        return true;
    }

    /**
     * Calculate the discount amount for a given order subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($subtotal < $this->min_spend) return 0;

        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
            if ($this->max_discount) {
                $discount = min($discount, $this->max_discount);
            }
            return $discount;
        }

        // fixed
        return min($this->value, $subtotal);
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute(): string
    {
        if (!$this->is_active) return 'Nonaktif';
        if ($this->starts_at && $this->starts_at->isFuture()) return 'Belum Mulai';
        if ($this->expires_at && $this->expires_at->isPast()) return 'Kedaluwarsa';
        if ($this->usage_limit !== null && $this->usage_count >= $this->usage_limit) return 'Habis';
        return 'Aktif';
    }
}
