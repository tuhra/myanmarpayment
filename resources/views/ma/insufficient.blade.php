@extends('layouts.telenor.master')
@section('title', 'Go|Games')
@section('content')
<div class="wrap-sm text-center">
	<svg class="icon icon-lg icon-iconmonstr-smiley-sad"><use xlink:href="ico/symbol-defs.svg#icon-iconmonstr-smiley-sad"></use></svg>
	<h3>Oops!</h3>
	<div class="lead">
    	<p><span class="mm-zawgyione">You have insufficient balance to subscribe service</span></p>
    </div>
    <div class="row">
        <button type="submit"><a class="btn-submit" href="{{url('/')}}"><span class="mm-zawgyione">{{trans('app.try_again')}}</span></a></button>
    </div>
</div>
@stop