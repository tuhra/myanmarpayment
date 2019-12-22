<!DOCTYPE HTML>
<html>
    <head>
        <!-- TrafficGuard tag (tgtag.js) -->
        <ins data-x="trafficguard" data-type="pageview" data-trafficguard=""> </ins>
        <script id="trafficguard" async type="text/javascript" src="http://delivery.trafficguard.ai/tgtag?property_id=tg-000165-003"> </script>
        <title>GoGames | Myanmar</title>
	<link rel="icon" href="http://login.gogamesapp.com/public/logo/icon.png">
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="{{asset('css_telenor/styles.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('stylesheets/typography.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('stylesheets/main.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('intl-tel-input/build/css/intlTelInput.css')}}" rel="stylesheet">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <style type="text/css">
            .image-logo {
                margin: -50px;
            }
        </style>
        
        <!-- Facebook Pixel Code -->
        @yield('fb_pixel')
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
    <div class="text-center image-logo">
        <img src="{{url('img/gogames-logo.png')}}" style="width:180px;" alt="Go|Games"  class="logo"/>
    </div>

    @include('layouts.navbar')

    @yield('content')

    <input type="hidden" name="ott" id="ott" value="{{ SESSION::get('ott') }}">

    <script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('intl-tel-input/build/js/intlTelInput.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/svgxuse.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script type="text/javascript">

$('input[type="radio"]').click(function () {
    if ($(this).is(':checked')) {
        if ($(this).val() == 'telenor') {
            window.location.href = "{{url('/valueposition')}}";
            // window.location.href = "{{url('/telenorconsent')}}";
        } else if ($(this).val() == 'wavemoney') {
            window.location.href = "{{url('/wavesubscribe')}}";
        } else if ($(this).val() == 'mpt') {
            var ott = $('#ott').val();
            if (ott === "") {
                window.location.href = "{{ url('/mpt/ma/webvalueposition') }}";
            } else {
                window.location.href = "{{ url('/mpt/ma/valueposition') }}";
            }
        } else {
            // alert('please select one');
            // swal("oop...", "Something wrong", "error")

            var currentCallback;

            // override default browser alert
            window.alert = function (msg, callback) {
                $('.message').text(msg);
                $('.customAlert').css('animation', 'fadeIn 0.3s linear');
                $('.customAlert').css('display', 'inline');
                setTimeout(function () {
                    $('.customAlert').css('animation', 'none');
                }, 300);
                currentCallback = callback;
            }

            $(function () {

                // add listener for when our confirmation button is clicked
                $('.confirmButton').click(function () {
                    $('.customAlert').css('animation', 'fadeOut 0.3s linear');
                    setTimeout(function () {
                        $('.customAlert').css('animation', 'none');
                        $('.customAlert').css('display', 'none');
                    }, 300);
                    currentCallback();
                })

                // our custom alert box
                setTimeout(function () {
                    alert('                       Please Select operator                  ', function () {
                        console.log("Callback executed");
                    });
                }, 500);
            });
        }
    }
});

    </script>
</body>
</html>










