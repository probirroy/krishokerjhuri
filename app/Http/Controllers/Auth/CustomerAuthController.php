<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:180', 'required_without:mobile', Rule::unique('users', 'email')],
            'mobile' => ['nullable', 'string', 'max:30', 'required_without:email', 'regex:/^\+?[0-9][0-9\s().-]{5,28}$/', Rule::unique('users', 'mobile')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'mobile' => $data['mobile'] ?? null,
            'password' => $data['password'],
            'is_admin' => false,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'identifier' => ['required', 'string', 'max:180'],
            'password' => ['required', 'string'],
        ]);

        $identifier = trim($data['identifier']);
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        if (! Auth::attempt([$field => $identifier, 'password' => $data['password'], 'is_admin' => false], $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'identifier' => 'These customer sign-in details do not match our records.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request)
    {
        abort_if($request->user()->is_admin, 403);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been signed out.');
    }
}
