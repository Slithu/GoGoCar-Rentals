<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use App\Models\CarAvailability;

class Car extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand',
        'model',
        'car_body',
        'engine_type',
        'transmission',
        'engine_power',
        'seats',
        'doors',
        'suitcases',
        'price',
        'description',
        'image_path'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function availability()
    {
        return $this->hasOne(CarAvailability::class);
    }
}
