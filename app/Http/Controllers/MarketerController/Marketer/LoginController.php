<?php

namespace App\Http\Controllers\MarketerController\Marketer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

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
        $this->middleware('guest:marketer')->except('logout');
    }
    public function showLoginForm()
    {
        
        return view('marketer.authAdmin.login');
    }
    public function login(Request $request)
    {
        App::setLocale('ar');
        $this->validate($request,[
            'email'=>'required|email',
            'password'=>'required|min:6',
        ]);
            // Verified - send email
            $credential =[
                'email'=>$request->email,
                'password'=>$request->password
            ];
            if (Auth::guard('marketer')->attempt($credential, $request->member)){
                return redirect()->intended(route('marketer.home'));
            }
            return redirect()->back()->withInput($request->only(['email','remember']))->with('warning_login', trans('messages.warning_login'));
    }

    public function logout(Request $request)
    {
//        dd(Auth::guard('admin')->user()->id);
        Auth::guard('marketer')->logout();

//        $request->session()->invalidate();

        return redirect('/marketer/login');
    }
}
