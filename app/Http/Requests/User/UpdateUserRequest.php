<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'name'       => 'sometimes|required|string|max:255',
            'last_name'  => 'sometimes|required|string|max:255',
            'email'      => 'sometimes|required|email|unique:users,email,' . $userId,
            'password'   => 'sometimes|nullable|string|min:6',
            'status'     => 'sometimes|required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Si envías el nombre, no puede estar vacío.',
            'last_name.required'  => 'Si envías el apellido, no puede estar vacío.',
            'email.email'         => 'El correo no tiene un formato válido.',
            'email.unique'        => 'Este correo ya está registrado.',
            'password.min'        => 'La contraseña debe tener mínimo 6 caracteres.',
            'status.boolean'      => 'El estado debe ser verdadero o falso.',
        ];
    }
}
