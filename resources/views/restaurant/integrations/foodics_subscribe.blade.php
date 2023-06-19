@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add')
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
                    <h1> {{app()->getLocale() == 'ar' ? 'الارتباط والتكامل' : 'Link and integration'}} </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('RestaurantIntegration')}}">
                                {{app()->getLocale() == 'ar' ? 'الارتباط والتكامل' : 'Link and integration'}}
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
                            <h3 class="card-title"> استخدام خاصيه فوودكس في مطعمك </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('foodics_subscription_submit' , $restaurant->id)}}" method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> حدد الفرع الذي تريد أستخدامه في فودكس </label>
                                    <select name="branch_id" id="branch_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($branches as $branch)
                                            <option value="{{$branch->id}}">
                                                {{app()->getLocale() == 'ar' ? $branch->name_ar : $branch->name_en}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('branch_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.payment_method') </label>
                                    <select name="payment_method" class="form-control" onchange="showDiv(this)"
                                            required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="bank"> @lang('messages.bank_transfer') </option>
                                        <option value="online"> @lang('messages.online') </option>
                                    </select>
                                    @if ($errors->has('payment_method'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('payment_method') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group" id="hidden_bank_div" style="display: none;">
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
                                <div class="form-group" id="hidden_div" style="display: none;">
                                    <label class="control-label"> @lang('messages.payment_type') </label>
                                    <select name="payment_type" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="visa"> @lang('messages.visa') </option>
                                        <option value="mada"> @lang('messages.mada') </option>
                                        <option value="apple_pay"> @lang('messages.apple_pay') </option>
                                    </select>
                                    @if ($errors->has('payment_type'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('payment_type') }}</strong>
                                        </span>
                                    @endif
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
    <script>
        function showDiv(element) {
            if (element.value == 'online') {
                document.getElementById('hidden_div').style.display = element.value == 'online' ? 'block' : 'none';
                document.getElementById('hidden_bank_div').style.display ='none';
            } else if (element.value == 'bank') {
                document.getElementById('hidden_bank_div').style.display = element.value == 'bank' ? 'block' : 'none';
                document.getElementById('hidden_div').style.display ='none';
            }
        }
    </script>
@endsection
