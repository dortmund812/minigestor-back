<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registro de usuario.
     * POST /register
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();
            $user = User::create([
                'name'       => $data['name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'status'     => true,
                'role'       => User::ROLE_MEMBER,
            ]);

            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json([
                'message'    => 'Registro exitoso.',
                'user'       => $user,
                'token'      => $token,
                'token_type' => 'Bearer',
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al registrar el usuario.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Login de usuario y generación de token.
     * POST /login
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required|string',
            ]);

            $user = User::where('email', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'message'    => 'Credenciales inválidas.',
                ], 401);
            }

            if (!$user->status) {
                return response()->json([
                    'message'    => 'El usuario está inactivo.',
                ], 403);
            }

            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message'    => 'Login exitoso.',
                'user'       => $user,
                'token'      => $token,
                'token_type' => 'Bearer',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message'    => 'Error de validación en el login.',
                'errors'     => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al intentar iniciar sesión.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout del usuario y eliminación del token actual.
     * POST /logout
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user && $user->currentAccessToken()) {
                $user->currentAccessToken()->delete();
            }

            return response()->json([
                'message' => 'Logout exitoso.',
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message'    => 'Error al cerrar sesión.',
                'error'      => $e->getMessage(),
            ], 500);
        }
    }
}
