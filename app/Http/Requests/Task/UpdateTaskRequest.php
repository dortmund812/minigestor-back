<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title'         => 'sometimes|required|string|max:255',
            'description'   => 'sometimes|required|string',
            'status'        => 'sometimes|required|boolean',
            'task_status'   => 'sometimes|required|in:pending,in_progress,done',
            'user_assigned' => 'sometimes|required|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'         => 'Si envías el título, no puede estar vacío.',
            'description.required'   => 'Si envías la descripción, no puede estar vacía.',
            'status.boolean'         => 'El campo status debe ser verdadero o falso.',
            'task_status.in'         => 'El estado debe ser pending, in_progress o done.',
            'user_assigned.exists'   => 'El usuario asignado no existe.',
        ];
    }
}
