<!DOCTYPE HTML>
<html>
    <head>
        <title>Telenor</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="{{ asset('css_telenor/price.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{asset('stylesheets/typography.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('stylesheets/main.css')}}" rel="stylesheet" type="text/css" />
    </head>
    <body>

     <div class="nav-option">
         @include('layouts.navbar')
     </div>

        <div class="text-center">
            <img src="{{ asset('tn_image/img/gogames-logo.png') }}" alt="Go|Games"  class="logo"/>
        </div>
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
                    <a href="{{url('/hecharge')}}" class="btn-submit"><span>{{ trans('app.subscribeButton') }}</span></a>
                </div>
                <div class="row">
                    <div class="header">
                        <h4><span>{{ trans('app.telenor_tanc_head') }}</span></h4>
                    </div>
                        @for ($i=1; $i <= 5; $i++)
                            <span><?php echo trans('app.telenor_tandc_'.$i); ?></span>
                            <br>
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
