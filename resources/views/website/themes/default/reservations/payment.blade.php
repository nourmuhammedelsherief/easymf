@push('styles')

    {{-- <link rel="stylesheet" href="{{asset('plugins/color-calender/theme-basic.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('plugins/color-calender/theme-glass.css')}}">
@endpush
@include('website.'.session('theme_path').'silver.layout.header')


<style>
    :root {
        --rest-background-color: #ebebeb;
    }

    html {
        background-color: #FFF !important;
    }

    body {
        position: relative;
        background-color: #FFF !important;
        /* margin: 0px !important; */
    }

    a {
        color: #000;
    }

    .x-card-header {
        width: 100%;
        height: 40px;
        background-color: {{$restaurant->color == null ? '' : $restaurant->color->background}}
	
    }

    .x-card {
        min-height: 400px;
        /* padding-top: 40px; */
        border: 0 !important;
        border-radius: 0 !important;
        border-top-left-radius: 47px !important;
        border-top-right-radius: 47px !important;
        /* background-color: 
    {{$restaurant->color == null ? '' : $restaurant->color->background}} , */
    }

    .x-card .icons {
        margin: 20px 2px 10px 2px;
    }

    .icons .div-icon {
        display: inline-block;
        border-left: 1px solid #CCC;
        padding: 10px 0;
        width: calc(25% - 5px);
        height: 73px !important;
        box-sizing: border-box;
        overflow: hidden;
    }

    .icons .div-icon:last-child {
        border-left: 0;
    }

    .icons i {
        font-size: 22px;
    }

    .details {
        margin: 5%;
        background-color: #f7f7f7;
        padding: 10px;
        padding-bottom: 34px;
        /* clear: both; */
        border: 3px solid var(--rest-background-color);
        border-style: dashed;
    }

    .details .data {
        clear: both;
    }

    .details .data label {
        float: right;
    }

    .details .data p {
        float: left;
    }

    .description-content {
        position: relative;
        padding: 10px;
        border: 2px dashed var(--rest-background-color);
    }

    .description-content .title {
        position: absolute;
        top: -18px;
        right: 10px;
        border-bottom: 2px dashed var(--rest-background-color);
        background-color: #FFF;
        padding: 0 10px;
    }

    .client-note textarea {
        border: 0;
        background-color: var(--rest-background-color)
    }

    label.dashed {
        border-bottom: 2px dashed var(--rest-background-color);
    }

    .parties .list {
        max-height: 80px;
        overflow-x: scroll;
        white-space: nowrap;
    }

    .parties .list .one {
        padding: 10px 20px;
        border: 1px solid var(--rest-background-color);
        border-radius: 20px;
        display: inline-block;
        cursor: pointer;
    }

    .div-icon p {
        height: 31px;
    }

    .date-icon p {
        margin: 0;
        line-height: 1.2;
        font-size: 11px;
    }

    .payment.form-group {
        margin-top: 20px;
    }

    .payment.form-group label {
        font-size: 14px;
    }

    .footer-button {
        margin-top: 40px;
        margin-bottom: 30px;
        clear: both;
    }

    .footer-button a {
        font-size: 14px;
    }

    /* new */
    html {
        position: relative;
    }

    body {
        position: initial !important;
        margin-bottom: 50px !important;
    }

    .footer-description {
        position: absolute;
        padding: 10px 0;
        bottom: 10px;
        left: 0;
        width: 100% !important;
        text-align: center;
    }
</style>


<div class="x-card-header"></div>
<div class="x-card card mr-0 ml-0 rounded-l" style="">
    <div class="container">
        <h1 class="text-center"
            style="font-size: 1.1rem !important;margin-top: 15px;">{{ trans('messages.reservations') }}
            - {{$restaurant->name}}</h1>
        @if($errors->any())
            <p class="alert alert-danger mt-5">{{$errors->first()}}</p>
        @endif
        <div class="icons">
            <div class="div-icon text-center">
                <div class=" text-center">
                    <i class="fas fa-user-alt"></i>
                </div>
                <p>{{$reservation->people_count * ($reservation->chairs >0 ? $reservation->chairs : 1)}}  </p>

            </div>


            <div class="div-icon text-center">
                <div class=" text-center">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <p>
                    {{$reservation->date}}
                </p>
            </div>

            <div class="div-icon text-center date-icon">
                <div class=" text-center">
                    <i class="fas fa-clock" style=""></i>
                </div>
                <p>
                    @lang('dashboard.from')
                    {{$reservation->period->from_string}}<br>
                    @lang('dashboard.to')
                    {{$reservation->period->to_string}}
                </p>

            </div>

            <div class="div-icon text-center">
                <div class=" text-center">
                    <i class="fas fa-building"></i>
                </div>
                <p>
                    {{isset($reservation->table->place->id) ? $reservation->table->place->name : ''}}
                </p>
            </div>
        </div>

        <div class="details">
            {{-- <h1 class="text-center">{{ trans('messages.reservation_num') }}<br> <span>{{$reservation->id}}</span></h1> --}}
            
            @if(!empty($reservation->chairs))
            <div class="data" style="clear: both">
                <label for="">{{trans('messages.reservation_quantity')}}</label>
                <p>{{ $reservation->chairs}}</p>
            </div>
            @endif
            <div class="data" style="clear: both">
                <label for="">{{trans('messages.reservation_type')}}</label>
                <p>{{ trans('messages._reservation_type.' . $reservation->type) }}</p>
            </div>
            <div class="data">
                <label for="">
                    @lang('messages.price')
                </label>
                <p>{{$reservation->price}} {{$country->name}}</p>
            </div>

            @if($reservation->tax > 0)
                <div class="data">
                    <label for="">{{ trans('messages.tax_') }}</label>
                    <p>{{$reservation->tax}} {{$country->name}}</p>
                </div>
            @endif
            @if($restaurant->online_payment_fees > 0 and $type == 'online')
                <div class="data  " id="online_payment_fees"  style="clear: both;">
                    <label for="">{{trans('messages.online_payment_fees')}}</label>
                    <p>{{round(($restaurant->online_payment_fees * $reservation->total_price) / 100)}}</p>
                </div>
            @endif

            <div class="data" style="position: relative;">
                <label for="">@lang('messages.total')</label>
                <p style="">{{$type == 'online' ? $totalPrice : $reservation->total_price}} {{$country->name}} <br>


                </p>
                @if($restaurant->total_tax_price == 'true')
                    <span style="font-size: 0.5rem;
                        position: absolute;
                        top: 13px;
                        left: -12px;
                        width: 56px;">{{trans('messages.tax_des')}}</span>
                @endif
            </div>

        </div>
        <form id="reservationForm" action="{{route('reservation.page4' ,[ $branch->id , $reservation->id])}}"
              method="POST" enctype="multipart/form-data">
            @csrf
            @if($type == 'bank')
                <div class="form-group payment">
                    <label for="">{{ trans('messages.bank') }}</label>
                    <select name="bank_id" id="" class="form-control">
                        @foreach($banks as $bank)
                            <option value="{{$bank->id}}">
                                @if(app()->getLocale() == 'ar')
                                    @lang('messages.bank') : {{$bank->name_ar}}
                                @else
                                    @lang('messages.bank') : {{$bank->name_en}}
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <div class="description">
                    </div>
                </div>
                <div class="form-group payment">
                    <label for="">{{ trans('messages.bank_transfer') }}</label>
                    <input type="file" name="photo" id="" class="form-control" accept=".png,.jpg,.jepg">
                </div>
            @elseif($type == 'online')
                @if($restaurant->payment_company == 'myFatoourah')
                    <div class="form-group payment">
                        <label for="">{{ trans('messages.payment_way') }}</label>
                        <select name="payment_method" id="" class="form-control">
                            <option value="visa">{{ trans('messages.visa') }}</option>
                            <option value="mada">{{ trans('messages.mada') }}</option>
                            <option value="apple_pay">{{ trans('messages.apple_pay') }}</option>
                        </select>
                    </div>
                @endif
            @endif
        </form>
        <div class="footer-button text-center">
            <a href="javascript:;" class="btn btn-primary save-page">{{ trans('messages.next_step') }}</a>

            <a href="{{route('reservation.page2' , [$branch->id ,$reservation->id])}}"
               class="btn btn-secondary ">{{ trans('messages.return') }}</a>
        </div>


    </div>

</div>

<div class="footer footer-description text-center">
    {!!  trans('messages.reservation_footer') !!}
</div>


@push('scripts')
    {{-- <script src="{{asset('plugins/color-calender/bundle.min.js')}}"></script> --}}
    <script>
        var banks = {!! json_encode($banks) !!};
        var lang = "{{app()->getLocale()}}";

        $(function () {
            $('.save-page').on('click', function () {
                $('#reservationForm').submit();
            });

            $('select[name=bank_id]').on('change', function () {
                var tag = $(this);
                $.each(banks, function (key, value) {

                    if (value.id == tag.val()) {

                        tag.parent().find('.description').html('@lang('messages.bank') : ' + (lang == 'ar' ? value.name_ar : value.name_en) + ' ,<br>\
							@lang('messages.account_number') : ' + value.account_number + ' ,<br>\
							@lang('messages.IBAN_number') : ' + value.IBAN_number);
                    }
                });
            });
            $('select[name=bank_id]').trigger('change');
        });
    </script>
@endpush

@include('website.'.session('theme_path').'silver.layout.scripts')
