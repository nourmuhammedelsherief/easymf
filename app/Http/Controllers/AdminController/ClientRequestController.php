<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ClientRequest;
use App\Models\Country;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class ClientRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request )
    {
        $isArchived = false;
        if($request->is_archived === '1'):
            $requests = ClientRequest::withCount('notes')->where('archived' , 1)->orderBy('created_at' , 'desc')->get();
            $isArchived = true;
        else:
            $requests = ClientRequest::withCount('notes')->where('archived' , 0)->orderBy('created_at' , 'desc')->get();
        endif;
        return view('admin.client_requests.index' , compact('requests' , 'isArchived'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.client_requests.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request )
    {
        
        $data = $this->validate($request , [
//            'country_id' => 'required|exists:countries,id',
            'name'    => 'required|string|max:255',
            'phone'    => 'required',
            'income' => 'required|min:1|max:1000' , 
            'description' => 'nullable|min:1|max:100000' , 
        ]);
        // create new city
        ClientRequest::create($data);
        flash(trans('messages.created'))->success();
        return redirect()->route('admin.client_request.index' );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $request = ClientRequest::findOrFail($id);
        return view('admin.client_requests.show' , compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request = ClientRequest::findOrFail($id);
        return view('admin.client_requests.edit' , compact( 'request'));
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
        $data = $this->validate($request , [
            //            'country_id' => 'required|exists:countries,id',
                        'name'    => 'required|string|max:255',
                        'phone'    => 'required',
                        'income' => 'required|min:1|max:1000' , 
                        'description' => 'nullable|min:1|max:100000' , 
                    ]);
        $request = ClientRequest::findOrFail($id);
        $request->update($data);
        flash(trans('messages.updated'))->success();
        return redirect()->route('admin.client_request.index' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request = ClientRequest::findOrFail($id);

        $request->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('admin.client_request.index' );
    
    }

    public function changeArchived(Request $request , ClientRequest $clientRequest){
        $request->validate([
            'status' => 'required|in:1,0' ,
        ]);

        $clientRequest->update([
            'archived' => $request->status
        ]);

        flash(trans('dashboard.messages.' . ($request->status == 1 ? 'archived_success' : 'unarchived_success')))->success();
        return redirect(route('admin.client_request.index' ) . ($request->status == 1 ? '?is_archived=0' : '?is_archived=1'));
    }
}
