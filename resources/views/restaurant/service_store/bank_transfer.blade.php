@extends('restaurant.lteLayout.master')

@section('title')
    {{app()->getLocale() == 'ar' ? 'الحسابات البنكية': 'Bank Accounts'}}
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
                    <h1>
                        {{app()->getLocale() == 'ar' ? 'الحسابات البنكية': 'Bank Accounts'}}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="#">
                                {{app()->getLocale() == 'ar' ? 'الحسابات البنكية': 'Bank Accounts'}}
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
                <div class="col-md-9">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> {{app()->getLocale() == 'ar' ? 'الحسابات البنكية': 'Bank Accounts'}} </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form"
                              action="{{route('restaurant.services_store.subscription_bank' , $service->id)}}"
                              method="post" enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <input type="hidden" name="seller_code" value="{{$seller_code}}">
                            <input type="hidden" name="price" value="{{$price}}">
                            <input type="hidden" name="discount" value="{{$discount}}">
                            <input type="hidden" name="tax_value" value="{{$tax_value}}">
                            <input type="hidden" name="branch_id" value="{{$branch}}">
                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.bank_transfer') </label>
                                    <select name="bank_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($banks as $bank)
                                            <option value="{{$bank->id}}">
                                                @if(app()->getLocale() == 'ar')
                                                    @lang('messages.bank') : {{$bank->name_ar}} ,
                                                    @lang('messages.account_number') : {{$bank->account_number}} ,
                                                    @lang('messages.IBAN_number') : {{$bank->IBAN_number}}
                                                @else
                                                    @lang('messages.bank') : {{$bank->name_en}} ,
                                                    @lang('messages.account_number') : {{$bank->account_number}} ,
                                                    @lang('messages.IBAN_number') : {{$bank->IBAN_number}}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('bank_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('bank_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <?php
                                $currency = app()->getLocale() == 'ar' ? $user->country->currency_ar : $user->country->currency_en;
                                ?>
                                <h6> @lang('messages.amountToTransfer')
                                    <br>
                                    <span style="color: #ff224f">
                                        @lang('messages.subscription_price')
                                        (
                                        {{number_format((float)$service->price, 2, '.', '')}}
                                        {{$currency}}
                                        )
                                    </span>
                                    <br>
                                    @if($discount > 0)
                                        <span style="color: #ff224f">
                                        @lang('messages.discount')
                                        (
                                        {{$discount}}
                                            {{$currency}}
                                        )
                                    </span>
                                        <br>
                                    @endif
                                    <span style="color: #ff224f">
                                        @lang('messages.tax')
                                        (
                                        {{$tax}}
                                        %
                                        )
                                    </span>
                                    <span style="color: #ff224f">
                                        @lang('messages.amount')
                                        (
                                        {{$tax_value}}
                                        {{$currency}}
                                        )
                                    </span>
                                    <br>
                                    <span style="color: #ff224f">
                                        @lang('messages.total')
                                        (
                                        {{number_format((float)$price, 2, '.', '')}}
                                        {{$currency}}
                                        )
                                    </span>
                                </h6>
                                <h5>
                                    <a target="_blank" href="https://web.easymenu.site/bank-information">
                                        @lang('messages.banks_accounts')
                                    </a>
                                </h5>
                                <div class="form-group ">
                                    <label class="control-label col-md-3"> @lang('messages.transfer_photo') </label>
                                    <div class="col-md-9">
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <div class="fileinput-preview thumbnail" data-trigger="fileinput"
                                                 style="width: 200px; height: 150px; border: 1px solid black;">
                                            </div>
                                            <div>
                                                <span class="btn red btn-outline btn-file">
                                                    <span
                                                        class="fileinput-new btn btn-info"> @lang('messages.choose_photo') </span>
                                                    <span
                                                        class="fileinput-exists btn btn-primary"> @lang('messages.change') </span>
                                                    <input type="file" name="transfer_photo" required> </span>
                                                <a href="javascript:;" class="btn btn-danger fileinput-exists"
                                                   data-dismiss="fileinput"> @lang('messages.remove') </a>
                                            </div>
                                        </div>
                                        @if ($errors->has('transfer_photo'))
                                            <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('transfer_photo') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            <!-- /.card-body -->

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
@section('scripts')
    <script src="{{ URL::asset('admin/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/components-select2.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/bootstrap-fileinput.js') }}"></script>
@endsection
