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
        <link href="{{ asset('css/custom.css') }}" rel="stylesheet" type="text/css" />
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
  fbq('track', 'TelenorTanc');
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
        <div class="wrap-sm">
            <div class="card">
                <div class="border">
                    <div class="header">
                        <h4><span>{{ trans('app.telenor_tanc_head') }}</span></h4>
                    </div>
                        @for ($i=1; $i <= 5; $i++)
                            <li><span><?php echo trans('app.telenor_tandc_'.$i); ?></span></li>
                            <br>
                        @endfor
                    </div>
                    <br>

                    @if (Session::has('telenor_he'))
                        <div class="row">
                            <a href="{{url('/hecharge')}}" class="btn-submit"><span>{{ trans('app.accept') }}</span></a>
                        </div>
                        <?php
                            Session::forget('telenor_he');
                        ?>
                    @else 
                        <button type="submit" class="btn-submit"><span><a href="{{ url('telenor-otp') }}" style="color: #FFF;">{{ trans('app.accept') }}</a></span></button>
                    @endif
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('intl-tel-input/build/js/intlTelInput.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</html>




