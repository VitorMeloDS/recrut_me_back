<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;


class AuthController extends Controller
{
    public function createUser(Request $request)
    {
        return response()->json(['Method not implemented']);
    }

    public function login(Request $request)
    {
        $rules = [
            'cpf' => ['required', 'cpf'],
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[\W]/',
            ],
        ];

        $messages = [
            'cpf.cpf' => 'O CPF informado é invalido.',
            'cpf.required' => 'O CPF é obrigatório.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',
            'password.regex' => 'A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número e um caractere especial.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = User::where('cpf', $request->cpf)
            ->where('password', '<>', null)
            ->where('disabled', '<>', true)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'As credenciais fornecidas estão incorretas.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $accessToken = $user->createToken('auth_token', ['*'], now()->addHour())->plainTextToken;

        $refreshToken = $user->createToken('refresh_token', ['refresh'], now()->addDay())->plainTextToken;

        return response()->json([
            'token' => $accessToken,
            'refreshToken' => $refreshToken,
        ]);
    }

    public function refreshToken(Request $request)
    {
        $request->validate([
            'refreshToken' => 'required|string',
        ]);

        $refreshToken = PersonalAccessToken::findToken($request->refreshToken);

        if (!$refreshToken || $refreshToken->tokenable_type !== User::class || !$refreshToken->can('refresh')) {
            return response()->json([
                'message' => 'Token inválido ou expirado.',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $refreshToken->tokenable;

        $refreshToken->delete();

        $newAccessToken = $user->createToken('auth_token', ['*'], now()->addHour(1))->plainTextToken;
        $newRefreshToken = $user->createToken('refresh_token', ['refresh'], now()->addDay(1))->plainTextToken;

        return response()->json([
            'token' => $newAccessToken,
            'refreshToken' => $newRefreshToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout realizado com sucesso.']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function isAuth() {
        return response()->json(['isAuth' => auth('sanctum')->check()]);
    }
}