<?php

namespace App\Http\Middleware;

use Closure;
use Hash;

class ApiSecureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $temp_token='Gc5XSsWSRSpEdZc8n66NUQ6LtUoXyMYHUZLQAcvO';

        $bearer=$request->bearerToken();
        if($bearer==$temp_token)
            return $next($request);
        else
            abort(401);
    }
}
