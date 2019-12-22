@extends('admin.layouts.master')
@section('title', 'Unsubscribers')
@section('content')
<div class="container" style="margin-top:100px">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading text-right">
                <h3 class="text-center">Go|Games CRM Portal</h3>
                @if(Session::get('operator') == 1)
                <a href="{{route('dashboard')}}">Dashboard</a>
                &nbsp;&nbsp;&nbsp;
                <a href="{{route('dnd')}}">Dnd</a>
                &nbsp;&nbsp;&nbsp;
                <a href="{{route('unsublist')}}">Unsubscribers</a>
                @endif
                &nbsp;&nbsp;&nbsp;
                <a href="{{route('logout')}}"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>&nbsp;Logout</a>
                </div>
                <div class="panel-body">
                <hr>
                    <table class="table table-responsive">
                        <thead>
                            <th>#</th>
                            <th>MSISDN</th>
                            <th>Channel Name</th>
                            <th>Date</th>
                        </thead>
                        <tbody>
                            <?php $index = 1;?>
                                @foreach($lists as $list)
                                    <tr>
                                        <td>{{ $index ++}}</td>
                                        <td>{{ $list->plain_msisdn}}</td>
                                        <td>CC</td>
                                        <td>{{ $list->created_at }}</td>
                                    </tr>
                                @endforeach
                        </tbody>
                    </table>
                    <div class="pull-right">{{ $lists->render() }}</div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
