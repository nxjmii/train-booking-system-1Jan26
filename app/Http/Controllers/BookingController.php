<?php

namespace App\Http\Controllers;

use App\Models\Train;
use App\Models\Booking;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // This is the missing function!
    public function store(Request $request)
    {
        // 1. SECURITY: Input Validation & Regex
        $validated = $request->validate([
            'train_id' => 'required|exists:trains,id',
            'seats' => 'required|integer|min:1|max:5',
            // Regex: Starts with 1 Uppercase letter, followed by 7-9 digits
            'passport_number' => ['required', 'string', 'regex:/^[A-Z][0-9]{7,9}$/'], 
        ]);

        $train = Train::findOrFail($validated['train_id']);

        // Check if there are enough seats
        if ($train->seats_available < $validated['seats']) {
            return back()->withErrors(['seats' => 'Not enough seats available on this train.']);
        }

        // 2. SECURITY: Create Booking (Injection Free)
        Booking::create([
            'user_id' => Auth::id(),
            'train_id' => $train->id,
            'seats_booked' => $validated['seats'],
            'passport_number' => $validated['passport_number'],
        ]);

        // Update the train's available seats
        $train->decrement('seats_available', $validated['seats']);

        // 3. SECURITY: Audit Log the successful booking
        AuditLog::create([
            'action' => 'Ticket Booking',
            'user_email' => Auth::user()->email,
            'ip_address' => $request->ip(),
            'details' => "Booked {$validated['seats']} seats. Passport Verified.",
        ]);

        return redirect()->route('dashboard')->with('success', 'Booking Successful! Ticket Confirmed.');
    }
}