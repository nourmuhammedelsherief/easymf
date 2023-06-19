@extends('admin.lteLayout.master')

@section('title')
    @lang('dashboard.ads')
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
                    <h1>@lang('dashboard.ads')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
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
                <h3>
                    <a href="{{route('adminAds.create')}}" class="btn btn-info">
                        <i class="fa fa-plus"></i>
                        @lang('messages.add_new')
                    </a>
                </h3>
                <div class="card">

                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                        <span></span>
                                    </label>
                                </th>
                                <th></th>
                                <th>@lang('dashboard.entry.type')</th>
                                
                                <th> @lang('dashboard.entry.content') </th>
                                <th> @lang('dashboard.entry.start_date') </th>
                                <th> @lang('dashboard.entry.end_date') </th>
                                <th> @lang('dashboard.entry.is_active') </th>
                                <th> @lang('dashboard.entry.created_at') </th>
                                <th> @lang('messages.operations') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0 ?>
                            @foreach($ads as $index => $item)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="{{$item->id}}" />
                                            <span></span>
                                        </label>
                                    </td>
                                    <td>{{$index + 1}}</td>
                                    <td>
                                        <span class="badge badge-primary">{{trans('dashboard._ads_type.' . $item->type)}}</span>
                                        </td>
                                    
                                    
                                    <td>
                                        @if($item->content_type == 'image')
                                            <div class="image-preview" style="max-width:200px;max-height:400px;">
                                                <img src="{{asset($item->image_path)}}" alt="" style="width: 100%;max-height:100%">
                                            </div>
                                        @elseif($item->content_type == 'youtube')
                                        <iframe width="200" src="{{$item->content}}"  frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        @endif
                                    </td>
                                    <td>{{$item->start_date}}</td>
									<td>{{$item->end_date}}</td>
									<td>
                                        @if($item->isActive() == true)
                                            <span class="badge badge-success">{{ trans('dashboard.yes') }}</span>
                                        @else 
                                            <span class="badge badge-secondary">{{ trans('dashboard.no') }}</span>
                                        @endif
                                    </td>
									<td>{{date('Y-m-d h:i A' , strtotime($item->created_at))}}</td>
               
                                    <td>
                                        <a href="{{route('adminAds.edit' , $item->id)}}" class=" btn btn-info" data="{{ $item->id }}" data_name="{{ $item->name }}" >
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        <a class="delete_data btn btn-danger" data="{{ $item->id }}" data_name="{{ $index + 1 }}" >
                                            <i class="fa fa-key"></i> @lang('messages.delete')
                                        </a>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
    

    <!-- /.row -->
    </section>
@endsection

@section('scripts')

    <script src="{{asset('dist/js/adminlte.min.js')}}"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js')}}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script>
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

                    window.location.href = "{{ url('/') }}" + "/admin/restaurant-ads/delete/" + id;

                });

            });
        });
    </script>
@endsection

