<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    // FIX: Allow these fields to be saved
    protected $fillable = [
        'action',
        'user_email',
        'ip_address',
        'details',
    ];
}