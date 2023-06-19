<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TaskEmployee;
use Illuminate\Http\Request;

class TaskEmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = TaskEmployee::withCount('tasks')->orderBy('created_at' , 'desc')->get();
        
        return  view('admin.task_employees.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return  view('admin.task_employees.create');
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
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            
        ]);

        // create new category
        TaskEmployee::create([
            'name'  => $request->name, 
            'job_title' => $request->job_title
        ]);
        flash(trans('messages.created'))->success();
        return redirect()->route('task_employees.index');
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
        $category = TaskEmployee::findOrFail($id);
        return  view('admin.task_employees.edit' , compact('category'));
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
        $this->validate($request , [
            'name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'status' => 'required|in:true,false'
            
        ]);
        $category = TaskEmployee::findOrFail($id);
        $category->update([
            'name'  => $request->name,
            'job_title'  => $request->job_title,
            'status' => $request->status , 
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('task_employees.index');
    }

    public function changeStatus(Request $request, $id , $status)
    {
        
        $category = TaskEmployee::findOrFail($id);
        $category->update([
            'status' => $status , 
        ]);
        flash(trans('messages.updated'))->success();
        return redirect()->route('task_employees.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = TaskEmployee::findOrFail($id);
        if ($category->tasks()->count() > 0)
        {
            flash(trans('messages.cant_deleted'))->error();
            return redirect()->route('task_employees.index');
        }
        $category->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('task_employees.index');
    }
}
