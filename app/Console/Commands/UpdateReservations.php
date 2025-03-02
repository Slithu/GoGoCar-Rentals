<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;

class UpdateReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel outdated pending reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $affected = Reservation::where('status', 'pending')
            ->where('start_date', '<', now()->addHours(2))
            ->update(['status' => 'cancelled']);

        $this->info("$affected records updated.");
    }
}
