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
  fbq('track', 'WavePromotion');
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
        @if(Session::get('locale') == 'en')
            <h3>Thingyan Limited Time Offer <br>
            Extra FREE weeks!</h3>
        @else
            <h1 style="font-size: 20px;">အခ်ိန္အကန္႔အသတ္<br/>ရက္အပို လက္ေဆာင္</h1>
        @endif
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
                    <div class="card-label">
                        @if(Session::get('locale') == 'en')
                        {{$type->extra_week}} {{trans('app.free')}}
                        @else
                            {{$type->mm_extra_week}} {{trans('app.free')}}
                        @endif
                    </div>
                        <p style="font-size: 15px;">
                            @if(Session::get('locale') == 'en')
                                {{ $type->name }} + {{$type->extra_week}} {{ strtoupper(trans('app.free'))}}
                            @else
                                {{ $type->mm_name }} + {{$type->mm_extra_week}} {{ strtoupper(trans('app.free'))}}
                            @endif
                        </p>
                    <div class="card-option">
                        <div>
                            @if(Session::get('locale') == 'en')
                                {{$type->amount}} Kyats
                            @else
                                {{$type->mm_amount}} က်ပ္
                            @endif
                        </div>
                        <a href="{{url('/subscribe', $type->id)}}" class="btn" style="width: 100px; height: 42px;"><div style="padding-left: -20px; font-size: 15px;"><center>{{trans('app.subscribeButton')}}</center></div></a>
                    </div>
                </div>
            @endforeach
        </div>
        <center><a href="{{ url('T&C') }}" class="tandc"><u>Terms & Conditions</u></a></center>
    </div>
@stop








