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
  fbq('track', 'WaveMsisdn');
</script>
@stop
@section('content')
<style type="text/css">
  #loadingDiv{
      position:fixed;
      top:0px;
      right:0px;
      width:100%;
      height:100%;
      background-color:#666;
      background-image:url('../images/loading.gif');
      background-repeat:no-repeat;
      background-position:center;
      z-index:10000000;
      opacity: 0.4;
      filter: alpha(opacity=40); /* For IE8 and earlier */
  }
</style>
<div id="loadingDiv" style="display:none;">
    <div>
        
    </div>
</div>
<div class="row">
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
                    <form action="{{url('fbkit/verify')}}" method="get" id="form">
                        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="code" id="code" />
                    <div class="form-group" style="padding-bottom:60px">
                        <label for="number" class="col-md-3 control-label" style="text-align:right; font-size:16px">{{ trans('app.mobileLabel') }} #</label>
                                    <div class="col-md-8">
                                      <input type="text" class="form-control" id="number" placeholder="{{ trans('app.number_placeholder') }}" required='true' name="phone_number" maxlength="11">
                                      @if ($errors->has('phone_number'))
                                        <span class="custom-block">
                                              <strong>{{ trans('auth.failed') }}</strong>
                                        </span>
                                      @endif
                                      <input type="hidden" name="app_id" value="{{config('customauth.FB_ACCOUNT_KIT_APP_ID')}}">
                                      <input type="hidden" name="country_code" id="country_code">
                                      <!-- <input type="hidden" name="redirect" value="{{url('fbkit/verify')}}"> -->
                                      <input type="hidden" name="state" value="{{ csrf_token() }}">
                                      <input type="hidden" name="fbAppEventsEnabled" value=true>
                                    </div>
                              </div>
                          </div>    
                    </div>
               </div>
               <div class="panel-footer">
                     <button onclick="smsLogin();" id="login" class="btn-custom btn-lg btn-block btn-raised">
                    {{ trans('app.nextButton') }}
                    </button>
                </form> 
           </div>    
        </div>
    </div>
</div>
<script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/ack.js')}}"></script>
<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
@stop








