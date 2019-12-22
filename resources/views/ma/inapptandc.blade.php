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
    </head>
    <style>
        .intl-tel-input.separate-dial-code .selected-dial-code {
            display: table-cell;
            vertical-align: middle;
            position: absolute;
            margin-top: 6px;
            margin-left: 30px;
        }
    </style>
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
                @if(Session::get('he_msisdn'))
                    <p>
                        သင့္ဖုန္းနံပါတ္ +{{ Session::get('he_msisdn') }} အတြက္ ဝန္ေဆာင္မွဳ ရယူ ရန္ Terms and Conditions ကို လက္ခံ ေပးျခင္းအားျဖင့္ အတည္ျပဳ လိုက္ပါ။
                    </p>
                @endif
                <p>
                    <form method="POST" action="{{url('/mpt/ma/sendotp')}}">
                        {{csrf_field()}}
                        @if(!Session::get('he_msisdn'))
                        <div class="row input-lg">
                            <input type="tel" class="form-control mm-zawgyione" id="number" style="font-size: 10px;" placeholder="{{ trans('app.number_placeholder') }}" required='true' name="msisdn" maxlength="11">
                        </div>
                            <span class="mm-zawgyione">{{ trans('app.msisdn_text') }}</span>
                        @else
                            <div class="row input-lg">
                                <input type="tel" class="form-control mm-zawgyione" id="number" style="font-size: 10px;" placeholder="{{ trans('app.number_placeholder') }}" required='true' name="msisdn" maxlength="11" value="+{{Session::get('he_msisdn')}}">
                            </div>
                                <span class="mm-zawgyione">{{ trans('app.msisdn_text') }}</span>
                        @endif
                </p>

                
                <div class="border">
                    <div class="header">
                        <span class="mm-zawgyione"><b>Terms & Conditions</b></span>
                    </div>
                    <p>{{trans('app.mpt_one')}}</p>
                    <p>{{trans('app.mpt_two')}}</p>
                    <p>{{trans('app.mpt_three')}}</p>
                    <p>{{trans('app.mpt_four')}}</p>
                    <p>{{trans('app.mpt_five')}}</p>
                    <p>{{trans('app.mpt_six')}}</p>
                    <p>{{trans('app.mpt_seven')}}</p>
                    <p>{{trans('app.mpt_eight')}}</p>
                    <p>{{trans('app.mpt_nine')}}</p>
                    <p>MPT အခေပးေခ်မႈစည္းမ်ဥ္းစည္းကမ္းမ်ား အျပည့္အစုံကို ဤတြင္ ေတြ႔ရွိႏိုင္ပါသည္။ <a href="{{ url('/mpt/ma/fulltandc') }}"> <strong><u>ပိုမိုသိရွိရန္</u></strong></a></p>
                    <div id="full">
                        
                    </div>
                    <center>
                        <!-- <a id="showall"><u>Show All</u></a>
                        <a id="showless"><u>Show Less</u></a> -->
                    </center>
                    <p style="color: red;">
                        <span class="mm-zawgyione"> 
                        @if(isset($message))
                            {{ $message['message'] }}
                        @endif
                        </span>
                    </p>
                    <div class="row">
                        <button type="submit" id="click_prevent" class="btn-submit"><span class="mm-zawgyione">လက္ခံပါသည္။</span></button>
                    </div>
                </div>
                <!-- <div class="row">
                    <button type="submit"><span class="mm-zawgyione">{{ trans('app.nextButton') }}</span></button>
                </div> -->
                </form>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('intl-tel-input/build/js/intlTelInput.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</html>




