<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id' , 'desc')->paginate(500);
        return view('admin.users.index' , compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.users.create' , compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'name'         => 'required|string|max:191',
            'password'     => 'required|string|min:8|confirmed',
            'phone_number' => ['required', 'unique:users', 'regex:/^((05)|(01))[0-9]{8}/'],
            'country_id'   => 'required|exists:countries,id',
            'active'       => 'required|in:true,false' ,
        ]);
        // create new user
        User::create([
            'name'         => $request->name,
            'password'     => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'country_id'   => $request->country_id,
            'active'       => $request->active,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('clients.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $countries = Country::all();
        return view('admin.users.edit' , compact('user' , 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $this->validate($request , [
            'name'         => 'required|string|max:191',
            'password'     => 'nullable|string|min:8|confirmed',
            'phone_number' => ['required', 'unique:users,phone_number,' .$user->id, 'regex:/^((05)|(01))[0-9]{8}/'],
            'country_id'   => 'required|exists:countries,id',
            'active'       => 'required|in:true,false' ,
        ]);
        $user->update([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => $request->password == null ? $user->password : Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'country_id'   => $request->country_id,
            'active'       => $request->active,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('clients.index');
    }

    public function clientActivation($id , $active)
    {
        $user = User::findOrFail($id);
        $user->update([
            'active' => $active,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('clients.index');
    }
}
