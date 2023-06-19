<?php

namespace App\Http\Controllers\MarketerController;

use App\Http\Controllers\Controller;
use App\Models\Marketer;
use App\Models\MarketerOperation;
use App\Models\MarketerTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MarketerController extends Controller
{
    public function my_profile()
    {
        $marketer = Auth::guard('marketer')->user();
        return view('marketer.users.profile' ,compact('marketer'));
    }

    public function my_profile_edit(Request $request , $id)
    {
        $marketer = Marketer::findOrFail($id);
        $this->validate($request , [
            'name'     => 'required|string|max:191',
            'email'    => 'required|email|max:191|unique:marketers,email,' . $id,
            'password' => 'nullable|confirmed|min:6',
        ]);
        $marketer->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password == null ? $marketer->password : Hash::make($request->password),
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }
    public function confirmed_operations()
    {
        $marketer = Auth::guard('marketer')->user();
        $operations = MarketerOperation::whereMarketerId($marketer->id)
            ->where('status' , 'done')
            ->paginate(100);
        return view('marketer.users.confirmed' , compact('operations' , 'marketer'));
    }
    public function not_confirmed_operations()
    {
        $marketer = Auth::guard('marketer')->user();
        $operations = MarketerOperation::whereMarketerId($marketer->id)
            ->where('status' , 'not_done')
            ->paginate(100);
        return view('marketer.users.not_confirmed' , compact('operations' , 'marketer'));
    }

    public function transfers()
    {
        $marketer = Auth::guard('marketer')->user();
        $transfers = MarketerTransfer::whereMarketerId($marketer->id)->paginate(100);
        return view('marketer.users.transfers' ,compact('transfers' , 'marketer'));
    }
}
