<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'amount',
        'bank_info',
        'status',
        'reference_number',
        'admin_notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'bank_info' => 'array',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
