<?php

namespace Database\Seeders;

use App\Models\AidPackage;
use App\Models\NeedyPerson;
use Illuminate\Database\Seeder;

class NeedyPersonSeeder extends Seeder
{
    public function run(): void
    {
        $people = [
            ['Maliha Khan Agha',      'Women',    'Kunduz'],
            ['Anisa Gul Mohammad',    'Women',    'Kunduz'],
            ['Feroza Ghulam Sakhi',   'Women',    'Kunduz'],
            ['Latifa Ghulam Dastger', 'Women',    'Kunduz'],
            ['Taj Khanom Mohammad',   'Women',    'Kunduz'],
            ['Ahmad Zia',             'Orphan',   'Kabul'],
            ['Sara Noori',            'Children', 'Herat'],
            ['Abdul Rahim',           'Elderly',  'Mazar-i-Sharif'],
            ['Zarghona Bibi',         'Widow',    'Kabul'],
            ['Hamid Karimi',          'Disabled', 'Herat'],
        ];

        $packageIds = AidPackage::pluck('id')->all();

        foreach ($people as $i => [$name, $category, $city]) {
            $person = NeedyPerson::firstOrCreate(
                ['name' => $name],
                ['category' => $category, 'city' => $city, 'sort_order' => $i]
            );

            // Placeholder text so the detail page is not empty. Replace it from the admin panel.
            if (! $person->story) {
                $person->update([
                    'age' => 30 + $i,
                    'family_size' => 3 + ($i % 5),
                    'story' => "SAMPLE TEXT: replace this with the real story from the admin panel.\n\n"
                        . "Write who this person is, what difficulty they are facing, and how your team verified their situation.\n\n"
                        . "Explain what the support will be used for, so donors know exactly how they are helping.",
                ]);
            }

            // Give each person 2-3 wishlist items (only on first creation).
            if ($person->wasRecentlyCreated && $packageIds) {
                $pick = array_rand(array_flip($packageIds), min(3, count($packageIds)));
                $person->aidPackages()->sync((array) $pick);
            }
        }
    }
}
