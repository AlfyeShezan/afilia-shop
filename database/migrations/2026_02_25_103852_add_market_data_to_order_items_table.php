<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'vendor_id')) {
                $table->foreignId('vendor_id')->nullable()->after('product_id')->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('order_items', 'commission_amount')) {
                $table->decimal('commission_amount', 15, 2)->default(0)->after('price');
            }
            if (!Schema::hasColumn('order_items', 'net_amount')) {
                $table->decimal('net_amount', 15, 2)->default(0)->after('commission_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            //
        });
    }
};
