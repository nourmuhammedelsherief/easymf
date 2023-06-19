@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.balance_transfer')
@endsection

@section('style')
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/select2-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/bootstrap-fileinput.css') }}">
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.balance_transfer') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('BalanceTransfer')}}">
                                @lang('messages.balance_transfer')
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
                            <h3 class="card-title">@lang('messages.balance_transfer') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('storeBalanceTransfer')}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.marketer_name') </label>
                                    <select name="marketer_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($marketers as $marketer)
                                            <option value="{{$marketer->id}}"> {{$marketer->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('marketer_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('marketer_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.amount') </label>
                                    <input name="amount" type="number" class="form-control" value="{{old('amount')}}"
                                           placeholder="@lang('messages.amount')">
                                    @if ($errors->has('amount'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('amount') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group ">
                                    <label class="control-label col-md-3"> @lang('messages.transfer_photo') </label>
                                    <div class="col-md-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px; border: 1px solid black;">
                                            </div>
                                            <div>
                                                <span class="btn red btn-outline btn-file">
                                                    <span class="fileinput-new btn btn-info"> @lang('messages.choose_photo') </span>
                                                    <span class="fileinput-exists btn btn-primary"> @lang('messages.change') </span>
                                                    <input type="file" name="transfer_photo"> </span>
                                                <a href="javascript:;" class="btn btn-danger fileinput-exists" data-dismiss="fileinput"> @lang('messages.remove') </a>
                                            </div>
                                        </div>
                                        @if ($errors->has('transfer_photo'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('transfer_photo') }}</strong>
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
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>

@endsection
