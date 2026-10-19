<?php

namespace Database\Seeders;

use App\Models\AidPackage;
use Illuminate\Database\Seeder;

class AidPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            ['Wheelchair Support Package',       'Health Support',    71.70, "A sturdy, foldable wheelchair for a person who cannot walk independently.", "1 foldable wheelchair\nCushion\nDelivery to the family"],
            ['Emergency Basic Food Package',     'Food',              22.53, "Essential food staples that feed a family for several weeks.", "10 kg flour\n5 L cooking oil\n5 kg rice\n2 kg sugar\nTea"],
            ['Flour for Needy Family',           'Food',              25.61, "A large bag of flour so a family can bake bread every day.", "25 kg flour\nDelivery to the family"],
            ['Emergency Essentials Aid Package', 'Food',              31.75, "A bigger food package for larger households.", "15 kg flour\n5 L cooking oil\n10 kg rice\nLentils\nSalt and tea"],
            ['Emergency Medium Essentials Pack', 'Food',              29.70, "Staples plus protein for a medium-sized family.", "10 kg rice\n5 kg lentils\n5 L cooking oil\n5 kg flour"],
            ['Winter Shelter Kit',               'Shelter',           85.00, "Keeps a family warm through the coldest months.", "4 thick blankets\nPlastic sheeting\nHeating fuel voucher"],
            ['Family Tent & Blankets',           'Shelter',          120.00, "A family tent with bedding for people who have lost their home.", "1 family tent\n6 blankets\n2 sleeping mats"],
            ['Home Cleaning Supplies Set',       'Cleaning Supplies', 14.90, "Basic supplies to keep a home clean and healthy.", "Laundry soap\nFloor cleaner\nBuckets\nBrooms"],
            ['Family Hygiene Kit',               'Hygiene Kits',      18.40, "Everyday hygiene items for the whole family.", "Soap\nToothbrushes and toothpaste\nShampoo\nTowels"],
            ['School Bag & Stationery Set',      'School Supplies',   16.25, "Everything a child needs to start the school year.", "School bag\nNotebooks\nPens and pencils\nGeometry set"],
            ['Cash Support for One Family',      'Money',             50.00, "Direct cash help so a family can cover their most urgent needs.", "Cash handed to one family\nReceipt and follow-up by our team"],
            ['Basic Medicines Pack',             'Health Support',    27.80, "Common medicines and first-aid items.", "Pain relief\nFirst-aid supplies\nVitamins\nOral rehydration salts"],
        ];

        foreach ($packages as $i => [$title, $category, $price, $description, $includes]) {
            $package = AidPackage::firstOrCreate(
                ['title' => $title],
                ['category' => $category, 'price' => $price, 'currency' => 'USD', 'sort_order' => $i]
            );

            // Fill the detail fields only if they are still empty (keeps your admin edits).
            if (! $package->description) {
                $package->update(['description' => $description, 'includes' => $includes]);
            }
        }
    }
}
