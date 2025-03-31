<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('reservations')->insert([
            'user_id' => 2,
            'car_id' => 2,
            'start_date' => '2024-06-24 00:00:00',
            'end_date' => '2024-06-26 00:00:00',
            'total_price' => 300.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 2,
            'car_id' => 1,
            'start_date' => '2024-06-24 18:30:00',
            'end_date' => '2024-06-26 20:00:00',
            'total_price' => 390.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 2,
            'car_id' => 3,
            'start_date' => '2024-07-01 05:00:00',
            'end_date' => '2024-07-01 06:00:00',
            'total_price' => 135.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 3,
            'car_id' => 2,
            'start_date' => '2024-07-03 15:00:00',
            'end_date' => '2024-07-07 18:00:00',
            'total_price' => 750.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 4,
            'car_id' => 20,
            'start_date' => '2024-07-04 12:00:00',
            'end_date' => '2024-07-06 20:00:00',
            'total_price' => 690.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 5,
            'car_id' => 11,
            'start_date' => '2024-07-03 10:00:00',
            'end_date' => '2024-07-06 12:00:00',
            'total_price' => 680.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 6,
            'car_id' => 16,
            'start_date' => '2024-07-04 11:30:00',
            'end_date' => '2024-07-06 17:00:00',
            'total_price' => 570.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 2,
            'car_id' => 2,
            'start_date' => '2024-07-08 12:00:00',
            'end_date' => '2024-07-10 13:00:00',
            'total_price' => 450.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 2,
            'car_id' => 1,
            'start_date' => '2024-08-07 11:00:00',
            'end_date' => '2024-08-10 13:00:00',
            'total_price' => 520.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 11,
            'car_id' => 17,
            'start_date' => '2024-11-10 15:00:00',
            'end_date' => '2024-11-13 12:00:00',
            'total_price' => 390.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 11,
            'car_id' => 12,
            'start_date' => '2024-11-12 12:00:00',
            'end_date' => '2024-11-14 12:00:00',
            'total_price' => 350.00,
            'status' => 'confirmed',
        ]);

        DB::table('reservations')->insert([
            'user_id' => 11,
            'car_id' => 22,
            'start_date' => '2024-11-17 17:40:00',
            'end_date' => '2024-11-20 12:00:00',
            'total_price' => 750.00,
            'status' => 'confirmed',
        ]);
    }
}
