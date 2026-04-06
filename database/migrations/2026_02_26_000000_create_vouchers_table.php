<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['percentage', 'fixed'])->default('fixed');
            $table->decimal('value', 15, 2)->default(0);
            $table->decimal('min_spend', 15, 2)->default(0)->comment('Minimum order amount to use this voucher');
            $table->decimal('max_discount', 15, 2)->nullable()->comment('Maximum discount amount for percentage type');
            $table->integer('usage_limit')->nullable()->comment('Total number of times this voucher can be used. Null = unlimited');
            $table->integer('usage_count')->default(0)->comment('Number of times this voucher has been used');
            $table->integer('per_user_limit')->default(1)->comment('Number of times a single user can use this voucher');
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
