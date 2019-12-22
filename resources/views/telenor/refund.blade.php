@extends('layouts.telenor.master')
@section('content')
    <div class="wrap-sm text-center">
        <br><br>
        <h3>
            <span class="mm-zawgyione"><b><strong>Refund</strong></b></span>
        </h3>
        <form method="post" action="{{url('/refund')}}">
            {{csrf_field()}}
            <div class="row input-lg">
                <input class="mm-zawgyione" type="numer" name="msisdn" maxlength="12" placeholder="Enter Msisdn" />
            </div>
            <div class="row input-lg">
                <input class="mm-zawgyione" type="text" name="serverRefCode" placeholder="Enter Reference Code" />
            </div>
            <div class="row">
                <button type="submit" class="btn-submit"><span class="mm-zawgyione">Refund</span></button>
            </div>
        </form>
    </div>
@endsection
