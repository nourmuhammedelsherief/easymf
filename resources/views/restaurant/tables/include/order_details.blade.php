<div>
	<h1 class="text-center mb-5">{{ trans('dashboard.order_number') }} #{{$order->id}}</h1>
	<table class="table table-striped">
		<thead>
			<tr>
				<th scope="col">{{ trans('dashboard.product_name') }}</th>
				<th scope="col">{{ trans('dashboard.size') }}</th>
				<th scope="col">{{ trans('dashboard.quantity') }}</th>
				<th scope="col">{{ trans('dashboard.unit_price') }}</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($order->order_items as $item)
				<tr>
					<td>
						@if(isset($item->product->id))
							<a href="{{route('products.show' , $item->product->id)}}">{{$item->product->name}}</a>
						@endif
					</td>
					<td>
						@if(isset($item->size->id))
							{{$item->size->name}}
						@endif
					</td>
					<td>{{$item->product_count}}</td>
					<td>{{$item->price}}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>