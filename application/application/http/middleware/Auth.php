<?php

namespace app\http\middleware;

class Auth
{
    public function handle($request, \Closure $next)
    {
        
        if (session('user') == null) {
            return redirect(url('/user/login'));
        }

        return $next($request);
    }
}