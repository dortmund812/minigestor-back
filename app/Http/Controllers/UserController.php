<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\SaveUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * GET /users
     */
    public function index()
    {
        try {
            $status = request('status');
            $query = User::orderBy('created_at', 'desc')->where('status', 1);

            if (!is_null($status)) {
                $query->where('status', (bool) $status);
            }
            $users = $query->get();

            return response()->json($users);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al obtener la lista de usuarios.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     * POST /users
     */
    public function store(SaveUserRequest $request)
    {
        try {
            $this->authorize('create', User::class);

            $validated = $request->validated();

            $user = User::create([
                'name'       => $validated['name'],
                'last_name'  => $validated['last_name'],
                'email'      => $validated['email'],
                'password'   => Hash::make($validated['password']),
                'status'     => $validated['status'] ?? true,
                'role'       => $validated['role'] ?? User::ROLE_MEMBER,
            ]);

            return response()->json($user, 201);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al crear el usuario.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     * GET /users/{id}
     */
    public function show(User $user)
    {
        try {
            $user = User::findOrFail($user);

            return response()->json($user);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al obtener el usuario.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     * PUT /user/{user}
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $validated = $request->validated();

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json([
                'message'    => 'Error al actualizar el usuario.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * DELETE /users/{id}
     */
    public function destroy(User $user)
    {
        try {
            $user = User::findOrFail($user);
            $user->status = 0;
            $user->save();

            return response()->json([
                'message' => 'User deleted successfully',
                'user'    => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al eliminar el usuario.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }
}
