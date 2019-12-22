@extends('layouts.telenor.master')
@section('title', 'Wave|Money')
@section('content')
    <div class="wrap-sm text-center" style="margin-top: 200px;">
        <form action="{{ url('wave') }}" method="POST">
            {{csrf_field()}}
            <div class="row input-lg">
                @if(Session::get('wavepay_msisdn'))
                    <input type="tel" class="form-control mm-zawgyione" id="number" style="font-size: 10px;" placeholder="{{ trans('app.number_placeholder') }}" value="+{{ Session::get('wavepay_msisdn') }}" required='true' name="number" maxlength="11" disabled>
                @else
                    <input type="tel" class="form-control mm-zawgyione" id="number" style="font-size: 10px;" placeholder="{{ trans('app.number_placeholder') }}" required='true' name="number" maxlength="11">
                @endif
                <input type="hidden" id="country_code" name="country_code" value="+95">
            </div>
            <div class="row">
                <button type="submit"><span class="mm-zawgyione">{{ trans('app.nextButton') }}</span></button>
            </div>
        </form>
    </div>
@stop
