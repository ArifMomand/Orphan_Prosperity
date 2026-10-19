<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            User::factory()->make(['name' => 'Test User', 'email' => 'test@example.com'])->getAttributes()
        );

        // Aid packages first, because people reference them in their wishlist.
        $this->call([
            AidPackageSeeder::class,
            NeedyPersonSeeder::class,
        ]);
    }
}
