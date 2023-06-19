@extends($admin.'.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.renewSubscription')
@endsection

@section('styles')

@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>  @lang('messages.renewSubscription') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.renewSubscription')
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
                            <h3 class="card-title"> @lang('messages.renewSubscription') </h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        @php
                            if ($admin == 'admin'):
                            $route = 'renewSubscriptionPostAdmin';
                            else:
                            $route = 'renewSubscriptionPost';
                            endif;
                        @endphp
                        <form role="form" action="{{route($route , [$user->id , $admin])}}" method="get"
                              enctype="multipart/form-data">
                            <input type='hidden' name='_token' value='{{Session::token()}}'>

                            <div class="card-body">
                                <?php $check_subscription = \App\Models\Subscription::whereRestaurantId($user->id)->where('type', 'restaurant')->first(); ?>

                                @if($check_subscription->end_at > Carbon\Carbon::now())
                                    <input type="hidden" name="package_id" value="1">
                                    <input type="hidden" name="payment" value="true">
                                @else
                                    {{--                                    <div class="form-group">--}}
                                    {{--                                        <label class="control-label"> @lang('messages.packages') </label>--}}
                                    {{--                                        <select name="package_id" class="form-control" required>--}}
                                    {{--                                            <option disabled selected> @lang('messages.choose_one') </option>--}}
                                    {{--                                            @foreach($packages as $package)--}}
                                    {{--                                                <?php--}}
                                    {{--                                                $check_price = \App\Models\CountryPackage::whereCountry_id($user->country_id)--}}
                                    {{--                                                    ->wherePackageId($package->id)--}}
                                    {{--                                                    ->first();--}}
                                    {{--                                                if ($check_price == null) {--}}
                                    {{--                                                    $package_actual_price = \App\Models\Package::find($package->id)->price;--}}
                                    {{--                                                } else {--}}
                                    {{--                                                    $package_actual_price = $check_price->price;--}}
                                    {{--                                                }--}}
                                    {{--                                                ?>--}}
                                    {{--                                                <option--}}
                                    {{--                                                    value="{{$package->id}}" {{$user->subscription->package_id == $package->id ? 'selected' : ''}}>--}}
                                    {{--                                                    @if(app()->getLocale() == 'ar')--}}
                                    {{--                                                        {{$package->name_ar }}--}}
                                    {{--                                                        (--}}
                                    {{--                                                        {{ $package_actual_price }} {{Auth::guard('restaurant')->user()->country->currency_ar}}--}}
                                    {{--                                                        )--}}
                                    {{--                                                    @else--}}
                                    {{--                                                        {{$package->name_en}}--}}
                                    {{--                                                        (--}}
                                    {{--                                                        {{ $package_actual_price }} {{Auth::guard('restaurant')->user()->country->currency_en}}--}}
                                    {{--                                                        )--}}
                                    {{--                                                    @endif--}}
                                    {{--                                                </option>--}}
                                    {{--                                            @endforeach--}}
                                    {{--                                        </select>--}}
                                    {{--                                        @if ($errors->has('package_id'))--}}
                                    {{--                                            <span class="help-block">--}}
                                    {{--                                            <strong style="color: red;">{{ $errors->first('package_id') }}</strong>--}}
                                    {{--                                        </span>--}}
                                    {{--                                        @endif--}}
                                    {{--                                    </div>--}}
                                    <input type="hidden" name="payment" value="false">
                                @endif

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
