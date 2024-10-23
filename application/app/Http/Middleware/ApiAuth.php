<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuth
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('API-Token') !== env("API_TOKEN")) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
