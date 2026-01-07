<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class SaveTaskRequest extends FormRequest
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
        return [
            'title'         => 'required|string|max:255',
            'description'   => 'required|string',
            'status'        => 'sometimes|boolean', // activa/inactiva
            'task_status'   => 'sometimes|in:pending,in_progress,done',
            'user_assigned' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Custom messages (opcional, pero útil).
     */
    public function messages(): array
    {
        return [
            'title.required'         => 'El título es obligatorio.',
            'title.max'              => 'El título no puede tener más de 255 caracteres.',
            'description.required'   => 'La descripción es obligatoria.',
            'status.boolean'         => 'El campo status debe ser verdadero o falso.',
            'task_status.in'         => 'El estado de la tarea debe ser pending, in_progress o done.',
            'user_assigned.required' => 'Debe especificar el usuario asignado.',
            'user_assigned.exists'   => 'El usuario asignado no existe.',
        ];
    }
}
