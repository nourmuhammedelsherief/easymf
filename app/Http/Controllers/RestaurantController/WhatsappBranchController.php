<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\WhatsappBranch;
use App\Models\Country;
use App\Models\ServiceSubscription;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class WhatsappBranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:restaurant');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $restaurant = auth('restaurant')->user();

        $whatsappBranches = WhatsappBranch::where('restaurant_id' , $restaurant->id)->orderBy('id', 'desc')->get();
        return view('restaurant.whatsapp_branches.index', compact('whatsappBranches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('restaurant.whatsapp_branches.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            // 'restaurant_id' => 'required|integer',
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'phone' => 'required|unique:whatsapp_branches,phone',
        ]);
        $restaurant = auth('restaurant')->user();
        // create new bank
        WhatsappBranch::create([
            'restaurant_id' => $restaurant->id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'phone' => $request->phone,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('whatsapp_branches.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $branch = WhatsappBranch::findOrFail($id);
        return view('restaurant.whatsapp_branches.edit', compact('branch' ));
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
        $restaurant = auth('restaurant')->user();
        $branch = WhatsappBranch::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        $this->validate($request, [
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'phone' => 'required|unique:whatsapp_branches,phone,' . $id,
        ]);
        $branch->update([
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'phone' => $request->phone,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('whatsapp_branches.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $bank = WhatsappBranch::findOrFail($id);
        $bank->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('whatsapp_branches.index');
    }


}
