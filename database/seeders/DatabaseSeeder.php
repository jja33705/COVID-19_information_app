<?php

namespace Database\Seeders;

use App\Models\Hashtag;
use App\Models\Review;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Hashtag::factory()->has(Review::factory()->count(rand(0, 10)))->count(20)->create();
    }
}
