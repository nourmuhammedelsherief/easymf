<?php

namespace App\Http\Controllers\AdminController;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Category;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskEmployee;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
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
        $tasks = Task::with(['employee' , 'category' ])->orderBy('created_at' , 'desc');
        if(!empty($request->status)):
            $tasks  = $tasks->where('status' , $request->status);
        endif;
        if(!empty($request->employee_id)):
            $tasks  = $tasks->where('employee_id' , $request->employee_id);
        endif;
        if(!empty($request->category_id)):
            $tasks  = $tasks->where('category_id' , $request->category_id);
        endif;
        if(!empty($request->title)):
            $tasks  = $tasks->where('title' , 'like' , '%' .$request->title . '%');
        endif;
        if(!empty($request->created_at)):
            $tasks  = $tasks->where('created_at' , 'like' , '%' .$request->created_at . '%');
        endif;
        $myTask = false;
        if($request->segment(2) == 'my-tasks'):
            $tasks = $tasks->where('employee_id' , auth('admin')->id());
            $myTask = true;
        endif;
        $tasks = $tasks->paginate(500);
        
        $employees = Admin::where('status' , 'true')->orderBy('name' , 'asc')->get();
        $categories  = TaskCategory::orderBy('name_ar' )->get();
        
        return  view('admin.tasks.index' , compact('tasks' , 'filter' , 'categories' , 'employees' , 'myTask'));
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
        return  view('admin.tasks.create' , compact('employees' , 'categories'));
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
            'category_id' => 'required|integer', 
            'employee_id' => 'required|integer', 
            'status' => 'required|in:pending,in_progress,completed', 
            'priority' => 'nullable|in:low,medium,high' , 
            'hours_count' => 'nullable|integer|min:0' , 
            'description' => 'required|min:1' , 
            'worked_at' => 'nullable|date' ,
            'title' =>'required|min:1|max:190'  , 
        ]);
        if(!$category = TaskCategory::find($request->category_id)):
            throw ValidationException::withMessages([
                'category_id' => trans('dashboard.errors.category_not_found') , 
            ]);
        endif;
        if(!$employee = Admin::where('status' , 'true')->find($request->employee_id)):
            throw ValidationException::withMessages([
                'employee_id' => trans('dashboard.errors.employee_not_found') , 
            ]);
        endif;
        $data['admin_id'] = auth('admin')->id();
        $data['admin_name'] = auth('admin')->user()->name;
        // create new category
        Task::create($data);

        flash(trans('messages.created'))->success();
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        $employees = Admin::where('status' , 'true')->orderBy('name' , 'asc')->get();
        $categories  = TaskCategory::orderBy('name_ar' )->get();
        $data = view('admin.tasks.show' , compact('employees' , 'categories' , 'task'))->render();
        return response([
            'status' => true , 
            'title' => $task->title , 
            'data' => $data,
        ]);
    }
    public function changeStatus(Request $request , $id)
    {
        $task = Task::findOrFail($id);
        if($request->method() == 'POST'):
            $data = $request->validate([
                'status' => 'required|in:in_progress,completed' , 
                'hours_count' => 'required_if:status,completed|nullable|numeric|min:0.1' , 
                'worked_at' => 'nullable|date' , 
            ]);
            $task->update($data);
            if(auth()->user()->role == 'admin'):
                $url = route('tasks.index');
            else:
                $url = route('tasks.my');
            endif;
            flash(trans('messages.updated'))->success();
            return redirect()->back();
        endif;
        $data = view('admin.tasks.change_status' , compact('task'))->render();
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
        $task = Task::findOrFail($id);
        $employees = Admin::where('status' , 'true')->orderBy('name' , 'asc')->get();
        $categories  = TaskCategory::orderBy('name_ar' )->get();
        return  view('admin.tasks.edit' , compact('employees' , 'categories' , 'task'));
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
        $task = Task::findOrFail($id);
        $data = $request->validate( [
            'category_id' => 'required|integer', 
            'employee_id' => 'required|integer', 
            'status' => 'required|in:pending,in_progress,completed', 
            'priority' => 'nullable|in:low,medium,high' , 
            'hours_count' => 'nullable|integer|min:0' , 
            'description' => 'required|min:1' , 
            'worked_at' => 'nullable|date' ,
            'title' => 'required|min:1|max:190' , 
        ]);
        if(!$category = TaskCategory::find($request->category_id)):
            throw ValidationException::withMessages([
                'category_id' => trans('dashboard.errors.category_not_found') , 
            ]);
        endif;
        if(!$employee = Admin::where('status' , 'true')->orWhere('id' , $task->employee_id)->find($request->employee_id)):
            throw ValidationException::withMessages([
                'employee_id' => trans('dashboard.errors.employee_not_found') , 
            ]);
        endif;
        // create new category
        $task->update($data);

        flash(trans('messages.updated'))->success();
        return redirect()->route('tasks.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Task::findOrFail($id);
        
        $category->delete();
        flash(trans('messages.deleted'))->success();
        return redirect()->route('tasks.index');
    }
}
