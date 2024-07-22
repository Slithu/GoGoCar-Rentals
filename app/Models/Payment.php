<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Reservation;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'user_id',
        'stripe_charge_id',
        'amount',
        'currency',
        'type',
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
