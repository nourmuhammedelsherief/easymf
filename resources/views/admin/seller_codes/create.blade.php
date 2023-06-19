@extends('admin.lteLayout.master')

@section('title')
    @lang('messages.add') @lang('messages.seller_codes')
@endsection

@push('styles')
    <style>
        .display-none{
            display: none;
        }
    </style>
@endpush

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1> @lang('messages.add') @lang('messages.seller_codes') </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">@lang('messages.control_panel')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{url('/admin/marketers')}}">
                                @lang('messages.seller_codes')
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
                            <h3 class="card-title">@lang('messages.add') @lang('messages.seller_codes') </h3>
                        </div>
                        <!-- /.card-header -->
                        @foreach ($errors->all() as $item)
                            <p class="text-danger">{{$item}}</p>
                    @endforeach
                    <!-- form start -->
                        <form role="form" action="{{route('seller_codes.store')}}" method="post" enctype="multipart/form-data">
                            <input type = 'hidden' name = '_token' value = '{{Session::token()}}'>

                            <div class="card-body">
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.marketer') </label>
                                    <select name="marketer_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($marketers as $marketer)
                                            <option value="{{$marketer->id}}"> {{$marketer->name}} </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('marketer_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('marketer_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.country') </label>
                                    <select name="country_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach($countries as $country)
                                            <option value="{{$country->id}}">
                                                {{app()->getLocale() == 'ar' ? $country->name_ar : $country->name_en}}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('country_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('country_id') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                {{-- used_type --}}
                                <div class="form-group">
                                    <label class="control-label"> {{trans('dashboard.used_type')}} </label>
                                    <select name="used_type" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="code"> {{trans('dashboard.code')}} </option>
                                        <option value="url"> {{trans('dashboard.url')}} </option>
                                    </select>
                                    @if ($errors->has('used_type'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('used_type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- url --}}
                                <div class="form-group display-none">
                                    <label class="control-label"> @lang('dashboard.url') </label>
                                    <input name="custom_url" type="text" class="form-control" value="{{old('custom_url')}}" placeholder="@lang('dashboard.url')">
                                    <p class="text-muted">مثال : {{url('restaurants-registration/xxxxxxxx')}}</p>
                                    @if ($errors->has('custom_url'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('custom_url') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- package_id --}}
                                <div class="form-group display-none">
                                    <label class="control-label"> @lang('dashboard.package') </label>
                                    <select name="package_id" id="package_id" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        @foreach ($packages as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('package_id'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('package_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- code --}}
                                <div class="form-group display-none">
                                    <label class="control-label"> @lang('messages.seller_name') </label>
                                    <input name="seller_name" type="text" class="form-control" value="{{old('seller_name')}}" placeholder="@lang('messages.seller_name')">
                                    @if ($errors->has('seller_name'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('seller_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                {{-- type --}}
                                <div class="form-group display-none">
                                    <label class="control-label"> {{app()->getLocale() == 'ar' ? 'تفعيل كود الخصم ل ' : 'activate seller code for'}} </label>
                                    <select name="type" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="restaurant"> {{app()->getLocale() == 'ar' ? 'المطاعم': 'Restaurants'}} </option>
                                        <option value="branch"> {{app()->getLocale() == 'ar' ? 'الفروع' : 'Branches'}} </option>
                                        <option value="service"> {{app()->getLocale() == 'ar' ? 'الخدمات' : 'Services'}} </option>
                                        <option value="both"> {{app()->getLocale() == 'ar' ? 'الجميع' : 'All'}} </option>
                                    </select>
                                    @if ($errors->has('type'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('type') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <!--<div class="form-group">-->
                            <!--    <label class="control-label"> {{app()->getLocale() == 'ar' ? 'حاله كود الخصم' : 'seller code status'}} </label>-->
                                <!--    <select name="discount" class="form-control" required>-->
                            <!--        <option disabled selected> @lang('messages.choose_one') </option>-->
                            <!--        <option value="subscription"> {{app()->getLocale() == 'ar' ? 'أشتراك جديد': 'New Subscription'}} </option>-->
                            <!--        <option value="renew"> {{app()->getLocale() == 'ar' ? 'تجديد' : 'Renew Subscription'}} </option>-->
                                <!--    </select>-->
                            <!--    @if ($errors->has('discount'))-->
                                <!--        <span class="help-block">-->
                            <!--            <strong style="color: red;">{{ $errors->first('discount') }}</strong>-->
                                <!--        </span>-->
                                <!--    @endif-->
                                <!--</div>-->

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.permanent') </label>
                                    <select name="permanent" class="form-control" required>
                                        <option disabled selected> @lang('messages.choose_one') </option>
                                        <option value="true"> @lang('messages.yes') </option>
                                        <option value="false"> @lang('messages.no') </option>
                                    </select>
                                    @if ($errors->has('permanent'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('permanent') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.percentage') </label>
                                    <input name="percentage" type="number" class="form-control" value="{{old('percentage')}}" placeholder="@lang('messages.percentage')">
                                    @if ($errors->has('percentage'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('percentage') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.code_percentage') </label>
                                    <input name="code_percentage" type="number" class="form-control" value="{{old('code_percentage')}}" placeholder="@lang('messages.code_percentage')">
                                    @if ($errors->has('code_percentage'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('code_percentage') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.start_at') </label>
                                    <input name="start_at" type="date" class="form-control" value="{{old('start_at')}}" placeholder="@lang('messages.start_at')">
                                    @if ($errors->has('start_at'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('start_at') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.end_at') </label>
                                    <input name="end_at" type="date" class="form-control" value="{{old('end_at')}}" placeholder="@lang('messages.end_at')">
                                    @if ($errors->has('end_at'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('end_at') }}</strong>
                                        </span>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label class="control-label"> @lang('messages.activity') </label>
                                    <input name="active" type="radio" value="true">@lang('messages.yes')
                                    <input name="active" type="radio" value="false">@lang('messages.no')
                                    @if ($errors->has('active'))
                                        <span class="help-block">
                                            <strong style="color: red;">{{ $errors->first('active') }}</strong>
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

@push('scripts')
    <script>
        $(function(){
            $('select[name=used_type]').on('change' , function(){
                var tag = $(this);
                var sellerName = $('input[name=seller_name]');
                var customUrl = $('input[name=custom_url]');
                var both = $('select[name=type]');
                var package = $('#package_id');
                if(tag.val()== 'code'){
                    sellerName.parent().fadeIn(300);
                    both.parent().fadeIn(300);
                    customUrl.parent().fadeOut(300);
                    package.parent().fadeOut(300);
                    customUrl.prop('disabled' , true);

                    package.prop('disabled' , true);
                    sellerName.prop('disabled' , false);
                    both.prop('disabled' , false);
                }else{
                    sellerName.parent().fadeOut(300);
                    both.parent().fadeOut(300);
                    customUrl.parent().fadeIn(300);
                    package.parent().fadeIn(300);

                    both.prop('disabled' , true);
                    customUrl.prop('disabled' , false);
                    package.prop('disabled' , false);
                    sellerName.prop('disabled' , true);
                }
            });
            $('select[name=used_type]').trigger('change');
        });
    </script>
@endpush
