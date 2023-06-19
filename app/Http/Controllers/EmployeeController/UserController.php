<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use App\Models\RestaurantEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function my_profile()
    {
        $employee = Auth::guard('employee')->user();
        return view('employee.users.profile' ,compact('employee'));
    }

    public function my_profile_edit(Request $request , $id)
    {
        $employee = RestaurantEmployee::findOrFail($id);
        $this->validate($request , [
            'name'     => 'required|string|max:191',
            'email'    => 'required|email|max:191|unique:restaurant_employees,email,' . $id,
            'password' => 'nullable|confirmed|min:6',
        ]);
        $employee->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password == null ? $employee->password : Hash::make($request->password),
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }
}
