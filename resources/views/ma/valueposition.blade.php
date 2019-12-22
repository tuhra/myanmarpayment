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
        </style>
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
                    <li><span class="mm-zawgyione">
                    <span class="mpt_sub_price"><b>{{ trans('app.mpt_sub_price') }}</b></span>
                    {{ trans('app.mpt_sub_desc_four') }}</span></li>
                    <li><span class="mm-zawgyione">{{ trans('app.sub_desc_four') }}</span></li>
                </ul>
                <div class="row">
                    <div class="row">
                        <a class="btn-submit" href="{{ url('/mpt/ma/inapptandc') }}" style="width: 200px;"><span class="mm-zawgyione">{{trans('app.nextButton')}}</span></a>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('intl-tel-input/build/js/intlTelInput.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</html>
