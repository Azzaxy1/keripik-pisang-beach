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
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $customerRole = Role::create(['name' => 'customer']);

        // Create permissions
        $permissions = [
            'manage users',
            'manage products',
            'manage categories',
            'manage orders',
            'manage payments',
            'view dashboard',
            'manage settings'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign all permissions to admin role
        $adminRole->givePermissionTo(Permission::all());

        // Create admin users
        $admin1 = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => true
        ]);
        $admin1->assignRole('admin');

        $admin2 = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@ecommerce.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => true
        ]);
        $admin2->assignRole('admin');

        // Create sample customers
        $customer1 = User::create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => true
        ]);
        $customer1->assignRole('customer');

        $customer2 = User::create([
            'name' => 'John Doe',
            'email' => 'customer@ecommerce.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'status' => true
        ]);
        $customer2->assignRole('customer');
    }
}
