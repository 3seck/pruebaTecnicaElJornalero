<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:8',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                    'regex:/[@$!%*?&#]/',
                    'confirmed',
                ],
            ], [
                'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
                'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, un número, y un símbolo especial.',
                'password.confirmed' => 'La confirmación de la contraseña no coincide.',
                'email.unique' => 'El correo electrónico ya está en uso.',
            ]);
    
            // Crear el nuevo usuario
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);
    
            // Autenticar al usuario
            Auth::login($user);
    
            return redirect()->intended('/characters')->with('success', 'Registro exitoso, ¡bienvenido a Rick y Morty personajes!');
    
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Hubo un problema durante el registro: ' . $e->getMessage()])->withInput();
        }
    }
    


    public function login(Request $request)
{
    try {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Intentar autenticar al usuario
        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Las credenciales introducidas no son correctas.',
            ])->withInput();
        }

        // Regenerar la sesió
        $request->session()->regenerate();

        return redirect()->intended('/characters')->with('success', 'Inicio de sesión exitoso.');

    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'Hubo un problema durante el inicio de sesión: ' . $e->getMessage()])->withInput();
    }
}


public function logout(Request $request)
{
    try {
        // Cerrar la sesión del usuario
        Auth::guard('web')->logout();

        // Invalidar la sesión actual
        $request->session()->invalidate();

        // Regenerar el token CSRF
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Cierre de sesión exitoso.');

    } catch (\Exception $e) {
        return redirect('/characters')->withErrors(['error' => 'Hubo un problema durante el cierre de sesión: ' . $e->getMessage()]);
    }
}

}