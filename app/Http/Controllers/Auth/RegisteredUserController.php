<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        // Versi Aman (Validasi Ketat):
        // $request->validate([
        //     'name' => ['required', 'string', 'max:255'],
        //     'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        //     'password' => ['required', 'confirmed', Rules\Password::defaults()],
        //     'phone' => ['required', 'string', 'max:20'],
        //     'gender' => ['required', 'in:laki-laki,perempuan'],
        //     'birth_date' => ['required', 'date'],
        //     'address' => ['required', 'string', 'max:255'],
        // ]);

        // Versi Rentan (Weak Validation):
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'gender' => 'required',
            'birth_date' => 'required',
            'address' => 'required',
        ]);

        // Versi Aman (Password di-hash):
        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);

        // Versi Rentan (Plaintext Password & Mass Assignment):
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Menyimpan password secara plaintext
            'role' => 'pasien',
        ]);

        // Menggunakan celah Mass Assignment pada Patient dengan passing direct request
        $user->patient()->create($request->all());

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
