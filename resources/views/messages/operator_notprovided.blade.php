@extends('layouts.master')
@section('title', 'Go|Games')
@section('content')
	<div class="wrap-sm text-center">
		<svg class="icon icon-lg icon-iconmonstr-smiley-sad"><use xlink:href="ico/symbol-defs.svg#icon-iconmonstr-smiley-sad"></use></svg>
		<h3>Oops!</h3>
		<div class="lead">
	    	<p>Your Operator is not provided from wave money.Please use only telenor number!</p>
	    </div>
	    <div class="row">
	        <button type="submit"><a href="{{url('/')}}">{{trans('app.try_again')}}</a></button>
	    </div>
	</div>
@stop