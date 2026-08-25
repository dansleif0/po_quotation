<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. User Manager KDA
        User::updateOrCreate(
            ['username' => 'managerkda'],
            [
                'name' => 'Manager KDA',
                'email' => 'managerkda@tasniem.com',
                'password' => Hash::make('123456'),
            ]
        );

        // 2. User Ardian
        User::updateOrCreate(
            ['username' => 'ardian'],
            [
                'name' => 'Ardian',
                'email' => 'ardian@tasniem.com',
                'password' => Hash::make('123456'),
            ]
        );
    }
}