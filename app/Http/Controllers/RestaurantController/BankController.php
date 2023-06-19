<?php

namespace App\Http\Controllers\RestaurantController;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Country;
use App\Models\ServiceSubscription;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class BankController extends Controller
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
       
        $banks = Bank::where('restaurant_id' , $restaurant->id)->orderBy('id', 'desc')->get();
        return view('restaurant.banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('restaurant.banks.create' , compact('countries'));
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
            'country_id' => 'required|exists:countries,id',
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'useful' => 'required|string|max:191',
            'account_number' => 'required|max:191',
            'IBAN_number' => 'required|max:191',
        ]);
        $restaurant = auth('restaurant')->user();
        // create new bank
        Bank::create([
            'country_id' => $request->country_id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'useful' => $request->useful,
            'restaurant_id' => $restaurant->id ,
            'account_number' => $request->account_number,
            'IBAN_number' => $request->IBAN_number,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('restaurant.banks.index');
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
        $countries = Country::all();
        $bank = Bank::findOrFail($id);
        return view('restaurant.banks.edit', compact('bank' , 'countries'));
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
        $bank = Bank::where('restaurant_id' , $restaurant->id)->findOrFail($id);
        $this->validate($request, [
            'country_id' => 'required|exists:countries,id',
            'name_ar' => 'required|string|max:191',
            'name_en' => 'required|string|max:191',
            'useful' => 'required|string|max:191',
            'account_number' => 'required|max:191',
            'IBAN_number' => 'required|max:191',
        ]);
        $bank->update([
            'country_id' => $request->country_id,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'useful' => $request->useful,
            'account_number' => $request->account_number,
            'IBAN_number' => $request->IBAN_number,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('restaurant.banks.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank = Bank::findOrFail($id);
        $bank->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('restaurant.banks.index');
    }


    public function settings(Request $request){
        $restaurant = auth('restaurant')->user();
        
        if($request->method() == 'POST'):
            // return $restaurant;
            $data = $request->validate([
                'enable_reservation_bank' => 'required|in:true,false' , 
            ]);
            $restaurant->update($data);
            flash(trans('messages.updated'))->success();
            return redirect(route('restaurant.banks.index'));
        endif;
        return view('restaurant.banks.setting' , compact('restaurant'));
    }
}
