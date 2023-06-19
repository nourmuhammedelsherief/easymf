@if($restaurant->branches->count() > 0 && $restaurant->show_branches_list == 'true')
    <?php $i = 1; ?>
    @foreach($restaurant->branches->whereIn('status' , ['active' , 'tentative']) as $res_branch)
        <div class="fac fac-radio fac-orange mb-1 mr-4">
            <div class="row">
                <div class="col-sm-12">
                    <label for="map{{$i}}-fac-radio" class="color-dark1-dark ">
                        <input id="map{{$i}}-fac-radio" type="radio" name="map"
                               value="{{$res_branch->id}}" {{$res_branch->id == $branch->id ? 'checked' : ''}}>
                        <span class="checkmark" style="border-color: {{$restaurant->color == null ? 'orange' : $restaurant->color->icons }} !important"></span> <span class="minw100 font-14 font-600">
                    {{app()->getLocale() == 'ar' ? $res_branch->name_ar : $res_branch->name_en}}
                </span>


                        @if($branch->id != $res_branch->id)
                            @if($res_branch->main == 'true')
                                <a href="{{route('sliverHome', [$restaurant->name_barcode])}}"
                                   class="btn btn-xxs bg-yellow2-dark"> @lang('messages.choose') </a>

                            @else
                                <a href="{{route('sliverHomeBranch', [$restaurant->name_barcode , $res_branch->name_barcode])}}"
                                   class="btn btn-xxs bg-yellow2-dark"> @lang('messages.choose') </a>
                            @endif
                        @endif


                    </label>
                </div>

            </div>
        </div>
        <?php $i++; ?>
    @endforeach
@endif
