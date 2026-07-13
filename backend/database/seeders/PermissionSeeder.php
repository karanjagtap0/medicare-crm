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

            'order.view',
            'order.create',
            'order.edit',
            'order.delete',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }
}
