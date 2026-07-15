<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [

            'dashboard.view',

            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'user.list',

            'role.view',
            'role.create',
            'role.edit',
            'role.delete',

            'medicine.view',
            'medicine.create',
            'medicine.edit',
            'medicine.delete',

            'category.view',
            'category.create',
            'category.edit',
            'category.delete',

            'brand.view',
            'brand.create',
            'brand.edit',
            'brand.delete',

            'supplier.view',
            'supplier.create',
            'supplier.edit',
            'supplier.delete',

            'tax.view',
            'tax.create',
            'tax.edit',
            'tax.delete',

            'uom.view',
            'uom.create',
            'uom.edit',
            'uom.delete',

            'medicine.view',
            'medicine.create',
            'medicine.edit',
            'medicine.delete',

            'customer.view',
            'customer.create',
            'customer.edit',
            'customer.delete',

            'order.view',
            'order.create',
            'order.edit',
            'order.delete',
            'order.cancel',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }
}
