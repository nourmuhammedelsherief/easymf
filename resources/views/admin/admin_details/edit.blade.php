@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('dashboard.admin_details')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('dashboard.admin_details') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('admin_details.index')}}">
                                @lang('dashboard.admin_details')
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
                            <h3 class="card-title">@lang('messages.edit') @lang('dashboard.admin_details') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('admin_details.update' , $task->id)}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>
                            <input type="hidden" name="_method" value="PUT">
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.name') <span style="color:red;">*</span></label>
                                        <input type="text" name="title" value="{{$task->title}}" class="form-control" required>
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                   
                                    <div class="form-group col-md-6">
                                        <label class="control-label"> @lang('dashboard.entry.employee') <span style="color:red;">*</span></label>
                                        <select name="employee_id" id="employee_id" class="select2 form-control" required>
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
                                
                                    {{-- description --}}
                                    <div class="form-group col-md-12">
                                        <label class="control-label"> @lang('dashboard.entry.description') <span style="color:red;">*</span></label>
                                        <textarea name="description" type="text" class="form-control" placeholder="@lang('dashboard.entry.description')" required>{{$task->description}}</textarea>
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
