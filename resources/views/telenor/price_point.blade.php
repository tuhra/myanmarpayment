<!DOCTYPE HTML>
<html>
    <head>
        <!-- TrafficGuard tag (tgtag.js) -->
        <ins data-x="trafficguard" data-type="pageview" data-trafficguard=""> </ins>
        <script id="trafficguard" async type="text/javascript" src="http://delivery.trafficguard.ai/tgtag?property_id=tg-000165-003"> </script>

        <title>Telenor</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="{{ asset('css_telenor/price.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{asset('intl-tel-input/build/css/intlTelInput.css')}}" rel="stylesheet">
        <link href="{{asset('stylesheets/typography.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('stylesheets/main.css')}}" rel="stylesheet" type="text/css" />
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <style type="text/css">
            .image-logo {
                margin: -50px;
            }
            .intl-tel-input.separate-dial-code .selected-dial-code {
                display: table-cell;
                vertical-align: middle;
                position: absolute;
                margin-top: 6px;
                margin-left: 30px;
            }
        </style>
        <!-- Facebook Pixel Code -->
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
  fbq('track', 'TelenorPricePoint');
</script>

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
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=624650578052506&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->
        
    </head>
    <body>
        <div class="nav-option">
            @include('layouts.navbar')
        </div>

        <div class="text-center image-logo">
            <img src="{{ asset('tn_image/img/gogames-logo.png') }}" style="width:180px;" alt="Go|Games"  class="logo"/>
        </div>
        <br/>
        <div class="wrap-sm">
            <div class="card">
                <div class="card-img">
                    <img src="{{ asset('tn_image/img/all-games.png') }}" />
                </div>
                <h3><span>{{ trans('app.playnow') }}</span></h3>
                <ul>
                    <li><span>{{ trans('app.hundred_of_games') }}</span></li>
                    <li><span>{{ trans('app.sub_desc_two') }}</span></li>
                    <li><span>{{ trans('app.sub_desc_three') }}</span></li>
                </ul>
                <div class="row">
                    <form action="{{url('telenor')}}" method="post" >
                        {{csrf_field()}}
                        <div class="row input-lg">
                            <input type="tel" class="form-control mm-zawgyione" id="number" style="font-size: 10px;" placeholder="{{ trans('app.number_placeholder') }}" name="mobile_number" maxlength="12" minlength="10" autofocus required>
                            <input type="hidden" id="country_code" name="country_code" value="+95" id="country_code" style="position: absolute; margin-top: 8px; margin-left: -5px;">
                        </div>
                        <div class="row">
                            <button type="submit"><span class="mm-zawgyione">{{ trans('app.subscribeButton') }}</span></button>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div class="header">
                        <h4><span>{{ trans('app.telenor_tanc_head') }}</span></h4>
                    </div>
                        @for ($i=1; $i <= 5; $i++)
                            <span><?php echo trans('app.telenor_tandc_'.$i); ?></span>
                            <br/><br/>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('intl-tel-input/build/js/intlTelInput.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</html>
