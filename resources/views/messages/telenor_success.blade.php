<!DOCTYPE HTML>
<html>
    <head>
        @yield('scripts')
        <title>GoGames | Telenor</title>
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
  fbq('track', 'TelenorSuccess');
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

        <div class="text-center">            
            <img src="{{url('img/gogames-logo.png')}}" alt="Go|Games"  class="logo"/>
        </div>

        <div class="wrap-sm text-center">
            <svg class="icon icon-lg icon-iconmonstr-smiley-happy"><use xlink:href="ico/symbol-defs.svg#icon-iconmonstr-smiley-happy"></use></svg>
            <h3>Yay!</h3>
            <div class="lead">
                <p>
                    <span class="mm-zawgyione">{{trans('app.sub_success_message')}}</span>
                </p>
            </div>
            <div class="row">
                <button type="submit" id="complete"><a href="{{url('/telenor/continue')}}" class="btn-submit"><span class="mm-zawgyione">{{trans('app.continue')}}</span></a></button>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="{{ asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript">
        $('.alert').delay(3000).hide(0); 

        $('input[type="radio"]').click(function () {
            if ($(this).is(':checked')) {
                if ($(this).val() == 'telenor') {
                    window.location.href = "{{url('/valueposition')}}";
                } else if ($(this).val() == 'wavemoney') {
                    window.location.href = "{{url('/wavesubscribe')}}";
                } else if ($(this).val() == 'mpt') {
                    var ott = $('#ott').val();
                    if (ott === "") {
                        window.location.href = "http://miaki-wavem-g-mm.applandstore.com/app";
                    } else {
                        window.location.href = "{{ url('/mpt/valueposition') }}";
                    }
                } else {
                    // alert('please select one');
                    // swal("oop...", "Something wrong", "error")

                    var currentCallback;

                    // override default browser alert
                    window.alert = function(msg, callback){
                      $('.message').text(msg);
                      $('.customAlert').css('animation', 'fadeIn 0.3s linear');
                      $('.customAlert').css('display', 'inline');
                      setTimeout(function(){
                        $('.customAlert').css('animation', 'none');
                      }, 300);
                      currentCallback = callback;
                    }

                    $(function(){
                      
                      // add listener for when our confirmation button is clicked
                        $('.confirmButton').click(function(){
                        $('.customAlert').css('animation', 'fadeOut 0.3s linear');
                        setTimeout(function(){
                         $('.customAlert').css('animation', 'none');
                            $('.customAlert').css('display', 'none');
                        }, 300);
                        currentCallback();
                      })
                      
                      // our custom alert box
                      setTimeout(function(){
                        alert('Please Select operator', function(){
                                console.log("Callback executed");
                            });
                        }, 500);
                    });
                }
            }
        });

        $(document).on('click', '#complete', function () {
            fbq('track', 'CompleteRegistration');
        });

    </script>
</html>
