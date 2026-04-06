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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->integer('points')->default(0)->after('phone');
            $table->string('membership_level')->default('Silver')->after('points'); // Silver, Gold, Platinum
            $table->string('avatar')->nullable()->after('membership_level');
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'points', 'membership_level', 'avatar', 'is_active']);
        });
    }
};
