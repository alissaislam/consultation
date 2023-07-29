<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class expert
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->user()->expert){
            abort(403,'you are not an expert');
        }

        return $next($request);
    }
}
