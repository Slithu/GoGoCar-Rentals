<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('car_returns')->insert([
            'reservation_id' => 1,
            'user_id' => 2,
            'return_date' => '2024-06-26 00:00:00',
            'exterior_condition' => "Minor scratches or dents that are difficult to notice",
            'interior_condition' => "Minor signs of use, no damage to the upholstery or equipment",
            'exterior_damage_description' => '',
            'interior_damage_description' => '',
            'car_parts_condition' => '',
            'penalty_amount' => 0.00,
            'comment' => 'Ok',
            'penalty_paid' => 0
        ]);

        DB::table('car_returns')->insert([
            'reservation_id' => 2,
            'user_id' => 2,
            'return_date' => '2024-06-26 21:00:00',
            'exterior_condition' => "Minor scratches or dents that are difficult to notice",
            'interior_condition' => "The interior is in perfect condition, no signs of use",
            'exterior_damage_description' => '',
            'interior_damage_description' => '',
            'car_parts_condition' => '',
            'penalty_amount' => 50.00,
            'comment' => 'The return of the car was delayed by an hour',
            'penalty_paid' => 1
        ]);

        DB::table('car_returns')->insert([
            'reservation_id' => 3,
            'user_id' => 2,
            'return_date' => '2024-07-01 07:00:00',
            'exterior_condition' => "Minor scratches or dents that are difficult to notice",
            'interior_condition' => "The interior is in perfect condition, no signs of use",
            'exterior_damage_description' => '',
            'interior_damage_description' => '',
            'car_parts_condition' => '',
            'penalty_amount' => 50.00,
            'comment' => 'Return Date an hour past Rental Date',
            'penalty_paid' => 1
        ]);
    }
}
