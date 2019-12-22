<!DOCTYPE HTML>
<html>
    <head>
        <title>Telenor</title>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="{{ asset('css_telenor/price.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{asset('stylesheets/typography.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('stylesheets/main.css')}}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
        <style type="text/css">
            .image-logo {
                margin: -50px;
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
                <div class="row">
                    @if(SESSION::has('message'))
                    <div class="alert-success" role="alert">{{ Session::get('message' )}}</div>
                    @endif
                    <form action="{{url('block')}}" method="post" >
                        {{csrf_field()}}
                        <div class="row input-lg">
                            <input type="number" class="form-control mm-zawgyione" id="number" style="font-size: 10px;" placeholder="Enter Msisdn" name="msisdn" autofocus required>
                        </div>
                        <div class="row">
                            <button type="submit"><span class="mm-zawgyione">Submit</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('sweet::alert')
    </body>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>
    <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('js/app.js')}}"></script>
</html>
