<?php

namespace App\Http\Controllers\RestaurantController\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:restaurant')->except('logout');
    }

    public function showLoginForm()
    {
        return view('restaurant.authAdmin.login');
    }

    public function login(Request $request)
    {
        App::setLocale('ar');
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
        // Verified - send email
        $credential = [
            'email' => $request->email,
            'password' => $request->password
        ];
        if (Auth::guard('restaurant')->attempt($credential, true)):
            return redirect()->route('restaurant.home');
        endif;
        return redirect()->back()->withInput($request->only(['email', 'remember']))->with('warning_login', trans('messages.warning_login'));
    }

    public function logout(Request $request)
    {
        Auth::guard('restaurant')->logout();
        return redirect('/restaurant/login');
    }
}
