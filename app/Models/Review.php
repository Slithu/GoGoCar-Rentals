<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Car;
use App\Models\User;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'car_id',
        'reservation_id',
        'comfort_rating',
        'driving_experience_rating',
        'fuel_efficiency_rating',
        'safety_rating',
        'overall_rating',
        'comment',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
