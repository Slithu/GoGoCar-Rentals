<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_notifications')->insert([
            'user_id' => 2,
            'title' => 'New rental completed!',
            'message' => "Jan Kowalski\nCar: Toyota Yaris\nRental Date: 2024-06-24 00:00:00 --- 2024-06-26 00:00:00\nTotal Price: 300 PLN",
            'type' => 'rental',
            'status' => 'unread',
        ]);

        DB::table('user_notifications')->insert([
            'user_id' => 2,
            'title' => 'New payment completed!',
            'message' => "Jan Kowalski\nAmount: 300.00\nCurrency: PLN",
            'type' => 'payment',
            'status' => 'unread',
        ]);
    }
}
