<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payments')->insert([
            'reservation_id' => 1,
            'user_id' => 2,
            'stripe_charge_id' => 'ch_3PeEKSFu2tXLfJss0d3oyN7c',
            'amount' => '300',
            'currency' => 'PLN',
            'type' => 'rental',
        ]);

        DB::table('payments')->insert([
            'reservation_id' => 2,
            'user_id' => 2,
            'stripe_charge_id' => 'ch_3PfIMcFu2tXLfJss1xHMmUb9',
            'amount' => '390',
            'currency' => 'PLN',
            'type' => 'rental',
        ]);

        DB::table('payments')->insert([
            'reservation_id' => 3,
            'user_id' => 2,
            'stripe_charge_id' => 'ch_3PiuwgFu2tXLfJss1WF8VpjJ',
            'amount' => '135',
            'currency' => 'PLN',
            'type' => 'rental',
        ]);

        DB::table('payments')->insert([
            'reservation_id' => 2,
            'user_id' => 2,
            'stripe_charge_id' => 'ch_3PhuwgFu2tXfgsfss1WF8VjJ',
            'amount' => '50',
            'currency' => 'PLN',
            'type' => 'penalty',
        ]);

        DB::table('payments')->insert([
            'reservation_id' => 3,
            'user_id' => 2,
            'stripe_charge_id' => 'ch_3PyuwgFu2fgdgsfss1WF8VjJ',
            'amount' => '50',
            'currency' => 'PLN',
            'type' => 'penalty',
        ]);
    }
}
