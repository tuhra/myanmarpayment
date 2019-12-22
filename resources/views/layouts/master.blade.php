<!DOCTYPE HTML>
<html>
    <head>
        <title>GoGames | Myanmar</title>
	<link rel="icon" href="http://login.gogamesapp.com/public/logo/icon.png">
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('css/styles.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('css/my.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('stylesheets/typography.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('stylesheets/main.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('intl-tel-input/build/css/intlTelInput.css')}}" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/css/intlTelInput.css">
        <link rel="stylesheet" type="text/css" href="{{url('css/homestyle.css')}}">
        
</head>
<body>
    <div class="nav-option">
        @include('layouts.navbar')
    </div>

    <div class="text-center">            
        <img src="{{url('img/gogames-logo.png')}}" alt="Go|Games"  class="logo"/>
    </div>

    @yield('content')

    <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('intl-tel-input/build/js/intlTelInput.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/material.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js">
    </script>
    @yield('scripts')
    <script type="text/javascript">
        $('.alert').delay(3000).hide(0);
    </script>

</body>
</html>










