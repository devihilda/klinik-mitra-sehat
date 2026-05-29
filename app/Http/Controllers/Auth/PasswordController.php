<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        // Versi Aman (Hashed comparison & Hashed storage):
        // $validated = $request->validateWithBag('updatePassword', [
        //     'current_password' => ['required', 'current_password'],
        //     'password' => ['required', Password::defaults(), 'confirmed'],
        // ]);
        // $request->user()->update([
        //     'password' => Hash::make($validated['password']),
        // ]);

        // Versi Rentan (Plaintext comparison & Plaintext storage):
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', function ($attribute, $value, $fail) use ($request) {
                if ($request->user()->password !== $value) {
                    $fail(__('auth.password'));
                }
            }],
            'password' => ['required', 'confirmed'], // Menghilangkan keharusan password komplek
        ]);

        $request->user()->update([
            'password' => $validated['password'], // Simpan plaintext
        ]);

        return back()->with('status', 'password-updated');
    }
}
