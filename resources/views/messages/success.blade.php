@extends('layouts.telenor.master')
@section('title', 'Go|Games')
@section('fb_pixel')
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '624650578052506');
  fbq('track', 'WaveRegComplete');
</script>
@stop
@section('content')

<div class="wrap-sm text-center">
    <svg class="icon icon-lg icon-iconmonstr-smiley-happy"><use xlink:href="ico/symbol-defs.svg#icon-iconmonstr-smiley-happy"></use></svg>
    <h3>Yay!</h3>
    <div class="lead">
        <p>
    	 	@if($subscriber->id == 1)
         	You have successfully subscribed to GoGames at {{$subscriber->amount}} Ks for {{$subscriber->name}}.
         	@else
         		You have successfully subscribed to GoGames at {{$subscriber->amount}} Ks for {{$subscriber->name}} and you have {{$subscriber->extra_week}}(s) free.
         	@endif
        </p>
    </div>
    <div class="row">
        <button type="submit"><a class="btn-submit" href="{{route('wm.web.continue')}}"><span class="mm-zawgyione">{{trans('app.continue')}}</span></a></button>
    </div>
</div>
@stop