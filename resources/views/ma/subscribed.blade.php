@extends('layouts.telenor.master')
@section('title', 'Go|Games')
@section('content')

<div class="wrap-sm text-center">
    <svg class="icon icon-lg icon-iconmonstr-smiley-happy"><use xlink:href="ico/symbol-defs.svg#icon-iconmonstr-smiley-happy"></use></svg>
    <h3>Yay!</h3>
    <div class="lead">
        <p>
         	<span class="mm-zawgyione">{{trans('app.already_exist')}}</span>
        </p>
    </div>
    <div class="row">
        <button type="submit"><a class="btn-submit" href="{{route('wm.web.continue')}}"><span class="mm-zawgyione">{{trans('app.continue')}}</span></a></button>
    </div>
</div>
@stop
