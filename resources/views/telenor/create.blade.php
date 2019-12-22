@extends('layouts.telenor.master')
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
  fbq('track', 'TelenorCreateMsisdn');
</script>
@stop
@section('content')
    @if(session('exception'))            
    <span style="color: red;">  
        <center><h4>{{ session('exception') }}</h4></center>
    </span>            
    @endif

    @if(session('message'))            
    <span  style="color: green;">                
        <center><h4>{{ session('message') }}</h4></center>
    </span>            
    @endif

    @if(count($errors) > 0)
    @foreach ($errors->all() as $error)
    <span style="color: red;">
        <center><strong>{{++$loop->index}}. {{ $error }}</strong></center>
    </span>
    @break;
    @endforeach
    @endif

    <div class="wrap-sm text-center">
        <form action="{{url('telenor')}}" method="post" >
            {{csrf_field()}}
            <div class="row input-lg">
                <input type="tel" class="form-control" id="number" placeholder="{{ trans('app.number_placeholder') }}" name="mobile_number" maxlength="12" minlength="10" autofocus required>
                <input type="hidden" id="country_code" name="country_code" value="+95" id="country_code">
            </div>
            <div class="row">
                <button type="submit">Next</button>
            </div>
        </form>
    </div>
@endsection
