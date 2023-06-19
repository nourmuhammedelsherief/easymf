@if($branches->count() > 0)
    <?php $i = 1; ?>
    @foreach($branches as $res_branch)
        <div class="fac fac-radio fac-orange mb-1 mr-4">
            <div class="row">
                <div class="col-sm-12">
                    <label for="map{{$i}}-fac-radio" class="color-dark1-dark ">
                        <input id="map{{$i}}-fac-radio" type="radio" name="map"
                               value="{{$res_branch->id}}" {{(isset($branch->id ) and $res_branch->id == $branch->id ) ? 'checked' : ''}}>
                        <span class="checkmark" style="border-color: {{$restaurant->color == null ? 'orange' : $restaurant->color->icons }} !important"></span> <span class="minw100 font-14 font-600">
                    {{app()->getLocale() == 'ar' ? $res_branch->name_ar : $res_branch->name_en}}
                </span>


                        @if((!isset($branch->id) or $branch->id != $res_branch->id))
                            
                                <a href="{{route('reservation.page1' , $restaurant->id) . '?branch_id=' . $res_branch->id}}"
                                   class="btn btn-xxs bg-yellow2-dark"> @lang('messages.choose') </a>

                            {{-- @else
                                <a href="{{route('reservation.page1' , $res_branch->id)}}"
                                   class="btn btn-xxs bg-yellow2-dark"> @lang('messages.choose') </a>
                             --}}
                        @endif


                    </label>
                </div>

            </div>
        </div>
        <?php $i++; ?>
    @endforeach
@endif
