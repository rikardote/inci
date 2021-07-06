<?php

namespace App\Http\Middleware;

use Closure;
use App\Configuration;

class Maintenance
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
        $mantenimiento = Configuration::where('name', "mantenimiento")->first();
        if($mantenimiento->state && !\Auth::user()->admin()){
            return redirect('/mantenimiento');
        }
  
         return $next($request);
    }
}
