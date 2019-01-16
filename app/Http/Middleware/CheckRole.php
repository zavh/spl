<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class CheckRole
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
        $user = Auth::User();
        $role = $user->role->role_name;
        if($role == 'superadmin')
            return $next($request);
        //return redirect('home')->with('error','Permission denied!');
        //dd($request);
    }
}
