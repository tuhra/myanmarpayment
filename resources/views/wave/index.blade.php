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
  fbq('track', 'WavePackage');
</script>
@stop
@section('content')
    <div class="wrap-sm text-center">
        <h1>Get it Now</h1>
    </div>
    @include('flash::message')
    <div class="wrap">
        <div class="row cards">

            @foreach($types as $type)

                <div class="card">
                    <?php
                        $days = $type->validity + $type->extra;
                        $week = $days/7;
                    ?>
                    @if($type->id == 1)
                        <h3>Standard</h3>
                        <div class="card-title"><h3>{{$week}} Week</h3></div>
                    @elseif($type->id == 2)
                        <h3>Saver</h3>
                        <div class="card-title"><h3>{{$week}} Weeks</h3></div>
                    @else 
                        <h3>Super Saver</h3>
                        <div class="card-title"><h3>{{$week}} Weeks</h3></div>
                    @endif
                        @if($type->extra !== 0)
                            <div class="card-label">{{$type->extra_week}} Free</div>
                                Only pay for <strong> {{$type->name}}</strong>
                        @endif
                    <div class="card-option">
                        <div>{{$type->amount}} Ks</div>
                        <a href="{{url('/subscribe', $type->id)}}" class="btn" style="width: 100px; height: 42px;"><div style="padding-left: -20px; font-size: 15px;"><center>{{trans('app.subscribeButton')}}</center></div></a>
                    </div>
                </div>

            @endforeach

        </div>
    </div>
@stop








