<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $admin = \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@afilia.shop',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('super-admin');

        $staff = \App\Models\User::factory()->create([
            'name' => 'Test Staff',
            'email' => 'staff@afilia.shop',
            'password' => bcrypt('password'),
        ]);
        $staff->assignRole('staff');

        $customer = \App\Models\User::factory()->create([
            'name' => 'Test Customer',
            'email' => 'customer@afilia.shop',
            'password' => bcrypt('password'),
        ]);
        $customer->assignRole('customer');

        // Core Data Seeding
        $electronics = \App\Models\Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'is_active' => true,
        ]);

        $laptops = \App\Models\Category::create([
            'parent_id' => $electronics->id,
            'name' => 'Laptops',
            'slug' => 'laptops',
            'is_active' => true,
        ]);

        \App\Models\Product::create([
            'category_id' => $laptops->id,
            'name' => 'Enterprise Pro Laptop',
            'slug' => 'enterprise-pro-laptop',
            'price' => 1299.99,
            'sku' => 'LAP-ENT-001',
            'stock' => 50,
            'status' => 'active',
        ]);
    }
}
