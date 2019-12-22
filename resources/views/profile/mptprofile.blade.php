@extends('layouts.master')
@section('title', 'Go|Games')
@section('content')
<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default">
           <div class="panel-body notfound-body">
				<div class="notfound">
					<form action="{{ url('/upgrade') }}" method="post">
					 	<h4 class="text-center">
		             		<span class="mm-zawgyione">{{trans('app.welcome')}} </span>
		             	</h4>
		              	<h4 class="text-center">
		              		<?php
		              			if(Session::get('locale') == 'en') {
		              				$text = 'The service has been subscribed by 99 Ks per day.';
		              			} else {
		              				$text = trans('app.mpt_myacc_one') . trans('app.mpt_myacc_two') . trans('app.mpt_myacc_three');
		              			}
		              		?>
		              		@if(1 == $user->subscriber->is_subscribed && 1 == $user->subscriber->is_active)
	              				<span class="mm-zawgyione">
	              					{!! $text !!}
	              				</span>
		              		@else
		              			<span class="mm-zawgyione">
		              			{{trans('app.unactive_subscription')}}
		              			</span>
		              		@endif
		             	</h4>
		             	{{ csrf_field() }}
	             		<center>
	             		</center>
		             	<p>
		             		@if(1 == $user->subscriber->is_subscribed && 1 == $user->subscriber->is_active)
		             			<center>
		             				<span class="mm-zawgyione">
		             					<?php
		             						$date = new DateTime($data['renewal_date']);
		             						$renewal_date = $date->format('d-m-Y');
		             					?>
		             					{{ trans('app.nxt') }} {{ $user->subscriber->valid_date }}
		             				</span>
		             			</center>
		             		@endif
		             	</p>
	             	</form>
				</div>
           </div>
           <div class="panel-footer myaccount">
	           	
           </div>    
        </div>
	</div>
</div>
@stop