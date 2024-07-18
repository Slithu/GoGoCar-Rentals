<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Car;

class CarAvailability extends Model
{
    use HasFactory;

    protected $table = 'car_availability';

    protected $fillable = [
        'car_id',
        'available',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
