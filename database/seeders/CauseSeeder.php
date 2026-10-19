<?php

namespace Database\Seeders;

use App\Models\Cause;
use Illuminate\Database\Seeder;

class CauseSeeder extends Seeder
{
    public function run(): void
    {
        $causes = [
            [
                'title' => 'Clean Water for All', 'category' => 'Water', 'location' => 'Kandahar Province',
                'goal' => 3000, 'raised' => 2000, 'donors' => 14,
                'summary' => 'Drilling and repairing wells so families no longer walk hours for clean water.',
                'story' => "In much of Kandahar Province, the nearest safe water source can be an hour's walk each way. This fund drills new wells and repairs broken hand pumps in villages that have gone years without reliable access.\n\nEach well typically serves 40–60 families and includes a year of maintenance training.",
            ],
            [
                'title' => 'Improve Education', 'category' => 'Education', 'location' => 'Kabul & Herat',
                'goal' => 15000, 'raised' => 9000, 'donors' => 25,
                'summary' => 'Books, uniforms, and qualified teachers for classrooms that would otherwise sit empty.',
                'story' => "Many partner schools have students and a building, but not the basics. This fund covers classroom supplies, uniform stipends, and teacher salaries across our Kabul and Herat partner schools.",
            ],
            [
                'title' => 'End Hunger', 'category' => 'Community', 'location' => 'Nationwide',
                'goal' => 200000, 'raised' => 50000, 'donors' => 6,
                'summary' => 'Daily meals and take-home food packages for families facing food insecurity.',
                'story' => "This is our largest and most direct program: daily hot meals at partner sites, plus monthly take-home food packages for families who can't reach a distribution point regularly.",
            ],
            [
                'title' => 'Reduce Homelessness', 'category' => 'Shelter', 'location' => 'Kabul',
                'goal' => 80000, 'raised' => 40000, 'donors' => 12,
                'summary' => 'Emergency shelter and transitional housing support for displaced families.',
                'story' => "Displacement is one of the fastest ways a family ends up without stable housing. This fund covers emergency shelter stays and the first months of rent once a family finds transitional housing.",
            ],
            [
                'title' => 'Immigration & Refugees', 'category' => 'Emergency', 'location' => 'Border Regions',
                'goal' => 220000, 'raised' => 90000, 'donors' => 24,
                'summary' => 'Emergency supplies and legal aid support for refugee and returnee families.',
                'story' => "Families crossing back into the country, often with nothing, need immediate essentials and help navigating registration and legal paperwork.",
            ],
            [
                'title' => 'Climate Change Mitigation', 'category' => 'Community', 'location' => 'Rural Provinces',
                'goal' => 100000, 'raised' => 30000, 'donors' => 8,
                'summary' => 'Drought-resistant crops and flood defenses for farming communities.',
                'story' => "Rural communities are seeing longer droughts and more violent flash floods. This fund supports drought-resistant seed distribution and small-scale flood barriers.",
            ],
            [
                'title' => 'Medical Checkups for Children', 'category' => 'Health', 'location' => 'Partner Homes',
                'goal' => 50000, 'raised' => 22000, 'donors' => 18,
                'summary' => 'Routine health screenings and basic treatment for children in our care homes.',
                'story' => "Children in our partner homes get a full checkup twice a year, plus treatment for anything caught early.",
            ],
            [
                'title' => 'School Supplies Drive', 'category' => 'Education', 'location' => 'Nationwide',
                'goal' => 10000, 'raised' => 7000, 'donors' => 30,
                'summary' => 'Backpacks, notebooks, and basic supplies so no child starts the term empty-handed.',
                'story' => "At the start of each school term, we put together supply kits for kids whose families can't cover it.",
            ],
        ];

        foreach ($causes as $cause) {
            Cause::firstOrCreate(['title' => $cause['title']], $cause);
        }
    }
}
