<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use App\Models\AuditLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Request;

class LogFailedLogin
{
    /**
     * Handle the event.
     */
    public function handle(Failed $event): void
    {
        AuditLog::create([
            'action' => 'Failed Login',
            // Retrieve email from the credentials array
            'user_email' => $event->credentials['email'] ?? 'unknown',
            'ip_address' => Request::ip(),
            'details' => 'Invalid credentials provided during login attempt.',
        ]);
    }
}