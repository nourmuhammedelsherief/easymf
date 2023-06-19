@extends('admin.lteLayout.master')

@section('title')
    @lang('dashboard.tasks')
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
                    <h1>@lang('dashboard.tasks')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="{{url('/admin/home')}}">
                                @lang('messages.control_panel')
                            </a>
                        </li>
                        <li class="breadcrumb-item active">
                            <a href="{{route('tasks.index')}}"></a>
                            @lang('dashboard.tasks')
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
                    @if(auth('admin')->user()->role == 'admin' and $myTask == false)
                        <a href="{{route('tasks.create')}}" class="btn btn-info">
                            <i class="fa fa-plus"></i>
                            @lang('messages.add_new')
                        </a>
                        <a href="{{route('tasks.index') }}" class="btn btn-primary">
                            <i class="fas fa-list-ul"></i>
                            @lang('dashboard.all') ({{App\Models\Task::count()}})
                        </a>
                        <a href="{{route('tasks.index') . '?status=pending'}}" class="btn btn-danger">
                            <i class="fas fa-list-ul"></i>
                            @lang('dashboard.pending_tasks') ({{App\Models\Task::where('status' , 'pending')->count()}})
                        </a>
                        <a href="{{route('tasks.index') . '?status=in_progress'}}" class="btn btn-danger">
                            <i class="fas fa-list-ul"></i>
                            @lang('dashboard.in_progress_tasks')  ({{App\Models\Task::where('status' , 'in_progress')->count()}})
                        </a>
                        <a href="{{route('tasks.index') . '?status=completed'}}" class="btn btn-success">
                            <i class="fas fa-list-ul"></i>
                            @lang('dashboard.completed_tasks')  ({{App\Models\Task::where('status' , 'completed')->count()}})
                        </a>
                    @endif
                    @if($myTask == true)
                        <a href="{{route('tasks.my') }}" class="btn btn-primary">
                            <i class="fas fa-list-ul"></i>
                            @lang('dashboard.all')
                        </a>
                        <a href="{{route('tasks.my') . '?status=pending'}}" class="btn btn-danger">
                            <i class="fas fa-list-ul"></i>
                            @lang('dashboard.pending_tasks')
                        </a>
                        <a href="{{route('tasks.my') . '?status=in_progress'}}" class="btn btn-danger">
                            <i class="fas fa-list-ul"></i>
                            @lang('dashboard.in_progress_tasks')
                        </a>
                        <a href="{{route('tasks.my') . '?status=completed'}}" class="btn btn-success">
                            <i class="fas fa-list-ul"></i>
                            @lang('dashboard.completed_tasks')
                        </a>
                    @endif
                </h3>
                <div class="card">
                    <!-- /.card-header -->
                    <div class="card-body">
                        @if($errors->any())
                            <p class="alert alert-danger">{{$errors->first()}}</p>
                        @endif
                        <div class="filter-table" style="margin:0 0 30px 0 ;">
                            <form action="{{$myTask == true ? route('tasks.my') : route('tasks.index')}}">
                                <div class="row">
                                    {{-- title --}}
                                    <div class="form-group col-md-3">
                                        <label class="control-label"> @lang('dashboard.entry.name') </label>
                                        <input type="text" name="title" class="form-control" value="{{(isset($filter['title'])) ? $filter['title'] : ''}}" placeholder="{{trans('dashboard.entry.name')}}">
                                        @if ($errors->has('title'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('title') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    
                                    @if(auth('admin')->user()->role == 'admin' and $myTask == false)
                                        {{-- category_id --}}
                                        <div class="form-group col-md-3">
                                            <label class="control-label"> @lang('dashboard.entry.category') </label>
                                            <select name="category_id" id="category_id" class="select2 form-control" >
                                                <option value="" disabled selected>اختر .. </option>
                                                @foreach ($categories as $item)
                                                    <option value="{{$item->id}}" {{(isset($filter['category_id']) and $filter['category_id'] == $item->id) ? 'selected' : ''}}>{{$item->name}}</option> 
                                                @endforeach
                                            </select>
                                            @if ($errors->has('category_id'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('category_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        {{-- employee_id --}}
                                        <div class="form-group col-md-3">
                                            <label class="control-label"> @lang('dashboard.entry.employee') </label>
                                            <select name="employee_id" id="employee_id" class="select2 form-control" >
                                                <option value="" disabled selected>اختر .. </option>
                                                @foreach ($employees as $item)
                                                    <option value="{{$item->id}}" {{(isset($filter['employee_id']) and $filter['employee_id'] == $item->id) ? 'selected' : ''}}>{{$item->name}} ({{trans('dashboard._role.' . $item->role)}})</option> 
                                                @endforeach
                                            </select>
                                            @if ($errors->has('employee_id'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('employee_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    {{-- status --}}
                                    <div class="col-md-3">
                                        <label for="">{{ trans('dashboard.task_status') }}</label>
                                        <select name="status" id="status" class="form-control select2">
                                            <option value="">{{ trans('dashboard.all') }}</option>
                                            <option value="pending" {{(isset($filter['status']) and $filter['status'] == 'pending') ? 'selected' : ''}}>{{ trans('dashboard._task_status.pending') }}</option>
                                                <option value="in_progress" {{( isset($filter['status']) and $filter['status'] == 'in_progress') ? 'selected' : ''}}>{{ trans('dashboard._task_status.in_progress') }}</option>
                                                <option value="completed" {{( isset($filter['status']) and $filter['status'] == 'completed' )? 'selected' : ''}}>{{ trans('dashboard._task_status.completed') }}</option>
                                        </select>
                                    </div>
                                    {{-- created_at --}}
                                    <div class="form-group col-md-3">
                                        <label class="control-label"> @lang('dashboard.entry.created_at') </label>
                                        <input type="text" name="created_at" class="form-control" value="{{(isset($filter['created_at'])) ? $filter['created_at'] : ''}}" placeholder="ex: {{date('Y-m-d')}}">
                                        @if ($errors->has('created_at'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('created_at') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="col-2">
                                        <button type="submit" class="btn btn-primary" style="margin-top:30px;">{{ trans('dashboard.search') }}</button>
                                    </div>
                                </div>
                                
                            </form>
                        </div>
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>@lang('dashboard.entry.name')</th>
                                <th>@lang('dashboard.entry.employee')</th>
                                <th>@lang('dashboard.entry.category')</th>
                                {{-- <th>@lang('dashboard.entry.description')</th> --}}
                                <th>@lang('dashboard.task_status')</th>
                                <th>@lang('dashboard.entry.priority')</th>
                                <th>@lang('dashboard.entry.created_at')</th>
                                
                                <th>@lang('messages.operations')</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0 ?>
                            @foreach($tasks as $task)
                                <tr class="odd gradeX">
                                    
                                    <td>
                                        <a href="javascript:;" class="show-details" data-id="{{$task->id}}">{{$task->title}}</a>    
                                    </td>
                                    <td> {!! isset($task->employee->id) ? '<a href="'.route('admins.edit' , $task->employee_id).'">'.$task->employee->name.'</a>' : '' !!} </td>
                                    <td> {!! isset($task->category->id) ? '<a href="'.route('task_categories.edit' , $task->category_id).'">'.$task->category->name_ar.'</a>' : '' !!}</td>
                                    {{-- <td> 
                                        {{$task->description}}
                                    </td> --}}
                                    <td>
                                        @if($task->status == 'pending')
                                            <span class="badge badge-info">{{ trans('dashboard._task_status.' . $task->status) }}</span>
                                        @elseif($task->status == 'in_progress')
                                            <span class="badge badge-primary">{{ trans('dashboard._task_status.' . $task->status) }}</span>
                                        @elseif($task->status == 'completed')
                                            <span class="badge badge-success">{{ trans('dashboard._task_status.' . $task->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($task->priority == 'low')
                                            <span class="badge badge-secondary">{{ trans('dashboard._task_priority.' . $task->priority) }}</span>
                                        @elseif($task->priority == 'medium')
                                            <span class="badge badge-primary">{{ trans('dashboard._task_priority.' . $task->priority) }}</span>
                                        @elseif($task->priority == 'high')
                                            <span class="badge badge-danger">{{ trans('dashboard._task_priority.' . $task->priority) }}</span>
                                        @endif
                                    </td>
                                    <td>{{date('Y-m-d h:i A' , strtotime($task->created_at))}}</td>
                                    <td>
                                        <a class="btn btn-info show-details" href="javascript:;"  data-id="{{$task->id}}">
                                            <i class="fa fa-user-alt"></i> @lang('messages.show')
                                        </a>
                                        @if($task->status != 'completed' and $myTask == true)
                                        <a class="btn btn-warning change-status" href="javascript:;"  data-id="{{$task->id}}">
                                            <i class="fa fa-edit"></i> @lang('dashboard.change_status')
                                        </a>
                                        @endif
                                        @if(auth('admin')->user()->role == 'admin' and $myTask == false)
                                        <a class="btn btn-primary" href="{{route('tasks.edit' , $task->id)}}">
                                            <i class="fa fa-user-edit"></i> @lang('messages.edit')
                                        </a>
                                        <a class="delete_city btn btn-danger" data="{{ $task->id }}" data_name="{{ $task->title }}" >
                                            <i class="fa fa-key"></i> @lang('messages.delete')
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>


                        {!! $tasks->links() !!}
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>

    <div class="modal fade" id="changeStatus" tabindex="-1" role="dialog" aria-labelledby="changeStatusTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="changeStatusTitle">{{ trans('dashboard.change_status') }}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              ...
            </div>
            
          </div>
        </div>
    </div>    
<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailsModalTitle">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('dashboard.close') }}</button>
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
        $(function () {
            $('.show-details').on('click' , function(){
                var tag = $(this);
                console.log('details');
                $.ajax({
                    url : "{{auth('admin')->user()->role == 'admin' ? route('tasks.index') : route('tasks.my')}}/" + tag.data('id') , 
                    method : 'GET' , 
                    success : function(json){
                        console.log('success');
                        if(json.status){
                            $('#detailsModal .modal-body').html(json.data);
                            $('#detailsModal .modal-title').html(json.title);
                            $('#detailsModal').modal('show');
                        }
                            
                    }
                });
            });
            $('#changeStatus').on('change' , 'select[name=status]' , function(){
                var tag = $(this);
                console.log(tag.val());
                if(tag.val() == 'completed'){
                    
                    $('#hours_count').fadeIn(300);
                    $('#worked_at').fadeIn(300);
                }else{
                    $('#hours_count').fadeOut(300);
                    $('#worked_at').fadeOut(300);
                }
            });
            $('.change-status').on('click' , function(){
                var tag = $(this);
                console.log('change-status');
                $.ajax({
                    url : "{{route('tasks.my')}}/" + tag.data('id') + '/change-status' , 
                    method : 'GET' , 
                    success : function(json){
                        console.log('success');
                        if(json.status){
                            $('#changeStatus .modal-body').html(json.data);
                            $('#changeStatus').modal('show');
                        }
                            
                    }
                });
            });
            $("#example1").DataTable({
                lengthMenu: [
                    [10, 25, 50 , 100, -1],
                    [10, 25, 50,  100,'All'],
                ],
                order : [[5 , 'desc']] , 
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
        $(document).ready(function() {
            var CSRF_TOKEN = $('meta[name="X-CSRF-TOKEN"]').attr('content');

            $('body').on('click', '.delete_city', function() {
                var id = $(this).attr('data');

                var swal_text = 'حذف ' + $(this).attr('data_name') + '؟';
                var swal_title = 'هل أنت متأكد من الحذف ؟';

                swal({
                    title: swal_title,
                    text: swal_text,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: "تأكيد",
                    cancelButtonText: "إغلاق",
                    closeOnConfirm: false
                }, function() {

                    {{--var url = '{{ route("imageProductRemove", ":id") }}';--}}

                        {{--url = url.replace(':id', id);--}}

                        window.location.href = "{{ url('/') }}" + "/admin/tasks/delete/"+id;


                });

            });

        });
    </script>

@endsection
