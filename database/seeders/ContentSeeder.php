<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\ProjectLocation;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $posts = [
            ['title' => 'Where to Give Now', 'excerpt' => 'Dolor donec eget morbi nisl. Eu et enim orci nisl vel auctor odio a.'],
            ['title' => 'Popular Charities', 'excerpt' => 'Dolor donec eget morbi nisl. Eu et enim orci nisl vel auctor odio a.'],
            ['title' => 'Childcare Crisis', 'excerpt' => 'Dolor donec eget morbi nisl. Eu et enim orci nisl vel auctor odio a.'],
        ];

        foreach ($posts as $post) {
            BlogPost::firstOrCreate(['title' => $post['title']], $post);
        }

        $locations = [
            ['name' => 'Kabul', 'description' => 'Our headquarters and largest concentration of partner homes and schools.', 'x_percent' => 58, 'y_percent' => 42],
            ['name' => 'Herat', 'description' => 'Western region programs — clean water and education initiatives.', 'x_percent' => 14, 'y_percent' => 40],
            ['name' => 'Mazar-i-Sharif', 'description' => 'Northern operations focused on healthcare and food security.', 'x_percent' => 50, 'y_percent' => 16],
        ];

        foreach ($locations as $location) {
            ProjectLocation::firstOrCreate(['name' => $location['name']], $location);
        }
    }
}