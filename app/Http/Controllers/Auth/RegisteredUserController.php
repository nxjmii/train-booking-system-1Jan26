<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\StrongPassword; // Add this import
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
  {
    // DEBUG: Dump the request data
    \Log::info('Registration Request Data:', $request->all());
    
    // Manually test the password
    $password = $request->password;
    \Log::info('Password Analysis:', [
        'password' => $password,
        'length' => strlen($password),
        'uppercase_count' => preg_match_all('/[A-Z]/', $password),
        'has_number' => preg_match('/\d/', $password),
        'has_symbol' => preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $password),
    ]);

    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', new StrongPassword],
    ]);

    \Log::info('Validation passed for registration');

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
   }
}