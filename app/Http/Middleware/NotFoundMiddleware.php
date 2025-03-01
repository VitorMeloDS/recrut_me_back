<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotFoundMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->getStatusCode() == 404) {
            return response()->json([
                'error' => 'Rota não encontrada.',
                'status' => 404
            ], 404);
        }

        return $response;
    }
}
