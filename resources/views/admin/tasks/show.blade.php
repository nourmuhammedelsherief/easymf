<div class="card-body">
    <div class="row">
        <div class="form-group col-lg-6">
            <label class="control-label"> @lang('dashboard.entry.category') 
                {{-- <span style="color:red;">*</span> --}}
            </label>
            <select disabled name="category_id" id="category_id" class="select2 form-control" required>
                <option value="" disabled selected>اختر .. </option>
                @foreach ($categories as $item)
                    <option value="{{$item->id}}" {{$task->category_id == $item->id ? 'selected' : ''}}>{{$item->name}}</option> 
                @endforeach
            </select>
            @if ($errors->has('category_id'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('category_id') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group col-lg-6">
            <label class="control-label"> @lang('dashboard.entry.employee') 
                {{-- <span style="color:red;">*</span> --}}
            </label>
            <select disabled name="employee_id" id="employee_id" class="select2 form-control" required>
                <option value="" disabled selected>اختر .. </option>
                @foreach ($employees as $item)
                    <option value="{{$item->id}}" {{$task->employee_id == $item->id ? 'selected' : ''}}>{{$item->name}} ({{trans('dashboard._role.' . $item->role)}})</option> 
                @endforeach
            </select>
            @if ($errors->has('employee_id'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('employee_id') }}</strong>
                </span>
            @endif
        </div>
        {{-- status --}}
        <div class="form-group col-lg-6">
            <label class="control-label"> @lang('dashboard.task_status') 
            {{-- <span style="color:red;">*</span> --}}
            </label>
            <select disabled name="status" id="status" class="select2 form-control" required>
                <option value="" disabled selected>اختر .. </option>
                <option value="pending" {{$task->status == 'pending' ? 'selected' : ''}}>{{ trans('dashboard._task_status.pending') }}</option>
                <option value="in_progress" {{$task->status == 'in_progress' ? 'selected' : ''}}>{{ trans('dashboard._task_status.in_progress') }}</option>
                <option value="completed" {{$task->status == 'completed' ? 'selected' : ''}}>{{ trans('dashboard._task_status.completed') }}</option>
            </select>
            @if ($errors->has('status'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('status') }}</strong>
                </span>
            @endif
        </div>
        {{-- priority --}}
        <div class="form-group col-lg-6">
            <label class="control-label"> @lang('dashboard.entry.priority') 
            {{-- <span style="color:red;">*</span> --}}
            </label>
            <select disabled name="priority" id="priority" class="select2 form-control" >
                
                <option value="low" {{$task->priority == 'low' ? 'selected' : ''}}>{{ trans('dashboard._task_priority.low') }}</option>
                <option value="medium" {{$task->priority == 'medium' ? 'selected' : ''}}>{{ trans('dashboard._task_priority.medium') }}</option>
                <option value="high" {{$task->priority == 'high' ? 'selected' : ''}}>{{ trans('dashboard._task_priority.high') }}</option>
            </select>
            @if ($errors->has('priority'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('status') }}</strong>
                </span>
            @endif
        </div>

      

        <div class="form-group col-lg-6">
            <label class="control-label"> @lang('dashboard.entry.hours_count') </label>
            <input disabled type="number" name="hours_count" class="form-control" value="{{$task->hours_count}}" placeholder="@lang('dashboard.entry.hours_count')">
            @if ($errors->has('hours_count'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('hours_count') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group col-lg-6">
            <label class="control-label"> @lang('dashboard.entry.worked_at') </label>
            <input disabled type="text" name="worked_at" class="form-control" value="{{empty($task->worked_at) ? null : date('Y-m-d h:i A' , strtotime($task->worked_at))}}" placeholder="@lang('dashboard.entry.worked_at')">
            @if ($errors->has('worked_at'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('worked_at') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group col-lg-6">
            <label class="control-label"> @lang('dashboard.entry.created_at') </label>
            <input disabled type="text" name="created_at" class="form-control" value="{{empty($task->created_at) ? null : date('Y-m-d h:i A' , strtotime($task->created_at))}}" placeholder="@lang('dashboard.entry.created_at')">
            @if ($errors->has('created_at'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('created_at') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group col-lg-6">
            <label class="control-label"> @lang('dashboard.entry.updated_at') </label>
            <input disabled type="text" name="updated_at" class="form-control" value="{{empty($task->updated_at) ? null : date('Y-m-d h:i A' , strtotime($task->updated_at))}}" placeholder="@lang('dashboard.entry.updated_at')">
            @if ($errors->has('updated_at'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('updated_at') }}</strong>
                </span>
            @endif
        </div>

        <div class="form-group col-lg-6">
            <label class="control-label"> @lang('dashboard.admin') </label>
            <input disabled type="text" name="admin_name" class="form-control" value="{{$task->admin_name}}" >
            
        </div>
        {{-- description --}}
        <div class="form-group col-md-12">
            <label class="control-label"> @lang('dashboard.entry.description') <span style="color:red;">*</span></label>
            <textarea disabled name="description" type="text" class="form-control" placeholder="@lang('dashboard.entry.description')" required>{{$task->description}}</textarea>
            @if ($errors->has('description'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
        
    </div>
</div>