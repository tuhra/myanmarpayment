<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>@yield('title')</title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('public/css/style.css')}}">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Source+Serif+Pro" rel="stylesheet">
   
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700">
</head>
<body style="background-color:#F5F5F5">
@yield('content')
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>	
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {

        $('.alert').delay(3000).hide(0); 
        
        $(document).on('click', '#deactivate', function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                  var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.confirm({
                title: 'Confirmation !',
                content: 'Are you sure?. You want to deactivate this MSISDN.',
                buttons: {
                    confirm: function () {
                        var user_id = $("#user_id").val()
                        $.get('deActivate', {user_id: user_id}).done(function (response) {
                            console.log(response);
                            if (response.status == "success") {
                                window.location.href = '{{route("deActivated")}}'
                            } else {
                                window.location.href = '{{route("dashboard")}}'
                            }
                        })
                    },
                    cancel: function () {
                        
                    }
                }
            });
        })

        $(document).on('click', '#dnd', function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

              var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.confirm({
                title: 'Confirmation !',
                content: 'Are you sure?. You want to deactivate and block this MSISDN.',
                buttons: {
                    confirm: function () {
                        var user_id = $("#user_id").val()
                        $.get('deActivate', {user_id: user_id}).done(function (response) {
                            if (response.status == "success") {
                                window.location.href = '{{route("deActivated")}}'
                            } else {
                                window.location.href = '{{route("dashboard")}}'
                            }
                        })
                    },
                    cancel: function () {
                        
                    }
                }
            });
        })
    })
</script>

</body>
</html>