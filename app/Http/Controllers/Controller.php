<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected const myFatoorahToken = "1IzSLeOkLFQH1zljLzerCm2RpB8AjFZLf8MMhSy4d8rHb0h1uHqrBleBFlFv-M4SHnyeiWg2zWQraKYJGndFcFvaBIeCPDQNrNs1Zwo7O-4apFyAXXUVZOAKbYzncpn-1ay0BPxB1X5dNH0EuWQ9OTqzcnOI7c5Ola5Esxz0imTrbVuhmKZl7SWBrPCU_SOYt80BSDe5j2XY5skkK7e5TxDRbbibdZPM7S11aYmQ7xKmZvaSj916IhTNXuIZA73TYE4xxkXyL_8dhHobugeLHF58VJNBjMv2UvzEP0pSk0RqGs3-AeqYwD6S3BbVrOaGIbx4fwKlowd6SOoSqkMoD9tmFwgdbkMxzWKYGqxg7bcvB0r62jBqn4YXh0Ej8FO6mrTdWZo5bHviUDRPMitO2KQDvyXrlWnf74n9DxOfV7MbuutAr2K2L3hzCYkdfU0eqA3snq-3Xh1KFjRvd5QqofEt9ubK71Xd4TooKLyXD4hby5prqJTTEVi23bPsPmB1uZ0D1cawjjJB8eUTIwEiPTgdPOSIOTkVFm4OIrHEXT44NbJ9MyDInxmQK8-pGDr0rNeBeLo8J_uyHbfh_sVlpS7XT0d-ehaTDtENoyG0XMe7hgWDYWsNxIe1N3dXwwtyBXLgggAl6JvdDXBzY3wp13oFfTHdASeOtyO3d0zwvkF-9j0uF2WgHd-kqgEyQVV0_UifcbMafuYwTgbBod5iB1soNMoVcTfjlsmr8LV8CK7Nr8xq";

    protected const myFatoorahTestToken = 'rLtt6JWvbUHDDhsZnfpAhpYk4dxYDQkbcPTyGaKp2TYqQgG7FGZ5Th_WD53Oq8Ebz6A53njUoo1w3pjU1D4vs_ZMqFiz_j0urb_BH9Oq9VZoKFoJEDAbRZepGcQanImyYrry7Kt6MnMdgfG5jn4HngWoRdKduNNyP4kzcp3mRv7x00ahkm9LAK7ZRieg7k1PDAnBIOG3EyVSJ5kK4WLMvYr7sCwHbHcu4A5WwelxYK0GMJy37bNAarSJDFQsJ2ZvJjvMDmfWwDVFEVe_5tOomfVNt6bOg9mexbGjMrnHBnKnZR1vQbBtQieDlQepzTZMuQrSuKn-t5XZM7V6fCW7oP-uXGX-sMOajeX65JOf6XVpk29DP6ro8WTAflCDANC193yof8-f5_EYY-3hXhJj7RBXmizDpneEQDSaSz5sFk0sV5qPcARJ9zGG73vuGFyenjPPmtDtXtpx35A-BVcOSBYVIWe9kndG3nclfefjKEuZ3m4jL9Gg1h2JBvmXSMYiZtp9MR5I6pvbvylU_PP5xJFSjVTIz7IQSjcVGO41npnwIxRXNRxFOdIUHn0tjQ-7LwvEcTXyPsHXcMD8WtgBh-wxR8aKX7WPSsT1O8d8reb2aR7K3rkV3K82K_0OgawImEpwSvp9MNKynEAJQS6ZHe_J_l77652xwPNxMRTMASk1ZsJL';
    protected function checkTheme($restaurant){
        if(isset($restaurant->theme->id)){
            session()->put('theme_path' , $restaurant->theme->path);
        }
        if(isset($restaurant->id)) session()->put('current_restaurant' , $restaurant->id);
        $this->checkWebsiteLang($restaurant);
    }

    public function checkWebsiteLang($restaurant){
        if($restaurant)
        {
            $lang = $restaurant->default_lang;
        
        
        if($restaurant->ar == 'false') $lang = 'en' ;
        elseif($restaurant->en == 'false') $lang = 'ar';
        if(!session()->has('default_website_lang')){
            $lang = $restaurant->default_lang;
            session()->put('default_website_lang' , $lang);
        }
        if(session()->has('locale')):
            $lang = session('locale');
            app()->setLocale($lang);
            session()->put('locale'  , $lang);
        else:
            
            app()->setLocale($lang);
            session()->put('locale'  , app()->getLocale());
        endif;
        }
    }
    
}
