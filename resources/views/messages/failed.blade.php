@extends('layouts.telenor.master')
@section('title', 'Go|Games')
@section('content')
<div class="wrap-sm text-center">
	<svg class="icon icon-lg icon-iconmonstr-smiley-sad"><use xlink:href="ico/symbol-defs.svg#icon-iconmonstr-smiley-sad"></use></svg>
	<h3>Oops!</h3>
	<div class="lead">
    	<p><span class="mm-zawgyione">{{trans('app.failed_message')}}</span></p>
    </div>
    <div class="row">
    	@if(Session::get('service'))
    		<a href="{{url('/wavesubscribe?service=waveplay')}}" class="btn-submit"><span class="mm-zawgyione">{{trans('app.try_again')}}</span></a>
    	@else
    		<a href="{{url('/')}}" class="btn-submit"><span class="mm-zawgyione">{{trans('app.try_again')}}</span></a>
    	@endif
    </div>
</div>
@stop