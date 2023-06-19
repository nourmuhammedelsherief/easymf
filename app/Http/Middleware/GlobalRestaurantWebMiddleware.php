<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class GlobalRestaurantWebMiddleware
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
        if(!session()->has('theme_path')){
            session()->put('theme_path' , 'themes.default.');
        }
        // add last activity on restaurant dashboard
        // if(!(isUrlActive('t/login') or isUrlActive('t/register')) and auth('restaurant')->check() and $request->segment(1) == 'restaurant'){
        //     $restaurant = auth('restaurant')->user();
        //     $now = Carbon::now();
        //     $lastActivity = Carbon::createFromTimestamp(strtotime($restaurant->last_activity))->addMinute(60);
        //     if($now->greaterThan($lastActivity) and env('CAN_LOGOUT') != 'false'){
        //         auth('restaurant')->logout();
        //         $restaurant->update([
        //             'last_session' => null , 
        //         ]);
        //         return redirect(route('restaurant.login'));
        //     }
        //     $restaurant->update([
        //         'last_activity' => Carbon::now(), 
        //     ]);

        // } // end las activity
        
        return $next($request);
    }
}
