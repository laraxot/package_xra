<?php

namespace XRA\XRA\Middleware;
use Auth;
use Closure;

class CheckArea{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){
        
        if (!Auth::check()) {
            return redirect()->route('lu::login')->with('warning', 'You need to log in first');
        }
        
        $segments=$request->segments();
        $guid=$segments[1];

        $user=Auth::user();
        if($user->areas->where('guid',$guid)->count()==0){
            return redirect('/admin');
        }
        
        return $next($request);
    }
}