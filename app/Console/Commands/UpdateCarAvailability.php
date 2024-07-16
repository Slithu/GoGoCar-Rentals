<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Car;
use App\Models\Reservation;
use App\Models\CarAvailability;

class UpdateCarAvailability extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:car-availability';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update car availability based on reservations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cars = Car::all();

        foreach ($cars as $car) {
            $isBooked = Reservation::where('car_id', $car->id)
                ->where('end_date', '>', now())
                ->exists();

            CarAvailability::updateOrCreate(
                ['car_id' => $car->id],
                ['available' => !$isBooked]
            );
        }

        $this->info('Car availability updated successfully.');
    }
}
