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
  fbq('track', 'MptOTP');
</script>
@stop
@section('content')
    <div class="wrap-sm text-center">
        <br><br>
        <h3>
            <span class="mm-zawgyione"><b><strong>{{ trans('app.verify_your_number') }}</strong></b></span>
        </h3>
        <div class="lead">
            <p>
                @if(Session::get('locale') == 'en')
                <span class="mm-zawgyione">{{trans('app.otp_text')}} +{{Session::get('msisdn')}}</span>
                @else
                    <span class="mm-zawgyione">သင့္ဖုန္းနံပါတ္ +{{Session::get('msisdn')}} သို့ ဂဏန္းေလးလံုး SMS ပို့ျပီးျဖစ္ပါသည္။</span>
                @endif
            </p>
            <p style="color: red;">
                <span class="mm-zawgyione"> 
                @if($errors->any())
                    {{ $errors->first() }}
                @endif
                </span>
            </p>
        </div>
        <form method="post" action="{{url('/mpt/otp')}}">
            {{csrf_field()}}
            <div class="row input-lg">
                <input class="mm-zawgyione" type="numer" name="pin" maxlength="4" placeholder="{{trans('app.pinPlaceholder')}}" />
            </div>
            <div class="row">
                <button type="submit" id="click_prevent" class="btn-submit"><span class="mm-zawgyione">{{trans('app.verifyButton')}}</span></button>
            </div>
            <div class="row">
                <span class="mm-zawgyione">{{ trans('app.dont_get') }} 
                <a name="resent" id="mpt-resend" style="font-size: 14px;"><span class="mm-zawgyione"><u><strong>{{trans('app.sent_again')}}</u></strong></em></a></span>
            </div>
            <input type="hidden" name="url" id="mpturl" data-url="{{url('/mpt/resent') }}">
        </form>
    </div>
@endsection
