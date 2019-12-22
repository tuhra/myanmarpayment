@extends('layouts.telenor.master')
@section('title', 'Wave|Money')
@section('content')

    <div class="wrap-sm text-center">
        <form id="setup">
            <div class="row input-lg">
                <input type="tel" class="form-control" id="number" placeholder="{{ trans('app.number_placeholder') }}" required='true' name="phone_number" maxlength="11">
                <input type="hidden" id="app_id" name="app_id" value="{{config('customauth.FB_ACCOUNT_KIT_APP_ID')}}">
                <input type="hidden" id="country_code" name="country_code">
                <input type="hidden" id="redirect" name="redirect" value="{{url('/operator/fbkit/verify')}}">
                <input type="hidden" id="state" name="state" value="{{ csrf_token() }}">
                <input type="hidden" id="fbAppEventsEnabled" name="fbAppEventsEnabled" value=true>
            </div>
            <div class="row">
                <button type="submit">Next</button>
            </div>
        </form>
    </div>
    
@stop
