
<h1 class="text-center">{{ trans('dashboard.table_num'  , ['num' => $table->id]) }}</h1>

@if($table->type == 'package')
<div class="image-preview" style="text-align:center;">
	<img src="{{$table->image_path}}" alt="" style="max-height:300px;">
</div>
@endif
<div class="row" style="margin-top: 30px;margin-bottom:40px;">
	<div class="col-2 " style="font-weight:bold;">
		{{ trans('dashboard.entry.place') }}
	</div>
	<div class="col-4 ">
		{{isset($table->place->id) ? $table->place->name : null }}
	</div>
	<div class="col-2 " style="font-weight:bold;">
		{{ trans('dashboard.entry.branch') }}
	</div>
	<div class="col-4 ">
		{{isset($table->branch->id) ? $table->branch->name : null }}
	</div>
	@if($table->type == 'chair' )
		<div class="col-2 " style="font-weight:bold;">
			@lang('dashboard.entry.chair_min')
		</div>
		<div class="col-4 ">
			{{$table->chair_min}}
		</div>

		<div class="col-2 " style="font-weight:bold;">
			@lang('dashboard.entry.chair_max')
		</div>
		<div class="col-4 ">
			{{$table->chair_max}}
		</div>
	@elseif($table->type == 'package')
	<div class="col-2 " style="font-weight:bold;">
		@lang('dashboard.people_count')
	</div>
	<div class="col-4 ">
		{{$table->people_count}}
	</div>
	<div class="col-2 " style="font-weight:bold;">
		@lang('dashboard.entry.reservation_min')
	</div>
	<div class="col-4 ">
		{{$table->chair_min}}
	</div>
	<div class="col-2 " style="font-weight:bold;">
		@lang('dashboard.entry.reservation_max')
	</div>
	<div class="col-4 ">
		{{$table->chair_max}}
	</div>
	@else 
		<div class="col-2 " style="font-weight:bold;">
			@lang('dashboard.people_count')
		</div>
		<div class="col-4 ">
			{{$table->people_count}}
		</div>

		<div class="col-2 " style="font-weight:bold;">
			@lang('dashboard.table_count')
		</div>
		<div class="col-4 ">
			{{$table->table_count}}
		</div>
	@endif

	<div class="col-2 " style="font-weight:bold;">
		@lang('messages.price')
	</div>
	<div class="col-4 ">
		{{$table->price}}
	</div>

	@if($table->type == 'package')
		<div class="row col-12">
			<div class="col-2 " style="font-weight:bold;">
				@lang('dashboard.entry.name_ar')
			</div>
			<div class="col-4 ">
				{{$table->title_ar}}
			</div>
			<div class="col-2 " style="font-weight:bold;">
				@lang('dashboard.entry.name_en')
			</div>
			<div class="col-4 ">
				{{$table->title_en}}
			</div>
	
			<div class="col-12 text-center mt-4" style="font-weight:bold;">
				@lang('dashboard.entry.description_ar')
			</div>
			<div class="col-12 ">
				{!! $table->description_ar !!}
			</div>
			
			<div class="col-12 text-center mt-4 " style="font-weight:bold;">
				@lang('dashboard.entry.description_en')
			</div>
			<div class="col-12 ">
				{!! $table->description_en !!}
			</div>
		</div>
	@endif
</div>
<div class="dates">
	<label for="">{{ trans('dashboard.entry.dates') }}</label>
	<div class="row">
		@foreach($table->dates as $item)
		@php
			$check = false;
			$cdate = Carbon\Carbon::createFromTimeStamp(strtotime($item->date));
			$now = Carbon\Carbon::createFromTimeStamp(strtotime(date('Y-m-d')));
			if($now->greaterThan($cdate)) $check = true;
			if($table->periods->count() == 0) $check = true;
		@endphp
			<div class="col-md-3 col-sm-4 date {{$check ? 'done-date' : ''}}">
				{!! $check ? '<span class="close-date"><i class="fas fa-times"></i></span>' : '' !!}
				<div class="text-center">{{$item->date}}</div>
			</div>
		@endforeach
	</div>
</div>

<div class="periods">
	<label for="">{{ trans('dashboard.periods') }}</label>
	<div class="row">
		@foreach($table->periods as $item)
			<div class="col-md-3 col-sm-4">
				<div class="text-center {{$item->status == 'not_available' ? 'active' : ''}}">
					{{date('h:i A' , strtotime($item->from))}} {{ trans('dashboard.to') }} {{date('h:i A' , strtotime($item->to))}}
					
				</div>
			</div>
		@endforeach
	</div>
</div>

@if($table->images->count() > 0)
<div class="row add-images mt-5">
	
@foreach ($table->images as $item)
	<div class="col-md-4 text-center mt-2" style="">
		<img src="{{asset($item->path)}}" alt="" style="max-width:100%;max-height:300px;">			
	</div>

@endforeach
</div>
@endif