<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
    }
    public function login()
    {
        return view('website.'.session('theme_path').'users.login');
    }
    public function submit_login(Request $request)
    {
        $this->validate($request , [
            'email'    => 'required|email',
            'password' => 'required|max:8',
        ]);
        $credential =[
            'email'=>$request->email,
            'password'=>$request->password
        ];
        if (Auth::guard('web')->attempt($credential, $request->member)){
            return redirect()->to('/');
        }
        return redirect()->back()->withInput($request->only(['email','remember']))->with('warning_login', trans('messages.warning_login'));

    }

    public function register()
    {
        $cities = City::all();
        return view('website.'.session('theme_path').'users.register' , compact('cities'));
    }
    public function submitRegister(Request $request)
    {
        $this->validate($request , [
            'name'          => 'required|string|max:191',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|string|min:6',
            'password_confirmation' => 'required|same:password',
            'phone_number'  => ['required', 'unique:users','regex:/^((05)|(01))[0-9]{8}/' , 'max:11'],
            'city_id'       => 'required|exists:cities,id',
            'type'          => 'required|in:origin,sector',
//            'photo'         => 'sometimes|mimes:jpg,jpeg,png,gif,tif,psd,pmp|max:5000',
        ]);
        // create new  users
        User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'city_id'      => $request->city_id,
            'type'         => $request->type,
//            'photo'        => $request->file('photo') == null ? null : UploadImage($request->file('photo') , 'photo' , '/uploads/users'),
        ]);
        $credential =[
            'email'=>$request->email,
            'password'=>$request->password
        ];
        if (Auth::guard('web')->attempt($credential, $request->member)){
            return redirect()->to('/');
        }

    }
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
