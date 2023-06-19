<?php

// Localization.php

namespace App\Http\Middleware;

use Closure;
use App;

class Localization
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
        if(in_array($request->segment(1) , ['restaurant'])):
            $lang = session()->has('lang_restaurant') ? session('lang_restaurant') : app()->getLocale();
            if(!session()->has('lang_restaurant')):
                session()->put('lang_restaurant' ,$lang );
            endif;
                
            app()->setLocale($lang);
        else:
           
            if (session()->has('locale')) {
                App::setLocale(session()->get('locale'));
            }
        endif;
       
        return $next($request);
    }
}
