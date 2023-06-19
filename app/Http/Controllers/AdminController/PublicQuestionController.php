<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\PublicQuestion;
use Illuminate\Http\Request;

class PublicQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $public_questions = PublicQuestion::orderBy('id' , 'desc')->get();
        return view('admin.public_questions.index' , compact('public_questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.public_questions.create');
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
            'question' => 'required|string',
            'answer'   => 'required|string',
            'question_en' => 'required|string',
            'answer_en'   => 'required|string',
            
        ]);
        // create new public questions
        PublicQuestion::create([
            'question' => $request->question,
            'answer'   => $request->answer,
            'question_en' => $request->question_en,
            'answer_en'   => $request->answer_en,
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('public_questions.index');
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
        $question = PublicQuestion::findOrFail($id);
        return view('admin.public_questions.edit' , compact('question'));
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
        $question = PublicQuestion::findOrFail($id);
        $this->validate($request , [
            'question' => 'required|string',
            'answer'   => 'required|string',
            'question_en' => 'required|string',
            'answer_en'   => 'required|string',
        ]);
        $question->update([
            'question' => $request->question,
            'answer'   => $request->answer,
            'question_en' => $request->question_en,
            'answer_en'   => $request->answer_en,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('public_questions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $question = PublicQuestion::findOrFail($id);
        $question->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('public_questions.index');
    }
}
