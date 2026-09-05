<?php

namespace App\Http\Controllers;

use App\Models\Perfil;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|string',
            ],
            [
                'email.required' => 'El email es obligatorio.',
                'email.email' => 'Ingresá un email válido.',
                'password.required' => 'La contraseña es obligatoria.',
            ]
        );

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'Las credenciales no son válidas.'
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('panel.dashboard'));
    }


    public function showRegister()
    {
        return view('auth.register', [
            'plans' => Plan::orderBy('monthly_price')->get()
        ]);
    }


    public function register(Request $request)
    {
        $data = $request->validate(
            [
                'name' => 'required|string|max:120',

                'email' => 'required|email|max:255|unique:users,email',

                'password' => 'required|string|min:8|confirmed',

                'nombre_negocio' => 'required|string|max:255',

                'slug' => 'required|string|max:100|alpha_dash|unique:perfil,slug',

                'plan_id' => 'nullable|exists:plans,id',
            ],
            [
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser texto.',
                'name.max' => 'El nombre no puede superar los 120 caracteres.',

                'email.required' => 'El email es obligatorio.',
                'email.email' => 'Ingresá un email válido.',
                'email.max' => 'El email no puede superar los 255 caracteres.',
                'email.unique' => 'Este email ya está registrado.',

                'password.required' => 'La contraseña es obligatoria.',
                'password.string' => 'La contraseña debe ser válida.',
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.confirmed' => 'Las contraseñas no coinciden.',

                'nombre_negocio.required' => 'El nombre del negocio es obligatorio.',
                'nombre_negocio.string' => 'El nombre del negocio debe ser texto.',
                'nombre_negocio.max' => 'El nombre del negocio no puede superar los 255 caracteres.',

                'slug.required' => 'La URL de tu página es obligatoria.',
                'slug.string' => 'La URL de tu página debe ser texto.',
                'slug.max' => 'La URL no puede superar los 100 caracteres.',
                'slug.alpha_dash' => 'La URL solo puede contener letras, números, guiones y guiones bajos.',
                'slug.unique' => 'Esta URL ya está siendo utilizada.',

                'plan_id.exists' => 'El plan seleccionado no es válido.',
            ]
        );

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->perfil()->create([
            'plan_id' => $data['plan_id'] ?? null,
            'slug' => Str::lower($data['slug']),
            'nombre_negocio' => $data['nombre_negocio'],
            'color_principal' => '#e85d04',
        ]);

        Auth::login($user);

        return redirect()->route('panel.dashboard');
    }


    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('catalogo');
    }
}
