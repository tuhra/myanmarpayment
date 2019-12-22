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
  fbq('track', 'WaveExpire');
</script>
@stop
@section('content')
<div class="wrap-sm text-center">
	<br><br><br>
	<h4>{{trans('app.wave_expire_title')}}</h4>
	<div class="lead">
    	<p><span class="mm-zawgyione">{{trans('app.wave_expire_text')}}</span></p>
    </div>
    <div class="row">
        <a href="{{url('/wavesubscribe')}}" class="btn-submit"><span class="mm-zawgyione">{{trans('app.subscribeButton')}}</span></a>
    </div>
</div>
@stop