<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
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
        if(auth()->user()->is_admin == 1){
            return $next($request);
        }

        if(auth()->user()->role=='boutique_seller' || auth()->user()->role=='brand_seller'){
            return redirect()->route('seller.dashboard')->with('error',"You don't have admin access.");
        }
   
        return redirect('/');
    }
        
    
}
