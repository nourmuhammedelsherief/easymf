@extends('restaurant.lteLayout.master')

@section('title')
     @lang('messages.copy_branch_menu')
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.copy_branch_menu') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('branches.index')}}">
                                @lang('messages.copy_branch_menu')
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
                @include('flash::message')
                <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> @lang('messages.copy_branch_menu') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('copyBranchMenuPost')}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.branch_from') </label>
                                    <select name="branch_id_from" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    {{$branch->name_ar}}
                                                @else
                                                    {{$branch->name_en}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('branch_id_from'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_id_from') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.branch_to') </label>
                                    <select name="branch_id_to" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($branches as $branch)
                                            @if($branch->foodics_status == 'false')
                                                <option value="{{$branch->id}}">
                                                    @if(app()->getLocale() == 'ar')
                                                        {{$branch->name_ar}}
                                                    @else
                                                        {{$branch->name_en}}
                                                    @endif
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @if ($errors->has('branch_id_to'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_id_to') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">@lang('messages.confirm')</button>
                                </div>

                        </form>
                    </div>

                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
@endsection
