<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password as PasswordRules;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'last_name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                'min:6',
                PasswordRules::min(6)->letters()->numbers(),
            ],
        ];
    }

    public function messages()
    {
        return [
            'name.required'      => 'El nombre es obligatorio',
            'name.min'           => 'El nombre debe tener al menos 3 caracteres',
            'last_name.required' => 'El apellido es obligatorio',
            'last_name.min'      => 'El apellido debe tener al menos 3 caracteres',
            'email.required'     => 'El email es obligatorio',
            'email.email'        => 'El email no es válido',
            'email.unique'       => 'El email ya se encuentra registrado',
            'password.required'  => 'La contraseña es obligatoria',
            'password.confirmed' => 'Debes confirmar la contraseña',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'La validación ha fallado',
            'errors'  => $validator->errors(),
        ], 422));
    }
}
