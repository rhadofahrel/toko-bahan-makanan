<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ── LOGIN ────────────────────────────────────────────────────────────────

    public function showLogin()
    {
        if (session('user_id')) {
            return $this->redirectByRole(session('user_role'));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ], [
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $email    = trim($request->email);
        $password = $request->password; // Password should usually not be trimmed, but let's check

        $user = User::findByEmail($email);

        if (!$user || !Hash::check($password, $user['password'])) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'Email atau password salah.']);
        }

        // Simpan sesi
        $request->session()->put('user_id',   $user['id']);
        $request->session()->put('user_name',  $user['name']);
        $request->session()->put('user_email', $user['email']);
        $request->session()->put('user_role',  $user['role']);

        if ($request->remember) {
            $request->session()->put('remember', true);
        }

        return $this->redirectByRole($user['role']);
    }

    // ── REGISTER ─────────────────────────────────────────────────────────────

    public function showRegister()
    {
        if (session('user_id')) {
            return $this->redirectByRole(session('user_role'));
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|min:3|max:100',
            'email'                 => 'required|email|max:100',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ], [
            'name.required'                  => 'Nama lengkap wajib diisi.',
            'name.min'                       => 'Nama minimal 3 karakter.',
            'email.required'                 => 'Email wajib diisi.',
            'email.email'                    => 'Format email tidak valid.',
            'password.required'              => 'Password wajib diisi.',
            'password.min'                   => 'Password minimal 6 karakter.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same'     => 'Konfirmasi password tidak cocok.',
        ]);

        // Cek email unik
        if (User::emailExists($request->email)) {
            return redirect()->route('login')
                ->withInput($request->only('email'))
                ->with('error', 'Email sudah terdaftar. Silakan login terlebih dahulu.');
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
            'role'     => 'customer',
        ]);

        // Redirect ke login (tidak auto-login)
        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login untuk masuk ke akun Anda.');
    }

    // ── LOGOUT ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        $request->session()->forget(['user_id', 'user_name', 'user_email', 'user_role', 'remember']);
        $request->session()->flush();

        return redirect()->route('login')
            ->with('success', 'Anda berhasil logout.');
    }

    // ── HELPER ───────────────────────────────────────────────────────────────

    private function redirectByRole(string $role)
    {
        if ($role === 'admin') {
            return redirect()->route('admin.home');
        }
        return redirect()->route('home');
    }
}
