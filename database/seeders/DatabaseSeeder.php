<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\PartnerRight;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(3)->create();
        Item::factory(30)->create();
        User::factory(10)->create();
        PartnerRight::factory(10)->create();
    }
}
