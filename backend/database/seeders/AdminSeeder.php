<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@medicare.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('Password@123'),
            ]
        );

        $user->assignRole('Super Admin');
    }
}
