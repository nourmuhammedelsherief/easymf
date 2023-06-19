<div class="px-3 pop sensitive" >
    @if($restaurant->sensitivities->count() > 0)
        @foreach($restaurant->sensitivities as $sensitive)
            <div class="card mb-0">
                <div class="content mt-0 mb-0">
                    <div class="row justify-content-center mb-2">
                        <div class="col-3">
                            <img src="{{asset('/uploads/sensitivities/' . $sensitive->photo)}}" class="rounded-xl shadow-x2l  mx-auto mt-1" width="65">
                        </div>
                        <div class="col-9 pt-3 pl-0">
                            <h4 class="font-16">
                                {{app()->getLocale() == 'ar' ? $sensitive->name_ar : $sensitive->name_en}}
                            </h4>
                            <p class="mt-n1 line-height-m color-gray2-dark">
                                {!! app()->getLocale() == 'ar' ? $sensitive->details_ar : $sensitive->details_en !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

