<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class ValidarAdministrador
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
        if(Auth::User() != null)
            if (Auth::User()->id_perfil != '1')
                return redirect('home');

        return $next($request);
    }
}
