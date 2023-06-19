@extends('restaurant.lteLayout.master')

@section('title')
    @lang('dashboard.services_store')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <!-- Theme style -->
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('dashboard.services_store')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">

                            <a href="{{route('restaurant.services_store.index')}}">@lang('dashboard.services_store')</a>

                        </li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    @include('flash::message')

    <section class="content">
        <div class="row">
            <div class="col-12">

                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        @php
                            $tempServices = [];
                        @endphp
                        @foreach ($categories as $category)
                            <div class="card">
                                <div class="card-header">{{$category->name}}</div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($category->services as $item)
                                            @php
                                                $myService = App\Models\ServiceSubscription::whereRestaurantId($restaurant->id)
                                                    ->where('service_id' , $item->id)
                                                    ->first();
                                                $tax = \App\Models\Setting::first()->tax;
                                            @endphp
                                            <div class="col-md-3">
                                                <div class="image-preview" style="width: 150px; height:150px;">
                                                    <img src="{{$item->photo == null ? asset($restaurant->image_path) : asset($item->image_path)}}" alt="" style="width: 100%; height:100%">
                                                </div>
                                                <div class="details">
                                                    @if($myService == null || $myService->paid_at == null)
                                                        @if($item->price == 0)
                                                            {{$item->name}} : {{app()->getLocale() == 'ar' ?'مجانا' : 'Free'}}
                                                        @else
                                                            {{$item->name}} :
                                                            <br>
                                                            {{number_format((float)($item->getRealPrice()+($item->getRealPrice() * $tax)/100), 0, '.', '')}}
                                                            {{$item->getRealCurrency()}}
                                                            @lang('messages.including_tax')
                                                        @endif
                                                    @elseif($myService->status == 'active')
                                                        @lang('messages.start') :
                                                        {{$myService->paid_at->format('Y-m-d')}}
                                                        <br>
                                                        @lang('messages.end') :
                                                        {{$myService->end_at->format('Y-m-d')}}
                                                    @endif
                                                </div>
                                                @php
                                                    $casher_branches = \App\Models\Branch::with('service_subscriptions')
                                                    ->whereRestaurantId($restaurant->id)
                                                    ->whereHas('service_subscriptions' , function ($d){
                                                        $d->whereIn('service_id' , [4,9,10]);
                                                    })
                                                    ->where('status' , 'active')
                                                    ->get();
                                                @endphp
                                                @if($myService == null or ($restaurant->branches()->whereStatus('active')->count() > $casher_branches->count()))
                                                    @if($myService != null and $myService->service->id == 4 and ($myService->status == 'active' or $myService->status == 'tentative'))
                                                        <a href="{{url('restaurant/integrations')}}" style="margin-top: 20px;" class="btn btn-primary">
                                                            <i class="fa fa-user-edit"></i>
                                                            @lang('messages.settings')
                                                        </a>
                                                    @else
                                                        <a href="{{route('restaurant.services_store.subscription' , $item->id)}}" style="margin-top: 20px;" class="btn btn-primary">
                                                            <i class="fa fa-user-edit"></i>
                                                            @lang('dashboard.subscribe')
                                                        </a>
                                                    @endif
                                                @elseif($myService != null and $myService->status== 'finished')
                                                    <a href="{{route('restaurant.services_store.subscription' , $item->id)}}" style="margin-top: 20px;" class="btn btn-primary">
                                                        <i class="fa fa-user-edit"></i>
                                                        @lang('messages.renewSubscription')
                                                    </a>
                                                @elseif($myService != null and ($myService->status== 'tentative' or $myService->status== 'tentative_finished'))
                                                    <a href="{{route('restaurant.services_store.subscription' , $item->id)}}" style="margin-top: 20px;" class="btn btn-primary">
                                                        <i class="fa fa-user-edit"></i>
                                                        @lang('messages.activate_subscription')
                                                    </a>
                                                @elseif($myService != null and $myService->status== 'active' and ($item->id == 4 or $item->id == 1 or $item->id == 9 or $item->id == 10))
                                                    <a href="{{url('restaurant/integrations')}}" style="margin-top: 20px;" class="btn btn-primary">
                                                        <i class="fa fa-user-edit"></i>
                                                        @lang('messages.settings')
                                                    </a>

                                                @endif
                                                @if(!empty($item->description))
                                                    <a href="javascript:;" data-toggle="modal" data-target="#detailsModal" style="margin-top: 20px;" class="btn btn-info btn-details" data-id="{{$item->id}}"><i class="far fa-eye"></i> @lang('messages.details')
                                                    </a>
                                                    @php
                                                        $tempServices[] = [
                                                            'id' => $item->id ,
                                                            'title' => $item->name,
                                                            'description' => $item->description,
                                                        ];
                                                    @endphp
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                        @endforeach
                    </div>

                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.col -->
        </div>
    {{$categories->links()}}
    <!-- /.row -->
    </section>
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('messages.close') }}</button>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script>
        var services = {!! json_encode($tempServices) !!};
        console.log(services);
        $(function () {
            $("#example1").DataTable({
                lengthMenu: [
                    [10, 25, 50 , 100, -1],
                    [10, 25, 50,  100,'All'],
                ],
            });
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
            });
        });
    </script>
    <script>
        $( document ).ready(function () {
            $('body').on('click', '.delete_data', function() {
                var id = $(this).attr('data');
                var swal_text = '{{trans('messages.delete')}} ' + $(this).attr('data_name');
                var swal_title = "{{trans('messages.deleteSure')}}";

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "{{trans('messages.sure')}}",
                    cancelButtonText: "{{trans('messages.close')}}"
                }, function() {

                    window.location.href = "{{ url('/') }}" + "/restaurant/res_branches/delete/" + id;

                });

            });
            $('.btn-details').on('click' , function(){
                var tag = $(this);
                $.each(services , function(k , v){
                    if(tag.data('id') == v.id){
                        $('#detailsModalLabel').html(v.title);
                        $('#detailsModal .modal-body').html(v.description);
                    }
                });
            });
        });
    </script>
@endsection
