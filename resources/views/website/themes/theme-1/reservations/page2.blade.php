@push('styles')

    {{-- <link rel="stylesheet" href="{{asset('plugins/color-calender/theme-basic.css')}}"> --}}
    <link rel="stylesheet" href="{{asset('plugins/color-calender/theme-glass.css')}}">
@endpush
@include('website.'.session('theme_path').'silver.layout.header')


<style>
    :root{
        --rest-background-color : #ebebeb;
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
        clear: both;
    }

    .footer-button a {
        font-size: 14px;
    }
    .heighlight{
        
        height: 81px !important;
        border-left: 1px solid #000;
        width: 0px;
    }
    
	/* new */
	html{
		position: relative;
	}
	body{
		position: initial !important;
		margin-bottom: 50px !important;
	}
	.footer-description{
		position: absolute;
		padding: 10px 0;
		bottom:10px;
		left: 0;
		width: 100% !important; 
		text-align: center;
	}
</style>


<div class="x-card-header"></div>
<div class="x-card card mr-0 ml-0 rounded-l" style="">
    <div class="container">
        <h1 class="text-center" style="font-size: 1.1rem !important;margin-top: 15px;">{{ trans('messages.reservations') }} - {{$restaurant->name}}</h1>
        @if($errors->any())
			<p class="alert alert-danger mt-5">{{$errors->first()}}</p>
		@endif
        <div class="icons">
            <div class="div-icon text-center">
                <div class=" text-center">
                    <i class="fas fa-user-alt"></i>
                </div>
                <p>{{$reservation->table->people_count}} </p>
                
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


            <div class="data">
                <label for="">
                    @lang('messages.price')
                </label>
                <p>{{$reservation->price}} {{$country->name}}</p>
            </div>
            @if($reservation->tax > 0)
            <div class="data" style="clear: both">
                <label for="">{{trans('messages.tax_')}}</label>
                <p>{{$reservation->tax}} {{$country->name}}</p>
            </div>
            @endif
            <div class="data" style="position: relative;">
                <label for="">@lang('messages.total')</label>
                <p style="">{{$reservation->total_price}} {{$country->name}} <br>
                    
                
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

        @if($restaurant->reservation_description_ar != null)
            <div class="description-content">
                <div class="title">@lang('dashboard.reservation_description')</div>
                <div class="body">
                    {!! app()->getLocale() == 'ar' ? $restaurant->reservation_description_ar : $restaurant->reservation_description_en !!}
                    <br>
                </div>
            </div>
        @endif
        <form   action="{{route('reservation.page2' , [$branch->id , $reservation->id])}}"  method="POST" id="reservationForm" enctype="multipart/form-data">
            @csrf
            <div class="client-note">
                <label for="" class="dashed">
                    @lang('messages.notes')
                </label>
                <textarea name="note" class="form-control" style="border:1px solid #CCC;" id="" cols="30" rows="5"></textarea>
            </div>
            @php
            $bank_transfer_service = \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)->where('service_id' , 2)->where('status' , 'active')->first();
            $online_service = \App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)->where('service_id' , 3)->where('status' , 'active')->first();
        @endphp
        <input type="hidden" name="tst" value="test input">
        
            <div class="form-group payment">
                <label for="payment_type">
                    @lang('messages.payment_method')
                </label>
                <select name="payment_type" id="payment_type" class="form-control">
                    
                    @if($bank_transfer_service != null)
                        <option value="bank" selected>
                            @lang('messages.transfer_bank')
                        </option>
                    @endif
                    @if($online_service != null)
                        <option value="online">
                            @lang('messages.online')
                        </option>
                    @endif
                </select>
            </div>
        
            <div class="footer-button text-center">
                <button type="submit"
                class="btn btn-primary ">{{ trans('messages.next_step') }}</button>
                
                <button href="{{route('reservation.page1' , $restaurant->id) . '?branch_id=' . $branch->Id}}" class="btn btn-secondary return" type="button">{{ trans('messages.return') }}</button>
            </div>
        </form>
        

      

        
    </div>

</div>



<div class="footer footer-description text-center">
	{!!  trans('messages.reservation_footer') !!}
</div>

@push('scripts')
    <script>
        $(function () {
            $('.save-page').on('click' ,function(event){
                event.preventDefault();
                if($('select[name=payment_type]').val())
				    $('form#reservationForm').submit();
			});
            $('button.return').on('click' , function(){
                window.location.replace($(this).attr('href'));
            });
        });
    </script>
@endpush

@include('website.'.session('theme_path').'silver.layout.scripts')
