<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Safe Version (Strict Validation)
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone' => ['required', 'string', 'max:20'],
            'gender' => ['required', 'in:laki-laki,perempuan'],
            'birth_date' => ['required', 'date'],
            'address' => ['required', 'string'],
        ]);

        // Wrap registration in a database transaction to prevent partial/orphaned records
        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'], // Encrypted automatically by casts on User model
                'role' => 'pasien', // Force role to 'pasien' to prevent privilege escalation
            ]);

            // Create patient details by explicitly mapping fields to prevent Mass Assignment vulnerability
            $user->patient()->create([
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'birth_date' => $validated['birth_date'],
                'address' => $validated['address'],
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
