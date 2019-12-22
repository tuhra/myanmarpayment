@if(Session::get('moStatus') ==1) 
    <a href="{{url('/mpt/mosuccess')}}" class="btn-submit">Subscribe</a>
@else
    <a href="{{url('/mpt/success')}}" class="btn-submit">Subscribe</a>
@endif