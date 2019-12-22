@extends('layouts.master')
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
  fbq('track', 'WaveUnSub');
</script>
@stop
@section('content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
      @include('flash::message')
    </div>
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
          <div class="panel-heading">
             <div class="row">
                <div class="col-xs-6 col-xs-offset-3 col-md-6 col-md-offset-4">
                   <img class="img-responsive" src="{{asset('logo/logo.png')}}" width="55%" />
                </div>
              </div>
          </div>
          <div class="panel-body notfound-body">
              <div class="form-panel">
                  <div class="form-panel">
                      <form action="" method="get" id="form">
                          <input type="text" class="form-control" id="plain_msisdn" placeholder="Enter your msisdn" required='true' name="plain_msisdn" maxlength="12">
                      </form>
                  </div>
              </div>
          </div>
        </div>
    </div>
</div>
@stop








