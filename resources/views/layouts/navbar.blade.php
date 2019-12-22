<div class="nav-option">
    <form class="navbar-form navbar-right" method="post" action="{{url('language') }}">
    {{ csrf_field() }}
        <select class="form-control" name="language" id="language" data-url="{{url('language') }}">
            <option value="en" {{Config::get('app.locale')=='en'?'selected':''}}>English</option>
            <option value="mm" {{Config::get('app.locale')=='mm'?'selected':''}}>Myanmar</option>
        </select>
    </form>
</div>