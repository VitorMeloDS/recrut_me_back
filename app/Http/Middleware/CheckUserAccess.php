<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        // Obtém o usuário autenticado
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        // Obtém o nome da rota e o método HTTP
        $routeName = $request->route()->getName();
        $method = $request->getMethod(); // GET, POST, PUT, DELETE

        // Definição das permissões por perfil
        $permissions = [
            1 => [ // Administrador
                'profile.index' => ['GET'],
                'profile.store' => ['POST'],
                'profile.show' => ['GET'],
                'profile.update' => ['PUT'],
                'profile.destroy' => ['DELETE'],
                'user' => ['GET', 'POST'],
                'menu' => ['GET'],
                'invite.index' => ['GET'],
                'invite.sendEmail' => ['POST'],
                'employee.index' => ['GET'],
                'employee.exportToExcel' => ['GET'],
                'employee.show' => ['GET'],
                'employee.updateProfile' => ['PUT'],
            ],
            2 => [ // Gente e Cultura
                'invite.index' => ['GET'],
                'invite.sendEmail' => ['POST'],
                'employee.index' => ['GET'],
                'employee.show' => ['GET'],
                'profile.index' => ['GET'],
                'profile.show' => ['GET'],
                'employee.exportToExcel' => ['GET'],
                'user' => ['GET', 'POST'],
                'menu' => ['GET'],
            ],
            3 => [ // Colaborador Comum
                ],
        ];

        // Verifica se o usuário tem permissão para acessar a rota com o método específico
        if (!isset($permissions[$user->id_profile][$routeName]) || !in_array($method, $permissions[$user->id_profile][$routeName])) {
            return response()->json(['error' => 'Acesso não autorizado.'], 403);
        }

        return $next($request);
    }
}