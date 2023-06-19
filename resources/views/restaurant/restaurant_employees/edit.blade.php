@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.edit') @lang('messages.restaurant_employees')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.css')}}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{--    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />--}}
@endsection


@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.edit') @lang('messages.restaurant_employees') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('restaurant_employees.index')}}">
                                @lang('messages.restaurant_employees')
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
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">@lang('messages.edit') @lang('messages.restaurant_employees') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" id="post-form" action="{{route('restaurant_employees.update' , $employee->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.permissions') </label>
                                    <select name="permission_id[]" class="select2 form-control" multiple required>
                                        @foreach($permissions as $permission)
                                            <option value="{{$permission->id}}" {{\App\Models\RestaurantPermission::whereRestaurantId($employee->id)->wherePermissionId($permission->id)->first() != null ? 'selected' : ''}}>
                                                {{app()->getLocale() == 'ar' ? $permission->name_ar:$permission->name_en}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('permission_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('permission_id') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.name') </label>
                                    <input name="name" type="text" class="form-control"
                                           value="{{$employee->name}}" placeholder="@lang('messages.name')">
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.email') </label>
                                    <input name="email" type="email" class="form-control"
                                           value="{{$employee->email}}" placeholder="@lang('messages.email')">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.password') </label>
                                    <input name="password" type="password" class="form-control"
                                           value="" placeholder="@lang('messages.password')">
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.password_confirmation') </label>
                                    <input name="password_confirmation" type="password" class="form-control"
                                           value="" placeholder="@lang('messages.password_confirmation')">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>

                            </div>
                            <!-- /.card-body -->
                            @method('PUT')
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>

        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
    <script src="{{asset('plugins/select2/js/select2.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });
    </script>

@endsection
