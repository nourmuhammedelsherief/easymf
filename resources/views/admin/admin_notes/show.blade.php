<div class="card-body">
    <div class="row">
        <div class="form-group col-lg-12">
            <label class="control-label"> @lang('dashboard.entry.name') </label>
            <input disabled type="text" name="title" class="form-control" value="{{$task->title}}" placeholder="@lang('dashboard.entry.title')">
            @if ($errors->has('title'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('title') }}</strong>
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

       
        {{-- description --}}
        <div class="form-group col-md-12">
            <label class="control-label"> @lang('dashboard.entry.description') <span style="color:red;">*</span></label>
            <textarea disabled name="description" rows="10" type="text" class="form-control" placeholder="@lang('dashboard.entry.description')" required>{{$task->description}}</textarea>
            @if ($errors->has('description'))
                <span class="help-block">
                    <strong style="color: red;">{{ $errors->first('description') }}</strong>
                </span>
            @endif
        </div>
        
    </div>
</div>