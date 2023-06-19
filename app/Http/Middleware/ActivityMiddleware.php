<?php

namespace App\Http\Middleware;

use App\Models\ActivityLog;
use Closure;
use Illuminate\Http\Request;

class ActivityMiddleware
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
        $data = $request->except(['_token' , 'password' , 'csrf-token' , 'token' ]);
        $segments = $request->segments();
        $perventToSave = ['datatable-' ];
        $check = true;
        if($request->has('draw') and $request->has('columns') and $request->has('order') and $request->has('start') and $request->has('length')) return $next($request);
        foreach($perventToSave as $item):
            if(in_array($item , $segments)) $check = false;
        endforeach;
        if($check){
            if(in_array($request->segment(1) , ['dashboard' , 'api'])) $guard = $request->segment(1);
            elseif(in_array($request->segment(1) , ['broadcasting']) ) return $next($request);
            else $guard = 'web';
            if($guard == 'api') $user = auth()->guard('api')->user();
            else $user = $request->user();
            $user_id = null;
            $activity = new ActivityLog([
                'user_id' => $user_id ,
                
                'guard' => $guard , 
                'method' => $request->method() , 
                'url' => $request->url() , 
                'data' => $data 
            ]);
            $activity->save();
      
        }
        return $next($request);
    }
}
