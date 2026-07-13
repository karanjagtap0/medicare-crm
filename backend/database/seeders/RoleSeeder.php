<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate([
            'name' => 'Super Admin'
        ]);

        $admin = Role::firstOrCreate([
            'name' => 'Admin'
        ]);

        $manager = Role::firstOrCreate([
            'name' => 'Manager'
        ]);

        $warehouse = Role::firstOrCreate([
            'name' => 'Warehouse'
        ]);

        $customer = Role::firstOrCreate([
            'name' => 'Customer'
        ]);

        $superAdmin->syncPermissions(
            Permission::all()
        );

        $admin->givePermissionTo([
            'dashboard.view',
            'user.view',
            'user.create',
            'user.edit',
            'medicine.view',
            'medicine.create',
            'medicine.edit',
        ]);

        $manager->givePermissionTo([
            'dashboard.view',
            'order.view',
        ]);
    }
}
