@extends('admin.layouts.master')
@section('title', 'Dashboard')
@section('content')
<?php 
    $digital = array('softbinder', 'mobifreak');
?>
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
                    @if(isset($message) && $message != null)
                      <div class="alert <?php echo $message['status'] ? 'alert-success' : 'alert-danger'?>" role="alert"><?php echo $message['message']?></div>
                    @endif
                    <form class="form-inline" action="{{route('search')}}">
                      <div class="form-group">
                        <label for="msisdn">Search By MSISDN:</label>
                        <input type="text" class="form-control" id="msisdn" name="msisdn" placeholder="eg: 959xxxxxxxxxx" required>
                        <button type="submit" class="btn btn-default">Search</button>
                      </div>
                    </form>
                    @if(isset($user) && $user != null) 
                        <h4>MSISDN: <?php echo $user->plain_msisdn ?></h4>
                        @if(!empty($user->subscriber))
                        <h4>Subscription Status: <?php echo $user->subscriber->is_subscribed ? "Active" : "InActive" ?></h4>
                            @if($user->subscriber->is_subscribed)
                                <input type="hidden" id="user_id" name="user_id" value="{{$user->subscriber->user_id}}">
                                <button type="button" class="btn btn-danger" id="deactivate">Deactivate</button>
                            @endif
                        @endif
                        <a href="{{route('dashboard')}}" class="btn btn-default">Cancel</a>
                    @endif
                    <hr>
                    @if(!empty($sublogs))
                    <table class="table table-responsive">
                        <thead>
                            <th>#</th>
                            <th>Channel Name</th>
                            <th>Subscribed/Unsubscribed</th>
                            <th>Date</th>
                        </thead>
                        <tbody>
                            <?php $index = 1;?>
                            @foreach($sublogs as $sublog)
                                <tr>
                                    <td>{{ $index ++}}</td>
                                    <td>{!! in_array(strtolower($sublog->name), $digital) ? 'Digital' : $sublog->name !!}</td>
                                    <td>
                                        <?php
                                            echo strtolower($sublog->event);
                                        ?>
                                    </td>
                                    <td>{!! $sublog->created_at !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
