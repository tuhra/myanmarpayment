@extends('layouts.master')
@section('title', 'GoGames|Telenor')
@section('content')
<!--================= Telenor Operator Routes START =============
 Developer: Fahad Hossain Howlader-->
<div class="wrap-sm text-center">
    <h3>{{trans('app.pinPlaceholder')}}</h3>

    <!--Exception Message START-->
    @if(session('exception'))            
    <span class="custom-block">                
        <h4>{{ session('exception') }}</h4>
    </span>            
    @endif
    <!--Exception Message END-->
    <!--Message START-->
    @if(session('message'))            
    <span  style="color: green;">                
        <h4>{{ session('message') }}</h4>
    </span>            
    @endif
    <!--Message Message END-->

    <form method="post" action="{{url('/telenor-otp')}}">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-md-4 col-md-offset-2 control-label" for="otp" style="text-align:right; font-size:16px; color: black">{{trans('app.pinLabel')}} #</label>
                <div class="col-md-6">
                    {{csrf_field()}}
                    <input class="form-control" id="otp" type="text" placeholder="{{trans('app.pinPlaceholder')}}" name="pin" maxlength="4" minlength="4" autofocus required>
                    @if ($errors->has('pin'))
                    <span class="custom-block">
                        <strong>{{ $errors->first('pin') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-footer text-center">
            <button type="submit" class="btn-custom btn-lg  btn-raised " id="click_prevent">
                {{trans('app.verifyButton')}}
            </button>
            <input type="button" style="background: chocolate;color: wheat" name="resent" id="resent" class="btn-warning btn-lg btn-raised" value="{{trans('app.resendButton')}}">
            
        </div>
    </form> 
</div>



@stop

@section('scripts')
<script type="text/javascript">

    $(document).ready(function () {
        $(document).on('click', '#resent', function () {            
            $.get('{{url("/telenor-otp-resent")}}', function (res) {
                // console.log(res.status_code === 200);
                if (res.status_code === 200) {
                    location.reload();
                }
                ;
            });
        })
    })
</script>
@stop()






