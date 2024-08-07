<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admin_notifications')->insert([
            'user_id' => 2,
            'title' => 'New rental completed!',
            'message' => "Rental ID: 1\nUser ID: 2\nUser: Jan Kowalski\nCar ID: 2\nCar: Toyota Yaris\nRental Date: 2024-06-24 00:00:00 --- 2024-06-26 00:00:00\nTotal Price: 300 PLN",
            'type' => 'rental',
            'status' => 'unread',
        ]);

        DB::table('admin_notifications')->insert([
            'user_id' => 2,
            'title' => 'New payment completed!',
            'message' => "Payment ID: 1\nRental ID: 1\nUser ID: 2\nUser: Jan Kowalski\nAmount: 300.00\nCurrency: PLN",
            'type' => 'payment',
            'status' => 'unread',
        ]);
    }
}
