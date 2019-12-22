@extends('layouts.telenor.master')
@section('title', 'Wave|Money')
@section('content')

    <div class="wrap-sm text-center">
        <form id="setup">
            <div class="row input-lg">
                <input type="tel" class="form-control" id="number" placeholder="{{ trans('app.number_placeholder') }}" required='true' name="phone_number" maxlength="11">
            </div>
            <div class="row">
                <button type="submit">Next</button>
            </div>
        </form>
    </div>
    
@stop
