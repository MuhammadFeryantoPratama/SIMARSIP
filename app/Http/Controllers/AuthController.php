<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['nullable'],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->orWhere('username', $validated['email'])
            ->first();

        if ($user === null || ! Auth::attempt([
            'email' => $user->email,
            'password' => $validated['password'],
        ], $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email/username atau password tidak sesuai.'])
                ->with('error', 'Email/username atau password tidak sesuai.')
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()
            ->intended(route($this->dashboardRoute($user)))
            ->with('success', 'Login berhasil. Selamat datang kembali, '.$user->name.'.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function dashboardRoute(User $user): string
    {
        $role = Str::lower($user->role?->name ?? 'staff');

        return match (true) {
            Str::contains($role, 'admin') => 'admin.dashboard',
            Str::contains($role, 'pimpin') || Str::contains($role, 'validator') => 'pimpinan.dashboard',
            Str::contains($role, 'audit') => 'auditor.dashboard',
            default => 'staff.dashboard',
        };
    }
}
