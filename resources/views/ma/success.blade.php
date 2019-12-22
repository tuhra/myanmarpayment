@extends('layouts.telenor.master')
@section('title', 'Go|Games')
@section('content')

<div class="wrap-sm text-center">
    <svg class="icon icon-lg icon-iconmonstr-smiley-happy"><use xlink:href="ico/symbol-defs.svg#icon-iconmonstr-smiley-happy"></use></svg>
    <h3>Yay!</h3>
    <div class="lead">
        <p>
            @if($data->status_code == 0)
                <span class="mm-zawgyione">{{trans('app.sub_success_message')}}</span>
            @elseif($data->status_code == 2084)
                <span class="mm-zawgyione">{{trans('app.already_exist')}}</span>
            @endif
        </p>
    </div>
    <div class="row">
        <a class="btn-submit" href="{{ url('/mpt/ma/continue') }}" style="width: 200px;"><span class="mm-zawgyione">{{trans('app.continue')}}</span></a>
    </div>
</div>
@stop