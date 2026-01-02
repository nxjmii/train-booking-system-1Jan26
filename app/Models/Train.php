<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    // THIS IS THE FIX: We are telling Laravel "It is safe to save these fields"
    protected $fillable = [
        'name',
        'destination',
        'departure_time',
        'seats_available',
    ];
}