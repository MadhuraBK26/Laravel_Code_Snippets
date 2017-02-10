@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2"><i class="fa fa-times"></i>
    </button>
    @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
    @else {{ $message }} @endif
</div>
@endif
@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
    <button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2"><i class="fa fa-times"></i>
    </button>
    <h4>Error</h4>
    @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
    @else {{ $message }} @endif
</div>
@endif
@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert" data-auto-dismiss="2"><i class="fa fa-times"></i>
    </button>
    <h4>Warning</h4>
    @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
    @else {{ $message }} @endif
</div>
@endif
@if ($message = Session::get('info'))
<div class="alert alert-info alert-block">
    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i>
    </button>
    <h4>Info</h4>
    @if(is_array($message)) @foreach ($message as $m) {{ $m }} @endforeach
    @else {{ $message }} @endif
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger">
    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i>
    </button>
    <strong>Whoops!</strong> There were some problems with your input.<br><br>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
