<div class="card-body">
    <h4 class="text-center">{{$task->title}}</h4>
    <form action="{{route('tasks.my.changeStatus' , $task->id)}}" method="POST">
        @csrf
    <div class="row">

        {{-- status --}}
        <div class="form-group col-lg-12">
            <label class="control-label"> @lang('dashboard.task_status') 
            <span style="color:red;">*</span>
            </label>
            <select name="status" id="status" class="select2 form-control" required>
                <option value="" disabled selected>اختر .. </option>
                @if($task->status == 'pending')
                <option value="in_progress" {{$task->status == 'in_progress' ? 'selected' : ''}}>{{ trans('dashboard._task_status.in_progress') }}</option>
                @endif
                @if(in_array($task->status , ['in_progress' , 'pending']))
                <option value="completed" {{$task->status == 'completed' ? 'selected' : ''}}>{{ trans('dashboard._task_status.completed') }}</option>
                @endif
            </select>
        </div>

        <div class="form-group col-lg-12" id="hours_count" style="display:none;">
            <label class="control-label"> @lang('dashboard.entry.hours_count') <span style="color:red;">*</span></label>
            <input  type="number" name="hours_count" class="form-control" value="{{$task->hours_count}}" placeholder="@lang('dashboard.entry.hours_count')">
        </div>

        <div class="form-group col-lg-12" style="display:none;" id="worked_at">
            <label class="control-label"> @lang('dashboard.entry.worked_at') </label>
            <input  type="datetime-local" name="worked_at" class="form-control" value="{{empty($task->worked_at) ? date('Y-m-d H:i') : date('Y-m-d H:i' , strtotime($task->worked_at))}}" placeholder="@lang('dashboard.entry.worked_at')">
        </div>
    </div>
        <div class="buton text-center" style="margin-top:30px;">
            <button class="btn btn-primary" type="submit">{{ trans('dashboard.save') }}</button>
            <button class="btn btn-secondary" type="button" data-dismiss="modal">{{ trans('dashboard.close') }}</button>
        </div>
    </form>
</div>