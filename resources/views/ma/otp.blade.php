@extends('layouts.telenor.master')
@section('content')
    <style>
        .isDisabled {
            cursor: not-allowed;
        }
    </style>
    <div class="wrap-sm text-center">
        <br><br>
        <h3>
            <span class="mm-zawgyione"><b><strong>{{ trans('app.verify_your_number') }}</strong></b></span>
        </h3>
        <div class="lead">
            <p>
                <?php
                    if(Session::get('he_msisdn')) {
                        $msisdn = Session::get('he_msisdn');
                    }

                    if(getMsisdn()) {
                        $msisdn = Session::get('msisdn');
                    }
                ?>
                @if(Session::get('locale') == 'en')
                <span class="mm-zawgyione">{{trans('app.otp_text')}} + {{$msisdn}}</span>
                @else
                    <span class="mm-zawgyione">သင့္ဖုန္းနံပါတ္ + {{$msisdn}} သို့ ဂဏန္းေလးလံုး SMS ပို့ျပီးျဖစ္ပါသည္။</span>
                @endif
            </p>
            <p style="color: red;">
                <span class="mm-zawgyione"> 
                @if(isset($message))
                    {{ $message['message'] }}
                @endif
                </span>
            </p>
        </div>
        <form method="post" action="{{url('/mpt/ma/postotp')}}">
            {{csrf_field()}}
            <div class="row input-lg">
                <input class="mm-zawgyione" type="numer" name="pin" maxlength="4" placeholder="{{trans('app.pinPlaceholder')}}" />
                <br>
                <span>{{trans('app.otp_info')}}</span>
            </div>
            <div class="row">
                <button type="submit" id="click_prevent" class="btn-submit"><span class="mm-zawgyione">{{trans('app.verifyButton')}}</span></button>
            </div>
            <div class="row">
                <span class="mm-zawgyione">{{ trans('app.dont_get') }} 
                <a name="resent" id="mptmaresend" style="font-size: 14px;"><span class="mm-zawgyione"><u><strong>{{trans('app.sent_again')}}</u></strong></em></a></span>
                <input type="hidden" name="url" id="maurl" data-url="{{url('/mpt/ma/otpregeneration') }}">
            </div>
        </form>
    </div>
    <script type="text/javascript" src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '#mptmaresend', function () {
                var url = $('#maurl').attr('data-url');
                var _token=$("input[name=_token]").val();
                $.ajax({
                    type : "POST",
                    url : url,
                    data: {_token:_token},
                    success: function(response) {
                        if (response.status === true) {
                            location.reload();
                        }
                    }
                })
            })
        })
    </script>
@endsection






