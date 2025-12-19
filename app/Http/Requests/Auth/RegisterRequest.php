<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required', 
                'string', 
                'max:30',
                'regex:/^[\pL\s\-]+$/u' 
            ],
            'username' => [
                'required', 
                'string', 
                'max:30', 
                'unique:'.User::class.',username',
                'regex:/^[a-zA-Z0-9_\-]+$/', 
                'alpha_dash' 
            ],
            'email' => [
                'required', 
                'string', 
                'lowercase', 
                'email:rfc,dns', 
                'max:255', 
                'unique:'.User::class.',email'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede superar 30 caracteres',
            'name.regex' => 'El nombre solo puede contener letras, espacios y guiones',
            
            'username.required' => 'El nombre de usuario es obligatorio',
            'username.unique' => 'Este nombre de usuario ya está en uso',
            'username.max' => 'El nombre de usuario no puede superar 30 caracteres',
            'username.regex' => 'El nombre de usuario solo puede contener letras, números, guiones bajos (_) y guiones medios (-)',
            'username.alpha_dash' => 'El nombre de usuario tiene caracteres no permitidos',
            
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debes introducir un correo electrónico válido',
            'email.unique' => 'Este correo electrónico ya está registrado',
            'email.lowercase' => 'El correo electrónico debe estar en minúsculas',
            
            'password.required' => 'La contraseña es obligatoria',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.letters' => 'La contraseña debe contener letras',
            'password.mixed' => 'La contraseña debe contener mayúsculas y minúsculas',
            'password.numbers' => 'La contraseña debe contener números',
            'password.symbols' => 'La contraseña debe contener símbolos especiales',
        ];
    }
    
    protected function prepareForValidation(): void
    {
        $this->merge([
            'email' => strtolower($this->email),
        ]);
    }
}