<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        $permitted_roles = explode('|', $roles);

        for($i = 0; $i<count($permitted_roles); $i++){
            if(Auth::user()->rol == $permitted_roles[$i]){
                return $next($request);
            }
        }
        
        return redirect('/');
    }
}
