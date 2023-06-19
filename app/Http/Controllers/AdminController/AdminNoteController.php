<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\AdminMemo;
use App\Models\TaskCategory;
use App\Models\TaskEmployee;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $filter = $request->validate([
            'status' => 'nullable|in:pending,in_progress,completed' , 
            'employee_id' => 'nullable|integer' , 
            'category_id' => 'nullable|integer' , 
            'title' => 'nullable|min:1' , 
            'created_at' => 'nullable|min:1'
        ]);
        $tasks = AdminMemo::where('employee_id' , auth('admin')->id())->orderBy('created_at' , 'desc');
 
        if(!empty($request->title)):
            $tasks  = $tasks->where('title' , 'like' , '%' .$request->title . '%');
        endif;
        if(!empty($request->created_at)):
            $tasks  = $tasks->where('created_at' , 'like' , '%' .$request->created_at . '%');
        endif;
        $myTask = false;
        
        $tasks = $tasks->paginate(500);
        
        
        return  view('admin.admin_notes.index' , compact('tasks' , 'filter'  , 'myTask'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $employees = Admin::where('status' , 'true')->orderBy('name' , 'asc')->get();
        $categories  = TaskCategory::orderBy('name_ar' )->get();
        return  view('admin.admin_notes.create' , compact('employees' , 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate( [
            'description' => 'required|min:1' , 
            // 'worked_at' => 'nullable|date' ,
            'title' =>'required|min:1|max:190'  , 
        ]);
        $data['employee_id'] = auth('admin')->id();
        // $data['admin_id'] = auth('admin')->id();
        // $data['admin_name'] = auth('admin')->user()->name;
        $data['type'] = 'note';
        // create new category
        AdminMemo::create($data);

        flash(trans('messages.created'))->success();
        return redirect()->route('my-notes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = AdminMemo::findOrFail($id);
        $employees = Admin::where('status' , 'true')->orderBy('name' , 'asc')->get();
        $categories  = TaskCategory::orderBy('name_ar' )->get();
        $data = view('admin.admin_notes.show' , compact('employees' , 'categories' , 'task'))->render();
        return response([
            'status' => true , 
            'title' => $task->title , 
            'data' => $data,
        ]);
    }
  
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = AdminMemo::findOrFail($id);
        $employees = Admin::where('status' , 'true')->orderBy('name' , 'asc')->get();
        $categories  = TaskCategory::orderBy('name_ar' )->get();
        return  view('admin.admin_notes.edit' , compact('employees' , 'categories' , 'task'));
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
        $task = AdminMemo::findOrFail($id);
        $data = $request->validate( [
            'description' => 'required|min:1' , 
            // 'worked_at' => 'nullable|date' ,
            'title' => 'required|min:1|max:190' , 
        ]);
    

        // create new category
        $task->update($data);

        flash(trans('messages.updated'))->success();
        return redirect()->route('my-notes.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = AdminMemo::findOrFail($id);
        
        $category->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('my-notes.index');
    }
}
