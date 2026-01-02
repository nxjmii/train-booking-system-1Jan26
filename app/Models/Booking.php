<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // FIX: Allow these fields to be saved
    protected $fillable = [
        'user_id',
        'train_id',
        'seats_booked',
        'passport_number',
    ];
}