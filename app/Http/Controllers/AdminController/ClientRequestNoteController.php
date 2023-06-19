<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\ClientRequest;
use App\Models\ClientRequestNote;
use App\Models\Country;
use Illuminate\Http\Request;
use PHPUnit\Framework\Constraint\Count;

class ClientRequestNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( ClientRequest $clientRequest )
    {
        $notes = $clientRequest->notes()->orderBy('created_at' , 'desc')->get();
        return view('admin.client_requests.notes.index' , compact('clientRequest' , 'notes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ClientRequest $clientRequest)
    {
        
        return view('admin.client_requests.notes.create'  , compact('clientRequest'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request , ClientRequest $clientRequest )
    {
        
        $data = $this->validate($request , [
//            'country_id' => 'required|exists:countries,id',
            'description'    => 'required|string|min:1|max:100000',
        ]);
        // create new city
        $clientRequest->notes()->create($data);
        flash(trans('messages.created'))->success();
        return redirect()->route('admin.client_request.note.index' , $clientRequest->id );
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
    public function edit(ClientRequest $clientRequest, $id)
    {
        $note = $clientRequest->notes()->findOrFail($id);
        return view('admin.client_requests.notes.edit' , compact( 'clientRequest' ,'note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientRequest $clientRequest , $id)
    {
        $data = $this->validate($request , [
                        'description'    => 'required|string',

                    ]);
        $note = $clientRequest->notes()->findOrFail($id);
        $note->update($data);
        flash(trans('messages.updated'))->success();
        return redirect()->route('admin.client_request.note.index'  , $clientRequest->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientRequest $clientRequest , $id)
    {
        
        $request = $clientRequest->notes()->findOrFail($id);

        $request->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('admin.client_request.note.index' , $clientRequest->id );
    
    }
}
