<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Marketer;
use App\Models\Package;
use App\Models\SellerCode;
use Illuminate\Http\Request;

class SellerCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $marketer = Marketer::findOrFail($id);
        SellerCode::whereMarketerId($id)->where('end_at' , '<'  , date('Y-m-d'))->where('active' , '!=' , 'false')->update([
            'active' => 'false' ,
        ]);
        $seller_codes = SellerCode::whereMarketerId($id)->orderBy('id', 'desc')->paginate(500);
        
        return view('admin.seller_codes.index', compact('seller_codes' , 'marketer'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        $marketers = Marketer::all();
        $packages = Package::get();
        return view('admin.seller_codes.create', compact('marketers' , 'countries' , 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $marketer = Employee::findOrFail($id);
        $this->validate($request, [

            'country_id' => 'required|exists:countries,id',
            'marketer_id' => 'required|exists:marketers,id',
            'used_type' => 'required|in:code,url' ,
            'package_id' => 'required_if:used_type,url|exists:packages,id',
            'seller_name' => 'required_if:used_type,code|string|max:191',
            'custom_url' => 'required_if:used_type,url|string|min:1|max:190|unique:seller_codes,custom_url',
            'permanent'   => 'required|in:true,false',
            'active' => 'required|in:true,false',
            'percentage' => 'required|numeric',
            'code_percentage' => 'required|numeric',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'type'   => 'required_if:used_type,code|in:restaurant,service,branch,both',

            // 'discount' => 'required|in:subscription,renew',
            //    'commission'   => 'required|numeric',

        ]);

        // create new seller code
        SellerCode::create([
            'country_id' => $request->country_id,
            'type' => $request->used_type == 'url' ? 'restaurant' : $request->type,
            // 'discount' => $request->discount,
            'marketer_id' => $request->marketer_id,
            'seller_name' => $request->used_type == 'code' ? $request->seller_name : null ,
            'custom_url' => $request->used_type == 'url' ? $request->custom_url : null ,
            'package_id' => $request->used_type == 'url' ? $request->package_id: null ,
            'used_type' => $request->used_type,
            'active' => $request->active,
            'permanent' => $request->permanent,
            'percentage' => $request->percentage,
            'code_percentage' => $request->code_percentage,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,


//            'commission'   => $request->commission,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('seller_codes.index' , $request->marketer_id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $seller_code = SellerCode::findOrFail($id);

        return view('admin.seller_codes.show', compact('seller_code'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $seller_code = SellerCode::findOrFail($id);
        $marketers = Marketer::all();
        $countries = Country::all();
        $packages = Package::get();
        return view('admin.seller_codes.edit', compact('marketers', 'countries','seller_code' , 'packages'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $seller_code = SellerCode::findOrFail($id);
        $this->validate($request, [
            'country_id' => 'required|exists:countries,id',
            'marketer_id' => 'required|exists:marketers,id',
            'permanent' => 'required|in:true,false',
            'active' => 'required|in:true,false',
            'percentage' => 'required|numeric',
            'code_percentage' => 'required|numeric',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'type'   => 'required_if:used_type,code|in:restaurant,service,branch,both',
            'used_type' => 'required|in:code,url' ,
            'package_id' => 'required_if:used_type,url|exists:packages,id',
            'seller_name' => 'required_if:used_type,code|string|max:191',
            'custom_url' => 'required_if:used_type,url|string|min:1|max:190|unique:seller_codes,custom_url,' . $id,
            // 'discount' => 'required|in:subscription,renew',
//            'commission'   => 'required|numeric',
        ]);
        $seller_code->update([
            'marketer_id' => $request->marketer_id,
            // 'seller_name' => $request->seller_name,
            'permanent' => $request->permanent,
            'active' => $request->active,
            'percentage' => $request->percentage,
            'code_percentage' => $request->code_percentage,
            'start_at' => $request->start_at,
            'end_at' => $request->end_at,
            'country_id' => $request->country_id,
            'type' => $request->used_type == 'url' ? 'restaurant' : $request->type,
            'seller_name' => $request->used_type == 'code' ? $request->seller_name : null ,
            'custom_url' => $request->used_type == 'url' ? $request->custom_url : null ,
            'package_id' => $request->used_type == 'url' ? $request->package_id: null ,
            'used_type' => $request->used_type,
            // 'discount' => $request->discount,
//            'commission'   => $request->commission,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('seller_codes.index' , $seller_code->marketer->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $seller_code = SellerCode::findOrFail($id);
        $code = $seller_code->marketer->id;
        $seller_code->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('seller_codes.index' , $code);
    }

    public function activate($id, $status)
    {
        $seller_code = SellerCode::findOrFail($id);
        if($status == 'true' and  SellerCode::whereId($id)->where('end_at' , '<'  , date('Y-m-d'))->count() > 0){
            
            flash(trans('dashboard.errors.seller_end_date'))->error();
            return redirect()->route('seller_codes.index' , $seller_code->marketer->id);
        }
        $seller_code->update([
            'active' => $status,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('seller_codes.index' , $seller_code->marketer->id);
    }
}
