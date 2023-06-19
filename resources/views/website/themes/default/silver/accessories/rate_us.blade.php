<div class="rateUs px-4" style="background-color: {{$restaurant->color == null ? '' : $restaurant->color->product_background}} !important;">
    <form id="form-rate-us" action="{{route('restaurant.rateUs' , $restaurant->id)}}" method="POST">
        @csrf
        <div class="form-group">
            <label for="" style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">{{trans('dashboard.entry.name')}}</label>
            <input type="text" name="name" required class="form-control" placeholder="{{trans('dashboard.entry.name')}}">
        </div>
        <div class="form-group">
            <label for="" style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">{{trans('dashboard.entry.mobile')}}</label>
            <input type="text" name="mobile"  class="form-control" placeholder="{{trans('dashboard.entry.mobile')}}">
        </div>
        @php
            $rateBranches = $restaurant->rateBranches;
        @endphp
        @if(count($rateBranches) > 0)
            <div class="form-group">
                <label for="" style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">{{trans('dashboard.branches')}}</label>
                <select name="branch_id" id="branch_id" class="form-control">
                    <option value="" disabled >{{ trans('dashboard.choose') }}</option>
                    @foreach ($rateBranches as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
        @endif
        <div class="form-group">
            <label for="" style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">{{trans('dashboard.entry.message')}}</label>
            <textarea name="message" id="" style="font-size:12px !important;"  rows="4" class="form-control" placeholder="{{trans('dashboard.entry.hint_message')}}"></textarea>
        </div>

        @foreach (['eat_rate' , 'place_rate' , 'service_rate' , 'reception_rate' , 'speed_rate' , 'worker_rate'] as $index => $key)
            <div class="form-group rate-us " style="clear:both;" data-key="{{$key}}">
                <input type="hidden" class="form-control" name="{{$key}}" value="">
                <div class="float-right" style="color: {{$restaurant->color == null ? '' : $restaurant->color->options_description}}">{{ trans('dashboard._feedback.type.'.$key) }}</div>
                <div class="float-left">
                    <i class="fas fa-star" data-num="5"></i>
                    <i class="fas fa-star" data-num="4"></i>
                    <i class="fas fa-star" data-num="3"></i>
                    <i class="fas fa-star" data-num="2"></i>
                    <i class="fas fa-star" data-num="1"></i>
                </div>
            </div>
        @endforeach
        <div class="form-group " style="clear: both;
        text-align: center;
        padding-top: 20px;
        font-size: 0.8rem;">
            <button type="button" id="save-rate" class="btn btn-primary" style="font-size: 0.8rem">{{ trans('messages.send') }}</button>
        </div>
    </form>
</div>

<style>
    .rateUs .form-group.rate-us{
        padding-top: 10px;
    }
    .rateUs .form-group.rate-us i{
        font-size: 1.1rem;
        color : #dbdbdb;
    }

    .rateUs .form-group.rate-us i.active{
        color : #f9bf00;
    }
</style>

@push('scripts')
    <script>
        var branches = [
                @foreach($rateBranches as $temp)
            {
                "id" : {{$temp->id}} ,
                "link" : "{{$temp->link}}",
            },
            @endforeach
        ];

        function rateUsForm(percent){
            var formData = new FormData($('#form-rate-us')[0]);
            var url = $('#form-rate-us').prop('action');

            $.ajax({
                url : url ,
                method : "POST" ,
                data : formData ,
                processData : false ,
                contentType : false ,
                complete: function(xhr , status){

                } ,
                success: function(json){
                    console.log(json);
                    var branch_id = $('#branch_id').prop('value');
                    $('#menu-rate .form-control').prop('value' , null);
                    $('.rateUs .form-group.rate-us i').removeClass('active');


                    if(json.redirect_to && percent >= 90){
                        // if(json.redirect_to)   window.open(json.redirect_to);
                        setTimeout(() => {
                            $('#menu-rate-link').trigger('click');
                            if(json.redirect_to)  $('#menu-rate-link a.yes').attr('href' , json.redirect_to);
                            console.log('done');
                        }, 1000);
                    }
                    $('#menu-rate .close-menu').trigger('click');
                    console.log('close menu');
                }

            });
        }
        $(function(){


            $('#save-rate').on('click' , function(){
                var check = false;
                var totalRate = 0;
                // $('#menu-rate-link').trigger('click');
                // console.log('done');
                // return 1;
                $.each(['eat_rate' , 'place_rate' , 'service_rate' , 'reception_rate' , 'speed_rate' , 'worker_rate']  , function(key , value){
                    var input = $('input[name='+value+']');
                    if(input && input.val().length > 0){
                        check = true;
                        totalRate += parseInt(input.val());
                    }
                });

                var percent = (totalRate * 100) / 30;
                console.log(percent);
                if(check) rateUsForm(percent);

                // if(check == true) $('#form-rate-us').submit();
            });
        });
    </script>
@endpush


