<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@orphan.org'],
            [
                'name'     => 'Admin',
                'password' => Hash::make('admin@786'),
                 'is_admin' => true, // add if your app uses it
            ]
        );
    }
}