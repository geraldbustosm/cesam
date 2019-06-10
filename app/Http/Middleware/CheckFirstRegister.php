<?php

namespace App\Http\Middleware;

use Closure;
use App\User;

class CheckFirstRegister{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(User::all()->count()>0){
            return redirect('/login');
        }
        return $next($request);
    }
}
