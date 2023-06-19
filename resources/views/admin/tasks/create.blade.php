@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('dashboard.tasks')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('dashboard.tasks') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('tasks.index')}}">
                                @lang('dashboard.tasks')
                            </a>
                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.add') @lang('dashboard.tasks') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('tasks.store')}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.name') <span style="color:red;">*</span></label>
                                        <input type="text" name="title" value="{{old('title')}}" class="form-control" required>
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.category') <span style="color:red;">*</span></label>
                                        <select name="category_id" id="category_id" class="select2 form-control" required>
                                            <option value="" disabled selected>اختر .. </option>
                                            @foreach ($categories as $item)
                                                <option value="{{$item->id}}" {{old('category_id') == $item->id ? 'selected' : ''}}>{{$item->name}}</option> 
                                            @endforeach
                                        </select>
                                        @if ($errors->has('category_id'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('category_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.employee') <span style="color:red;">*</span></label>
                                        <select name="employee_id" id="employee_id" class="select2 form-control" required>
                                            <option value="" disabled selected>اختر .. </option>
                                            @foreach ($employees as $item)
                                                <option value="{{$item->id}}" {{old('employee_id') == $item->id ? 'selected' : ''}}>{{$item->name}} ({{trans('dashboard._role.' . $item->role)}})</option> 
                                            @endforeach
                                        </select>
                                        @if ($errors->has('employee_id'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('employee_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    {{-- status --}}
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.task_status') 
                                        <span style="color:red;">*</span>
                                        </label>
                                        <select name="status" id="status" class="select2 form-control" required>
                                            <option value="" disabled selected>اختر .. </option>
                                            <option value="pending" {{old('status') == 'pending' ? 'selected' : ''}}>{{ trans('dashboard._task_status.pending') }}</option>
                                            <option value="in_progress" {{old('status') == 'in_progress' ? 'selected' : ''}}>{{ trans('dashboard._task_status.in_progress') }}</option>
                                            <option value="completed" {{old('status') == 'completed' ? 'selected' : ''}}>{{ trans('dashboard._task_status.completed') }}</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('status') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    {{-- priority --}}
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.priority') 
                                        {{-- <span style="color:red;">*</span> --}}
                                        </label>
                                        <select name="priority" id="priority" class="select2 form-control" >
                                            
                                            <option value="low" {{old('priority') == 'low' ? 'selected' : ''}}>{{ trans('dashboard._task_priority.low') }}</option>
                                            <option value="medium" {{old('priority') == 'medium' ? 'selected' : ''}}>{{ trans('dashboard._task_priority.medium') }}</option>
                                            <option value="high" {{old('priority') == 'high' ? 'selected' : ''}}>{{ trans('dashboard._task_priority.high') }}</option>
                                        </select>
                                        @if ($errors->has('priority'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('status') }}</strong>
                                            </span>
                                        @endif
                                    </div>
    
                                  
    
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.hours_count') </label>
                                        <input type="number" name="hours_count" class="form-control" value="{{old('hours_count')}}" placeholder="@lang('dashboard.entry.hours_count')">
                                        @if ($errors->has('hours_count'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('hours_count') }}</strong>
                                            </span>
                                        @endif
                                    </div>
    
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.worked_at') </label>
                                        <input type="datetime-local" name="worked_at" class="form-control" value="{{old('worked_at')}}" placeholder="@lang('dashboard.entry.worked_at')">
                                        @if ($errors->has('worked_at'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('worked_at') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    {{-- description --}}
                                    <div class="form-group col-md-12">
                                        <label class="control-label"> @lang('dashboard.entry.description') <span style="color:red;">*</span></label>
                                        <textarea name="description" type="text" class="form-control" placeholder="@lang('dashboard.entry.description')" required>{{old('description')}}</textarea>
                                        @if ($errors->has('description'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
@endsection
@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $(document).on('submit', 'form', function() {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
@endsection
