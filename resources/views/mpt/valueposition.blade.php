<!DOCTYPE HTML>
<html>
    <head>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-92002864-7"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-92002864-7');
        </script>
        <title>GoGames | Myanmar</title>
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
            .mpt_sub_price {
                color: purple;
                font-weight: normal;
                font-style: normal;
            }
            html,
            body {
              width: 100%;
              height: 100%;
              margin: 0;
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
  fbq('track', 'MptSubPage');
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
                <h3><span class="mm-zawgyione">{{ trans('app.playnow') }}</span></h3>
                <ul>
                    <li><span class="mm-zawgyione">{{ trans('app.sub_desc_one') }}</span></li>
                    <li><span class="mm-zawgyione">{{ trans('app.sub_desc_two') }}<span></span></li>
                    <li><span class="mm-zawgyione">{{ trans('app.mpt_sub_desc_three') }}
                    <span class="mpt_sub_price"><b>{{ trans('app.mpt_sub_price') }}</b></span>
                    {{ trans('app.mpt_sub_desc_four') }}</span></li>
                    <li><span class="mm-zawgyione">{{ trans('app.sub_desc_four') }}</span></li>
                </ul>
                <div class="row">
                    <form method="POST" action="{{url('/mpt/sendotp')}}">
                        {{csrf_field()}}
                        <div class="row input-lg">
                            <input type="tel" class="form-control mm-zawgyione" id="number" style="font-size: 10px;" placeholder="{{ trans('app.number_placeholder') }}" required='true' name="msisdn" maxlength="11">
                        </div>
                        <div class="row">
                            <button type="submit"><span class="mm-zawgyione">{{ trans('app.nextButton') }}</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('intl-tel-input/build/js/intlTelInput.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</html>




