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
		             		<span class="mm-zawgyione">{{trans('app.welcome')}}</span>
		             	</h4>
		              	<h4 class="text-center">
		              		@if(1 == $user->subscriber->is_subscribed && 1 == $user->subscriber->is_active)
		              		<span class="mm-zawgyione">
		             			{{trans('app.active_subscription')}}<br><small> for {{config('telenor.weeklyprice')}} Ks / Week</small>
		             		</span>
		             		@else
		             			<span class="mm-zawgyione">{{trans('app.unactive_subscription')}}</span>
		             		@endif
		             	</h4>
		             	{{ csrf_field() }}
	             		<center>
	             			
	             		</center>
		             	<p>
		             		@if(1 == $user->subscriber->is_subscribed && 1 == $user->subscriber->is_active)
		             		<center>Next renewal date {{$data['renewal_date']}}</center>
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