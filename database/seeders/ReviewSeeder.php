<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reviews')->insert([
            'user_id' => 2,
            'car_id' => 2,
            'comfort_rating' => 4,
            'driving_experience_rating' => 3,
            'fuel_efficiency_rating' => 4,
            'safety_rating' => 4,
            'overall_rating' => 3.75,
            'comment' => 'Good car',
        ]);

        DB::table('reviews')->insert([
            'user_id' => 2,
            'car_id' => 1,
            'comfort_rating' => 4,
            'driving_experience_rating' => 3,
            'fuel_efficiency_rating' => 5,
            'safety_rating' => 3,
            'overall_rating' => 3.75,
            'comment' => "Very nice car, I recommend it to anyone who doesn't need a lot of space",
        ]);

        DB::table('reviews')->insert([
            'user_id' => 2,
            'car_id' => 3,
            'comfort_rating' => 3,
            'driving_experience_rating' => 4,
            'fuel_efficiency_rating' => 4,
            'safety_rating' => 4,
            'overall_rating' => 3.75,
            'comment' => "Not bad car in good price",
        ]);
    }
}
