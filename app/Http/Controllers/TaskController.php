<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\SaveTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /tasks
     */
    public function index()
    {
        try {
            $status      = request('status');
            $taskStatus  = request('task_status');

            $query = Task::with(['creator', 'assigned'])
                ->orderBy('created_at', 'desc')
                ->where('status', 1);

            if (!is_null($status)) {
                $query->where('status', (bool) $status);
            }

            if ($taskStatus) {
                $query->where('task_status', $taskStatus);
            }

            $tasks = $query->get();

            return response()->json($tasks);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al obtener la lista de tareas.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     * POST /tasks
     */
    public function store(SaveTaskRequest $request)
    {
        try {
            $validated = $request->validated();

            $task = Task::create([
                'uuid'          => Str::uuid(),
                'title'         => $validated['title'],
                'description'   => $validated['description'],
                'status'        => $validated['status'] ?? true,
                'task_status'   => $validated['task_status'] ?? Task::TASK_STATUS_PENDING,
                'user_creator'  => Auth::id(),
                'user_assigned' => $validated['user_assigned'],
            ]);

            $task->load(['creator', 'assigned']);

            return response()->json($task, 201);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al crear la tarea.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /tasks/{task}
     */
    public function show(Task $task)
    {
        try {
            $task->load(['creator', 'assigned']);

            return response()->json($task);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al obtener la tarea.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /tasks/{task}
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        try {
            $validated = $request->validated();

            $task->update($validated);
            $task->load(['creator', 'assigned']);

            return response()->json($task);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al actualizar la tarea.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage (soft delete).
     * DELETE /tasks/{task}
     */
    public function destroy(Task $task)
    {
        try {
            $task->status = 0;
            $task->save();

            return response()->json([
                'message' => 'Task deleted successfully',
                'task'    => $task,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al eliminar la tarea.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }
}
