<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\DB;
use App\Models\Aula;
use App\Models\Modulo;
use App\Models\CursoRealizado;


class AssinanteAtivo extends Middleware
{

    //public function handle($request, Closure $next, $guard = null)
    public function handle($request, Closure $next, ...$guards)
    {

        $user = Auth()->User();

        $roles = $user->roles;

        if($roles->count() > 0){
            
            if(!$user->cliente && $user->roles->contains('name', 'Cliente')){
                return redirect()->route('logout');
            }  

            return $next($request);
        }

        return redirect()->route('logout');
    }
}
