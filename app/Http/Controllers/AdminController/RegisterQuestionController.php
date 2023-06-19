<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\RegisterAnswers;
use App\Models\RegisterQuestion;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RegisterQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $answers = RegisterAnswers::whereQuestionId(1)->get();
        return view('admin.register_questions.index' , compact('answers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = RegisterQuestion::find(1);
        return view('admin.register_questions.create' , compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $question = RegisterQuestion::findOrFail(1);
        $this->validate($request , [
            'answer',
            'answer_en' 
        ]);
        // create new answer
        RegisterAnswers::create([
            'question_id' => $question->id,
            'answer'      => $request->answer,
            'answer_en' => $request->answer_en , 
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('answers.index');
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
        $answer = RegisterAnswers::findOrFail($id);
        return view('admin.register_questions.edit' , compact('answer'));
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
        $answer = RegisterAnswers::findOrFail($id);
        $this->validate($request , [
            'answer',
            'answer_en'
        ]);
        // create new answer
        $answer->update([
            'answer'      => $request->answer,
            'answer_en'      => $request->answer_en,
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('answers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $answer = RegisterAnswers::findOrFail($id);
        $answer->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('answers.index');
    }
    public function update_question(Request $request)
    {
        $this->validate($request , [
            'question' => 'required|string' , 
            'question_en' => 'required|min:1'
        ]);
        $question = RegisterQuestion::findOrFail(1);
        $question->update([
            'question' => $request->question , 
            'question_en' => $request->question_en , 
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->back();
    }
    public function answer_restaurants($id)
    {
        $answer = RegisterAnswers::findOrFail($id);
        $restaurants = $answer->restaurants;
        return view('admin.register_questions.restaurants' , compact('restaurants' , 'answer'));
    }
}
