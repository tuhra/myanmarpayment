@extends('layouts.telenor.master')
@section('title', 'Wave|Money')
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
  fbq('track', 'WaveTandc');
</script>
@stop
<style>
    .tandc {
        font-size: 15px;
        color: gray;
    }
</style>
@section('content')
    <div class="wrap-sm text-center">
        <br>
        <h4>{{trans('app.wave_tnc_header')}}</h4>
    </div>
    <div class="wrap">
        <div class="row cards">
            <p>{{trans('app.wave_tnc_one')}}</p>
            <p>{{trans('app.wave_tnc_two')}}</p>
            <p>{{trans('app.wave_tnc_three')}} {{ $fromDate}} {{trans('app.to')}} {{ $toDate}} {{trans('app.wave_tnc_four')}}</p>
            <p>{{trans('app.wave_tnc_five')}}</p>
            <p>{{trans('app.wave_tnc_six')}}</p>
            <p>{{trans('app.wave_tnc_seven')}}</p>
        </div>
        <center><a href="{{ url('/wavesubscribe') }}" class="tandc"><u>{{trans('app.wave_back')}}</u></a></center>
    </div>
@stop








