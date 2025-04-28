<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    /*
    |-------------------------------------------------------------------------- 
    | Register Controller 
    |-------------------------------------------------------------------------- 
    |
    | Este controlador maneja el registro de nuevos usuarios, así como su
    | validación y creación. Por defecto, este controlador utiliza un trait 
    | para proporcionar esta funcionalidad sin necesidad de código adicional.
    |
    */

    use RegistersUsers;

    /**
     * Donde redirigir a los usuarios después del registro.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Crear una nueva instancia del controlador.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Obtener un validador para una solicitud de registro entrante.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator = Validator::make($data, [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido_paterno' => ['required', 'string', 'max:255'],
            'apellido_materno' => ['nullable', 'string', 'max:255'],
            'nombre_usuario' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => 'required',
        ], [
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden, por favor inténtalo de nuevo.',
            'g-recaptcha-response.required' => 'Por favor, verifica que no eres un robot.',
        ]);

        // Validación personalizada para reCAPTCHA
        $validator->after(function ($validator) use ($data) {
            if (isset($data['g-recaptcha-response'])) {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => env('RECAPTCHA_SECRET_KEY'),
                    'response' => $data['g-recaptcha-response'],
                    'remoteip' => request()->ip()
                ]);
                
                $responseData = $response->json();
                
                if (!$responseData['success']) {
                    $validator->errors()->add('g-recaptcha-response', 'La verificación reCAPTCHA falló. Por favor, intenta nuevamente.');
                }
            }
        });

        return $validator;
    }

    /**
     * Crear una nueva instancia de usuario después de un registro válido.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Crear el usuario después de que todas las validaciones hayan pasado
        return User::create([
            'nombre' => $data['nombre'],
            'apellido_paterno' => $data['apellido_paterno'],
            'apellido_materno' => $data['apellido_materno'] ?? '',
            'nombre_usuario' => $data['nombre_usuario'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}