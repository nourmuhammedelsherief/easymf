<div class="row">
	@if(isset($order->user->id))
		<div class="col-12 text-center mb-4">
			@if($order->type == 'table')
			<span class="badge badge-primary">{{ trans('dashboard.table') }}</span>
			@elseif($order->type == 'chair')
			<span class="badge badge-success">{{ trans('dashboard.chair') }}</span>
			@elseif($order->type == 'package')
			<span class="badge badge-danger">{{ trans('dashboard.package') }}</span>
			
			@endif
		</div>
		{{-- <div class="col-md-6 mb-3">
			<div class="row">
				<div class="col-6 text-bold">{{ trans('dashboard.entry.name') }}</div>
				<div class="col-6">{{$order->user->name}}</div>
			</div>
		</div> --}}

		<div class="col-md-6 mb-3">
			<div class="row">
				<div class="col-6 text-bold">{{ trans('dashboard.place') }}</div>
				<div class="col-6">{{isset($order->table->id) ? $order->table->place->name : '' }}</div>
			</div>
		</div>
		<div class="col-md-6 mb-3">
			<div class="row">
				<div class="col-6 text-bold">{{ trans('dashboard.branch') }}</div>
				<div class="col-6">{{isset($order->table->id) ? $order->table->branch->name : '' }}</div>
			</div>
		</div>
		<div class="col-md-6 mb-3">
			
			<div class="row">
				<div class="col-6 text-bold">{{ trans('dashboard.entry.phone') }}</div>
				<div class="col-6">
					<a href="tel:{{(isset($order->user->country->id) ? $order->user->country->code : App\Modals\Country::findOrFail(2)->code) . $order->user->phone_number}}">{{$order->user->phone_number}}</a>
				</div>
			</div>
		</div>
		
	@endif
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.order_num') }}</div>
			<div class="col-6">{{$order->id}}</div>
		</div>
	</div>
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.entry.payment_type') }}</div>
			<div class="col-6">{{trans('messages._payment_type.' . $order->payment_type)}}</div>
		</div>
	</div>
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.entry.tax_value') }}</div>
			<div class="col-6">{{$order->tax}}</div>
		</div>
	</div>
	@if($order->type == 'chair')
		<div class="col-md-6 mb-3">
			<div class="row">
				<div class="col-6 text-bold">{{ trans('messages.chairs_count') }}</div>
				<div class="col-6">{{$order->chairs}}</div>
			</div>
		</div>
	@elseif($order->type == 'package')

	
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.entry.reservation_quantity') }}</div>
			<div class="col-6">{{$order->chairs}}</div>
		</div>
	</div>
	
	@endif
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.entry.reservation_date') }}</div>
			<div class="col-6">{{$order->date}}</div>
		</div>
	</div>
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.entry.reservation_time') }}</div>
			<div class="col-6">{{$order->from_string}} {{ trans('dashboard.to') }} {{$order->to_string}}</div>
		</div>
	</div>
	
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.entry.price') }}</div>
			<div class="col-6">{{$order->price}}</div>
		</div>
	</div>
	
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.entry.total_price') }}</div>
			<div class="col-6">{{$order->total_price}}</div>
		</div>
	</div>
	@if(!empty($order->note))
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.entry.note') }}</div>
			<div class="col-6">{{$order->note}}</div>
		</div>
	</div>
	@endif
	<div class="col-md-6 mb-3">
		<div class="row">
			<div class="col-6 text-bold">{{ trans('dashboard.entry.created_at') }}</div>
			<div class="col-6">{{$order->created_at->format('Y-m-d h:i A')}}</div>
		</div>
	</div>
</div>