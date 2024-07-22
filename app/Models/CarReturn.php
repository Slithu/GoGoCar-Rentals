<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;
use App\Models\User;

class CarReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'user_id',
        'return_date',
        'exterior_condition',
        'interior_condition',
        'exterior_damage_description',
        'interior_condition_description',
        'car_parts_condition',
        'penalty_amount',
        'comments',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
