<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Marketer;
use App\Models\MarketerTransfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MarketerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $marketers = Marketer::orderBy('id' , 'desc')->paginate(500);
        return view('admin.marketers.index' , compact('marketers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.marketers.create');
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
            'name'     => 'required|string|max:191',
            'email'    => 'required|email|unique:marketers|max:191',
            'password' => 'required|confirmed|min:6',
        ]);
        // create new marketer
        Marketer::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('marketers.index');
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
        $marketer = Marketer::findOrFail($id);
        return view('admin.marketers.edit' , compact('marketer'));
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
        return redirect()->route('marketers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $marketer = Marketer::findOrFail($id);
        $marketer->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('marketers.index');
    }

    public function balance_transfer()
    {
        $marketers = Marketer::all();
        return view('admin.marketers.balance_transfer' , compact('marketers'));
    }
    public function store_balance_transfer(Request $request)
    {
        $this->validate($request , [
            'marketer_id'  => 'required|exists:marketers,id',
            'amount'       => 'required|numeric',
            'transfer_photo' => 'required|mimes:jpg,jpeg,png,gif,tif,psd,bmp,webp|max:5000',
        ]);

        MarketerTransfer::create([
            'marketer_id'     => $request->marketer_id,
            'transfer_photo'  => UploadImage($request->file('transfer_photo') , 'transfer' , '/uploads/transfers'),
            'amount'          => $request->amount,
        ]);
        $marketer = Marketer::find($request->marketer_id);
        $marketer_amount = $marketer->balance - $request->amount;
        $marketer->update([
            'balance'  => $marketer_amount
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('marketers.index');
    }

    public function transfers($id)
    {
        $marketer = Marketer::findOrFail($id);
        $transfers = MarketerTransfer::whereMarketerId($marketer->id)->paginate(100);
        return view('admin.marketers.transfers' , compact('transfers' , 'marketer'));
    }
}
