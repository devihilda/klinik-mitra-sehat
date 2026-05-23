<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (session()->has('user_id')) {
            return $this->redirectToDashboard(session('role'));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        // KERENTANAN: SQL Injection (SQLi)
        // String input langsung dimasukkan ke kueri SQL tanpa binding atau sanitasi.
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        
        try {
            $users = DB::select($query);
        } catch (\Exception $e) {
            // Mengembalikan error SQL agar pengetes/ZAP dapat mendeteksi Error-based SQL Injection
            return back()->with('error', 'Database Error: ' . $e->getMessage())->withInput();
        }

        if (count($users) > 0) {
            // Ambil user pertama hasil kueri
            $user = $users[0];

            // Set session secara manual
            session([
                'user_id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'role' => $user->role,
            ]);

            return $this->redirectToDashboard($user->role);
        }

        return back()->with('error', 'Username atau password salah!')->withInput();
    }

    public function logout()
    {
        session()->forget(['user_id', 'username', 'name', 'role']);
        return redirect('/login')->with('success', 'Berhasil logout.');
    }

    private function redirectToDashboard($role)
    {
        if ($role === 'petugas') {
            return redirect('/dashboard-petugas');
        }
        return redirect('/dashboard-pasien');
    }
}
