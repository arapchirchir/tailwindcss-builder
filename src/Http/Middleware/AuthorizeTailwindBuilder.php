<?php

namespace Tecworld\TailwindBuilder\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeTailwindBuilder
{
    public function handle(Request $request, Closure $next): Response
    {
        $gate = config('tailwind-builder.gate', 'viewTailwindBuilder');

        if (! Gate::allows($gate)) {
            abort(403);
        }

        return $next($request);
    }
}
