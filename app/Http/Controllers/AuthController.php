<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LogAktivitas;
use App\Traits\LogAktivitasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use LogAktivitasTrait;

    public function showLogin()
    {
        if (Auth::check()) return $this->redirectByRole();
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->status !== 'aktif') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi admin.'
                ])->onlyInput('email');
            }

            // LOG LOGIN
            $this->log('Login sebagai ' . ucfirst(Auth::user()->role) . ' — ' . Auth::user()->name);

            return $this->redirectByRole();
        }

        return back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        // LOG LOGOUT (sebelum Auth::logout)
        if (Auth::check()) {
            $this->log('Logout — ' . Auth::user()->name);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegister()
    {
        if (Auth::check()) return $this->redirectByRole();
        $adminExists = User::where('role', 'admin')->exists();
        if ($adminExists) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Registrasi tidak tersedia. Hubungi admin untuk menambahkan akun.']);
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $adminExists = User::where('role', 'admin')->exists();
        if ($adminExists) {
            return redirect()->route('login')
                ->withErrors(['email' => 'Registrasi tidak tersedia. Hubungi admin.']);
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin',
            'status'   => 'aktif',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        // LOG REGISTER
        $this->log('Registrasi akun admin baru — ' . $user->name);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Akun admin berhasil dibuat!');
    }

    private function redirectByRole()
    {
        return match (Auth::user()->role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'owner'  => redirect()->route('owner.dashboard'),
            default  => redirect()->route('petugas.dashboard'),
        };
    }
}
