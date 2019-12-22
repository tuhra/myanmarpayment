<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{asset('public/logo/icon.png')}}">
    <title>@yield('title')</title>
	  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{url('css/bootstrap-material-design.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('css/ripples.min.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/css/intlTelInput.css">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{url('css/app.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" type="text/css" href="{{url('css/my.css')}}">
</head>
<body>

@include('layouts.navbar')
<div class="container-fluid" style="margin-top:50px">
  @yield('content')
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.6/js/intlTelInput.js"></script>  -->
<script type="text/javascript" src="{{url('plugin/build/js/intlTelInput.js')}}"></script>
<script type="text/javascript" src="{{asset('js/material.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/app.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-92002864-1', 'auto');
  ga('send', 'pageview');
</script>
<script type="text/javascript">
 
    $(document).ready(function() {
        $(document).on('click', '#check', function () {
            var type = $("input[name=operator]:checked").val();
            if (type == 'telenor') {
               window.location.href = "{{url('/telenor')}}";
            } else if (type == 'wavemoney') {
                window.location.href = "{{url('/wavesubscribe')}}";
            } else {
                swal("oop...", "Something wrong", "error")
            }
        }) 

   })
</script>

</script>
</body>
</html>