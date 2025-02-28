<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     */
    protected $except = [
        'api/*', // Exclui todas as rotas da API
        'sanctum/csrf-cookie', // Exclui a rota usada para obter o token CSRF
    ];
}
