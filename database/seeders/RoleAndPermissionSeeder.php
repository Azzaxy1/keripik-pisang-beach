<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndPermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles (only if they don't exist)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $ownerRole = Role::firstOrCreate(['name' => 'owner']); // Role pemilik

        // Create permissions (only if they don't exist)
        $permissions = [
            'manage users',
            'manage products',
            'manage categories',
            'manage orders',
            'manage payments',
            'view dashboard',
            'manage settings',
            'view reports',
            'manage inventory'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles (only if not already assigned)
        if (!$adminRole->hasPermissionTo('manage users')) {
            $adminRole->givePermissionTo(['manage users', 'manage products', 'manage categories', 'manage orders', 'manage payments', 'view dashboard']);
        }

        if (!$ownerRole->hasAnyPermission(Permission::all())) {
            $ownerRole->givePermissionTo(Permission::all()); // Owner has all permissions
        }

        // Create admin users (only if they don't exist)
        if (!User::where('email', 'admin@keripikpisang.com')->exists()) {
            $admin1 = User::create([
                'name' => 'Admin Keripik',
                'email' => 'admin@keripikpisang.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true
            ]);
            $admin1->assignRole('admin');
        }

        // Create owner user (only if they don't exist)
        if (!User::where('email', 'owner@keripikpisang.com')->exists()) {
            $owner = User::create([
                'name' => 'Pemilik Keripik Pisang Cinangka',
                'email' => 'owner@keripikpisang.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true
            ]);
            $owner->assignRole('owner');
        }

        // Create sample customers (only if they don't exist)
        if (!User::where('email', 'customer@example.com')->exists()) {
            $customer1 = User::create([
                'name' => 'Pelanggan Keripik',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true
            ]);
            $customer1->assignRole('customer');
        }
    }
}
