@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('dashboard.services_store')
@endsection

@section('styles')

@endsection

@section('content')
    @include('flash::message')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>  @lang('dashboard.services_store') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{url('/admin/home')}}">
                                @lang('dashboard.services_store')
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
                    @if($is_new == 'true')
                        <p style="color: red;">
                            @php
                                $period = \App\Models\Setting::first()->branch_service_tentative_period;
                                $service_price = $service->price;
                                $tax = ($service_price * \App\Models\Setting::first()->tax) / 100;
                                $total = $service_price + $tax;
                            @endphp
                            @lang('messages.service_tentative_details')
                            {{$period}}
                            @lang('messages.a_day')
                            <br>
                            @lang('messages.branch_price_after_tentative')
                            {{number_format((float)$service_price, 0, '.', '')}}
                            @lang('messages.SR')
                            <br>
                            @lang('messages.tax') :
                            {{number_format((float)$tax, 0, '.', '')}}
                            @lang('messages.SR')
                            <br>
                            @lang('messages.total') :
                            {{number_format((float)$total, 0, '.', '')}}
                            @lang('messages.SR')
                        </p>
                @endif
                <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"> @lang('dashboard.services_store') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form role="form" action="{{route('restaurant.services_store.subscription' , $service->id)}}"
                              method="post"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>
                            <input type='hidden' name='is_new' value='{{$is_new}}'>

                            <div class="card-body">
                                @if($service->id == 11 and !isset($serviceSubscription->id)) 
                                <div class="text-muted text-center">للإشتراك بخدمة نقاط الولاء لابد أن تكون مشترك بأحد انواع الكاشيرات الداعمة للخدمة ( كاشير ايزي منيو . كاشير فودكس )</div>
                            @endif
                                @if($service->id != 1)
                                    @if(isset($serviceSubscription->id))
                                    {{-- <input type="hidden" name="branch_id" value="{{$serviceSubscription->branch_id}}"> --}}
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.branch') </label>
                                        <select name="branch_id" class="form-control" onchange="showDiv(this)"
                                                required readonly>
                                        <option value="{{$serviceSubscription->branch_id}}">{{$serviceSubscription->branch->name}}</option>
                                        </select>
                                    </div>
                                       
                                    @else
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.branch') </label>
                                        <select name="branch_id" class="form-control" onchange="showDiv(this)"
                                                required>
                                            <option disabled selected> @lang('messages.choose_branch') </option>
                                            @foreach($branches as $branch)
                                                @php
                                                    if($service->id == 11){ // if service is loyalty points
                                                        $check_branch = \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)
                                                        ->whereBranchId($branch->id)
                                                        ->whereIn('service_id' , [4, 10])
                                                        ->whereIn('status' , ['active' , 'tentative'])
                                                        ->first();
                                                    }else{
                                                        $check_branch = \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)
                                                        ->whereBranchId($branch->id)
                                                        ->whereNotIn('service_id' , [1 , 11])
                                                        ->whereIn('status' , ['active' , 'tentative'])
                                                        ->first();
                                                    }
                                                @endphp
                                                @if(!in_array($service->id , [11]) and (($check_branch and $check_branch->end_at <= now()->addDays(30) and  $check_branch->status != 'tentative') or ($check_branch == null)))
                                                    <option value="{{$branch->id}}">
                                                        {{app()->getLocale() == 'ar' ? $branch->name_ar: $branch->name_en}} 
                                                    </option>
                                                @elseif($service->id == 11 and isset($check_branch->id)  )
                                                    <option value="{{$branch->id}}">
                                                        {{app()->getLocale() == 'ar' ? $branch->name_ar: $branch->name_en}} 
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @if ($errors->has('branch_id'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('branch_id') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    @endif
                                @else
                                    <input type="hidden" name="branch_id"
                                           value="{{\App\Models\Branch::whereRestaurantId($restaurant->id)->whereMain('true')->whereStatus('active')->first()->id}}">
                                @endif
                         
                                @if($is_new == 'false')
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
                                    <div class="form-group">
                                        <label class="control-label"> @lang('messages.seller_code') </label>
                                        <input type="text" name="seller_code" class="form-control"
                                               value="{{old('seller_code')}}"
                                               placeholder="{{app()->getLocale() == 'ar' ? 'أذا لديك كود خصم أكتبه هنا' : 'Put Your Seller Code Here'}}">
                                        @if ($errors->has('seller_code'))
                                            <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('seller_code') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                @endif
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
    <script>
        function showDiv(element) {
            if (element.value == 'online') {
                document.getElementById('hidden_div').style.display = element.value == 'online' ? 'block' : 'none';
            } else if (element.value == 'bank') {
                document.getElementById('hidden_div').style.display = element.value == 'bank' ? 'none' : 'none';
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            $(document).on('submit', 'form', function () {
                $('button').attr('disabled', 'disabled');
            });
        });
    </script>
@endsection
