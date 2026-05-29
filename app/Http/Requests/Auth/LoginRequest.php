<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Kebutuhan Praktikum: Menggunakan validasi lemah agar bisa diuji (Vulnerability 2: Weak Validation)
            // 'email' => ['required', 'string', 'email'],
            'email' => ['required'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        // Kebutuhan Praktikum: Menonaktifkan rate limiting untuk simulasi Brute Force
        // $this->ensureIsNotRateLimited();

        // Kebutuhan Praktikum: Autentikasi plaintext password manual
        $user = User::where('email', $this->input('email'))->first();

        // Versi Aman (bawaan Laravel Breeze):
        // if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {

        // Versi Rentan (Plaintext Password Comparison):
        if (! $user || $user->password !== $this->input('password')) {
            // Kebutuhan Praktikum: Menonaktifkan pencatatan percobaan gagal pada RateLimiter
            // RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));

        // Kebutuhan Praktikum: Menonaktifkan pembersihan RateLimiter
        // RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
