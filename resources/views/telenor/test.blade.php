<!DOCTYPE HTML>
<html>
    <head>
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
        </style>
    </head>
    <body>
        
    </body>
    <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript">
        var ip;
        $.getJSON('http://www.geoplugin.net/json.gp?jsoncallback=?', function(data) {
            // console.log(JSON.stringify(data, null, 2));
            var rangeCheck = require('range_check');
            var ip = data.geoplugin_request;
            console.log(rangeCheck.inRange(ip, ['10.0.0.0/8', '192.0.0.0/8']));
        });

        
    </script>
</html>
