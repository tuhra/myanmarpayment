<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="{{asset('public/logo/icon.png')}}">
    <title>GoGames | Myanmar</title>
    <link rel="stylesheet" type="text/css" href="{{url('css/homestyle.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.11/css/intlTelInput.css">
</head>
<body>

  <div class="nav-option">
      @include('layouts.navbar')
  </div>

<div class="container-fluid" style="margin-top:50px">
  @yield('content')
</div>
<script type="text/javascript" src="{{url('js/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.11/js/intlTelInput.min.js"></script> 

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-92002864-1', 'auto');
  ga('send', 'pageview');
</script>
<script type="text/javascript">
    $('.alert').delay(3000).hide(0); 

    $('[type="tel"]').intlTelInput({
        preventInvalidNumbers: true,
    });

    var sel=$('.selected-dial-code').text();
    $('input[name=country_code]').val(sel);
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