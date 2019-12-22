<!DOCTYPE html>
<html>
<head>
    <title>Wave Dashboard</title>
    <style>
        label {
            float: left;
        }
        @import url("http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,300,700");
        @import url("http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600|Roboto Mono");

        @font-face {
          font-family: 'Dosis';
          font-style: normal;
          font-weight: 300;
          src: local('Dosis Light'), local('Dosis-Light'), url(http://fonts.gstatic.com/l/font?kit=RoNoOKoxvxVq4Mi9I4xIReCW9eLPAnScftSvRB4WBxg&skey=a88ea9d907460694) format('woff2');
        }
        @font-face {
          font-family: 'Dosis';
          font-style: normal;
          font-weight: 500;
          src: local('Dosis Medium'), local('Dosis-Medium'), url(http://fonts.gstatic.com/l/font?kit=Z1ETVwepOmEGkbaFPefd_-CW9eLPAnScftSvRB4WBxg&skey=21884fc543bb1165) format('woff2');
        }
        body {
          background: #d2d6de;
            font-family: 'Source Sans Pro', 'Helvetica Neue', Arial, sans-serif,  Open Sans;
            font-size: 14px;
            line-height: 1.42857;
            height: 350px;
            padding: 0;
            margin: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-weight: 400;
            overflow-x: hidden;
            overflow-y: auto;
            
        }
        .form-control {
            background-color: #ffffff;
            background-image: none;
            border: 1px solid #999999;
            border-radius: 0;
            box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
            color: #333333;
            display: block;
            font-size: 14px;
            height: 34px;
            line-height: 1.42857;
            padding: 6px 12px;
            transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
            width: 100%;
        }

        .login-box, .register-box {
            width: 360px;
            margin: 7% auto;
        }.login-page, .register-page {
            background: #d2d6de;
        }

        .login-logo, .register-logo {
            font-size: 35px;
            text-align: center;
            margin-bottom: 25px;
            font-weight: 300;
        }.login-box-msg, .register-box-msg {
            margin: 0;
            text-align: center;
            padding: 0 20px 20px 20px;
        }.login-box-body, .register-box-body {
            background: #fff;
            padding: 20px;
            border-top: 0;
            color: #666;
        }.has-feedback {
            position: relative;
        }
        .form-group {
            margin-bottom: 15px;
        }.has-feedback .form-control {
            padding-right: 42.5px;
        }.login-box-body .form-control-feedback, .register-box-body .form-control-feedback {
            color: #777;
        }
        .form-control-feedback {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 2;
            display: block;
            width: 34px;
            height: 34px;
            line-height: 34px;
            text-align: center;
            pointer-events: none;
        }.checkbox, .radio {
            position: relative;
            display: block;
            margin-top: 10px;
            margin-bottom: 10px;
        }.icheck>label {
            padding-left: 0;
        }
        .checkbox label, .radio label {
            min-height: 20px;
            padding-left: 20px;
            margin-bottom: 0;
            font-weight: 400;
            cursor: pointer;
        }
    </style>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap-theme.min.css" integrity="sha384-6pzBo3FDv/PJ8r2KRkGHifhEocL+1X2rVCTTkUfGk7/0pbek5mMa1upzvWbrUbOZ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
</head>
<body>

    <div class="container">
        <div class="login-box">
            <div class="login-logo">
                <img src="{{url('img/gogames-logo.png')}}" style="width:180px;" alt="Go|Games"  class="logo"/>
            </div>

            <div class="login-box-body">

                @include('flash::message')

                <p class="login-box-msg">Sign in to start your session</p>

                <form action="{{ url('/wave/promotion') }}" method="post" accept-charset="utf-8"> 
                    {{csrf_field()}}       
                    <div class="form-group has-feedback">
                        <div class='input-group date' id='startDate'>
                            <input type='text' class="form-control" name="fromDate" required placeholder="Promotion start date" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                    <div class="form-group has-feedback">
                        <div class='input-group date' id='endDate'>
                            <input type='text' class="form-control" name="toDate" required placeholder="Promotion end date" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group has-feedback">
                        @if(TRUE == $promotion->is_promotion)
                            <input type="checkbox" name="is_promotion" value="1" checked> Promotion
                        @else
                            <input type="checkbox" name="is_promotion" value="1"> Promotion
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-8"></div>
                        <div class="col-xs-4">
                            <input type="submit" value="Update" id="submit" class="btn btn-primary btn-block btn-flat">
                        </div><!-- /.col -->
                    </div>
                </form>
            </div>


</body>
<script type="text/javascript" src="{{asset('js/jquery.min.js')}}"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript">
    $(function () {
        $('#startDate').datetimepicker({
            format: 'YYYY-MM-DD',
        });
        $('#endDate').datetimepicker({
            format: 'YYYY-MM-DD',
        });
    });
</script>
</html>
    
    
    <!-- <div class="wrap-sm text-center" style="margin-top: 100px;">
        <form action="{{ url('wave') }}" method="POST">
            {{csrf_field()}}
            <div class="row input-lg">
                <label><b>Start Date</b></label>
                <input type="text" class="form-control mm-zawgyione" id="fromDate" placeholder="Start Date" required='true' name="fromDate">
            </div>
            <div class="row input-lg">
                <label><b>End Date</b></label>
                <input type="text" class="form-control mm-zawgyione" id="toDate" placeholder="End Date" required='true' name="toDate">
            </div>
            <div class="row input-lg">
                    <label><b>Promotion</b></label>
                    <input type="checkbox" name="vehicle" value="Bike">
            </div>
            <div class="row">
                <button type="submit"><span class="mm-zawgyione">Update</span></button>
            </div>
        </form>
    </div> -->
    




