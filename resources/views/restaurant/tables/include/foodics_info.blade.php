@if(isset($foodics['data']['id']))
	
	<div class="row">
		<div class="col-md-6">
			<div class="title">{{ trans('dashboard.foodics_order_id') }}</div>
			<p class="content">{{$foodics['data']['reference']}}</p>
		</div>
		<div class="col-md-6">
			<div class="title">{{ trans('dashboard.order_id') }}</div>
			<p class="content">{{$foodics['data']['id']}}</p>
		</div>

		<div class="col-md-6">
			<div class="title">{{ trans('dashboard.order_status') }}</div>
			<p class="content">
				@if($foodics['data']['status'] == 1) 
					<span class="badge badge-secondary">{{ trans('dashboard._foodics_status.' . $foodics['data']['status']) }}</span>
				@elseif($foodics['data']['status'] == 2) 
					<span class="badge badge-primary">{{ trans('dashboard._foodics_status.' . $foodics['data']['status']) }}</span>
				@elseif($foodics['data']['status'] == 3) 
					<span class="badge badge-danger">{{ trans('dashboard._foodics_status.' . $foodics['data']['status']) }}</span>
				@elseif($foodics['data']['status'] == 4) 
				<span class="badge badge-success">{{ trans('dashboard._foodics_status.' . $foodics['data']['status']) }}</span>
				@elseif($foodics['data']['status'] == 5) 
					<span class="badge badge-warning">{{ trans('dashboard._foodics_status.' . $foodics['data']['status']) }}</span>
				@elseif($foodics['data']['status'] == 6) 
					<span class="badge badge-info">{{ trans('dashboard._foodics_status.' . $foodics['data']['status']) }}</span>
				@elseif($foodics['data']['status'] == 7) 
					<span class="badge badge-info">{{ trans('dashboard._foodics_status.' . $foodics['data']['status']) }}</span>
				@endif
			</p>
		</div>
		<div class="col-md-6">
			<div class="title">{{ trans('dashboard.delivery_status') }}</div>
			<p class="content">
				@if($foodics['data']['delivery_status'] == 1) 
					<span class="badge badge-secondary">{{ trans('dashboard._foodics_delivery_status.' . $foodics['data']['delivery_status']) }}</span>
				@elseif($foodics['data']['delivery_status'] == 2) 
					<span class="badge badge-primary">{{ trans('dashboard._foodics_delivery_status.' . $foodics['data']['delivery_status']) }}</span>
				@elseif($foodics['data']['delivery_status'] == 3) 
					<span class="badge badge-danger">{{ trans('dashboard._foodics_delivery_status.' . $foodics['data']['delivery_status']) }}</span>
				@elseif($foodics['data']['delivery_status'] == 4) 
				<span class="badge badge-success">{{ trans('dashboard._foodics_delivery_status.' . $foodics['data']['delivery_status']) }}</span>
				@elseif($foodics['data']['delivery_status'] == 5) 
					<span class="badge badge-warning">{{ trans('dashboard._foodics_delivery_status.' . $foodics['data']['delivery_status']) }}</span>
				@elseif($foodics['data']['delivery_status'] == 6) 
					<span class="badge badge-info">{{ trans('dashboard._foodics_delivery_status.' . $foodics['data']['delivery_status']) }}</span>

				@endif
			</p>
		</div>
		<div class="col-md-6">
			<div class="title">{{ trans('dashboard.type') }}</div>
			<p class="content">
				@if($foodics['data']['type'] == 1) 
					<span class="badge badge-secondary">{{ trans('dashboard._foodics_type.' . $foodics['data']['type']) }}</span>
				@elseif($foodics['data']['type'] == 2) 
					<span class="badge badge-primary">{{ trans('dashboard._foodics_type.' . $foodics['data']['type']) }}</span>
				@elseif($foodics['data']['type'] == 3) 
					<span class="badge badge-danger">{{ trans('dashboard._foodics_type.' . $foodics['data']['type']) }}</span>
				@elseif($foodics['data']['type'] == 4) 
				<span class="badge badge-success">{{ trans('dashboard._foodics_type.' . $foodics['data']['type']) }}</span>
				@endif
			</p>
		</div>
		
	</div>

@else
	<p class="alert alert-danger text-center">لا يوجد طلب لفودكس</p>
	
	@if($order->payment_type == 'cash' or ($order->payment_type == 'online' and $order->payment_status == 'true'))
		<div class="text-center mt-10">
			<button class="btn btn-primary create-foodics-order" data-id="{{$order->id}}">{{ trans('dashboard.create_foodics_order') }}</button>
		</div>
	@endif
@endif