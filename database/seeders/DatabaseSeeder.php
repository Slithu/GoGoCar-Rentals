<?php

namespace Database\Seeders;

use Database\Seeders\UserSeeder;
use Database\Seeders\CarSeeder;
use Database\Seeders\ReservationSeeder;
use Database\Seeders\ReviewSeeder;
use Database\Seeders\PaymentSeeder;
use Database\Seeders\CarReturnSeeder;
use Database\Seeders\AdminNotificationSeeder;
use Database\Seeders\UserNotificationSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            UserSeeder::class,
            CarSeeder::class,
            ReservationSeeder::class,
            ReviewSeeder::class,
            PaymentSeeder::class,
            CarReturnSeeder::class,
            AdminNotificationSeeder::class,
            UserNotificationSeeder::class,
        ]);
    }
}
