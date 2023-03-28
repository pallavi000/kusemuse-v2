<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->role=='boutique_seller' || auth()->user()->role=='brand_seller'){
            return $next($request);
        }

        if(auth()->user()->is_admin==1){
            return redirect()->route('admin.dashboard');
        }

        return redirect('/');
    }
}
