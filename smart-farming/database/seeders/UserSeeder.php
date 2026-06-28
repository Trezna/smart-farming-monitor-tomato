<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@smartfarming.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
        ]);

        User::create([
            'name'     => 'Viewer',
            'email'    => 'viewer@smartfarming.com',
            'password' => Hash::make('viewer123'),
            'role'     => 'viewer',
        ]);
    }
}
