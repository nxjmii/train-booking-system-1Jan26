<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookingController;
use App\Models\AuditLog;
use App\Models\Train;
use Illuminate\Support\Facades\Route;
use App\Rules\StrongPassword;  // Add this import

Route::get('/', function () {
    return view('welcome');
});

// --- DEBUG ROUTE: Test StrongPassword Rule ---
Route::get('/test-password-now', function () {
    echo "<h1>Testing StrongPassword Rule</h1>";
    
    $passwords = [
        'password123' => 'Should FAIL',
        'ABCdef123!' => 'Should PASS',
        'Test123!' => 'Should FAIL (only 2 uppercase)',
        'ABCDEF123!' => 'Should PASS',
        'Ab1!' => 'Should FAIL (too short)',
        'AAAbbb123!' => 'Should PASS',
        'AAbbcc123' => 'Should FAIL (no symbol)',
    ];
    
    foreach ($passwords as $password => $expected) {
        echo "<h3>Testing: <code>{$password}</code> - {$expected}</h3>";
        
        $rule = new StrongPassword();
        $error = '';
        
        $rule->validate('password', $password, function ($message) use (&$error) {
            $error = $message;
        });
        
        if ($error) {
            echo "<div style='color: red; padding: 10px; border: 1px solid red; margin: 5px;'>❌ FAILED: {$error}</div>";
        } else {
            echo "<div style='color: green; padding: 10px; border: 1px solid green; margin: 5px;'>✅ PASSED</div>";
        }
        echo "<hr>";
    }
});

// --- NORMAL USERS (Dashboard & Booking) ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        $trains = Train::where('seats_available', '>', 0)->get();
        return view('dashboard', compact('trains'));
    })->name('dashboard');

    // Booking Process
    Route::post('/book', [BookingController::class, 'store'])->name('book.ticket');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- ADMIN ONLY ---
Route::middleware(['auth', 'admin'])->group(function () {
    
    // Audit Logs
    Route::get('/admin/logs', function () {
        $logs = AuditLog::latest()->get();
        return view('admin.logs', compact('logs'));
    })->name('admin.logs');
});

// --- AUTH ROUTES ---
require __DIR__.'/auth.php';