@extends('restaurant.lteLayout.master')

@section('title')
    @lang('messages.foodics_discount')
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('plugins/dataseller_codes-bs4/css/dataseller_codes.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('admin/css/sweetalert.css') }}">
    <!-- Theme style -->
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>@lang('messages.foodics_discount')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/restaurant/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('foodics_discounts' , $branch->id)}}"></a>
                            @lang('messages.foodics_discount')
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
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>
                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                        <input type="checkbox" class="group-checkable"
                                               data-set="#sample_1 .checkboxes"/>
                                        <span></span>
                                    </label>
                                </th>
                                <th></th>
                                <th> @lang('messages.branch') </th>
                                <th> @lang('messages.name') </th>
                                <th> @lang('messages.seller_code') </th>
                                <th> @lang('messages.amount') </th>
                                <th> @lang('messages.is_taxable') </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0 ?>
                            @foreach($discounts as $discount)
                                <tr class="odd gradeX">
                                    <td>
                                        <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                            <input type="checkbox" class="checkboxes" value="1"/>
                                            <span></span>
                                        </label>
                                    </td>
                                    <td><?php echo ++$i ?></td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? $discount->branch->name_ar : $discount->branch->name_en}}
                                    </td>
                                    <td>
                                        {{app()->getLocale() == 'ar' ? ($discount->name_ar == null ? $discount->name_en : $discount->name_ar) : ($discount->name_en == null ? $discount->name_ar : $discount->name_en)}}
                                    </td>
                                    <td>
                                        {{$discount->name_en}}
                                    </td>
                                    <td>
                                        @if($discount->is_percentage == 'true')
                                            {{$discount->amount}} %
                                        @else
                                            {{$discount->amount}}
                                            {{app()->getLocale() == 'ar' ? $branch->restaurant->country->currency_ar : $branch->restaurant->country->currency_en}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($discount->is_taxable == 'true')
                                            @lang('messages.yes')
                                        @else
                                            @lang('messages.no')
                                        @endif
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
    <script src="{{asset('plugins/dataseller_codes/jquery.dataseller_codes.js')}}"></script>
    <script src="{{asset('plugins/dataseller_codes-bs4/js/dataseller_codes.bootstrap4.js')}}"></script>
    <script src="{{ URL::asset('admin/js/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('admin/js/ui-sweetalert.min.js') }}"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'All'],
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
        $(document).ready(function () {
            $('body').on('click', '.delete_data', function () {
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
                }, function () {

                    window.location.href = "{{ url('/') }}" + "/restaurant/seller_codes/delete/" + id;

                });

            });
        });
    </script>
@endsection

