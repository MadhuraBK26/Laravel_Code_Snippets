@extends('layouts.applayout')

@section('title')
    Dashboard
@endsection

@section('menu')
    @include('partials.header')
@endsection

@section('content')

    <div class="row head-section">
        <h2>Hai , Welcome to NPAT System. You can rate your people based on their performance....!</h2>

        <div class="col-md-8 img-section">
            <img src="{{asset('assets/css/images/s4vs2kU.png')}}" class="img-responsive img-center"/>
        </div>

        <form role="form" class="form-horizontal" method="GET" action="{{ route('dashboard') }}">
            <div class="container">
                <div class="head-section">
                    <h1 class="div-space">Select Your Role </h1>
                    @foreach($getRoleDetails as $roleName)
                        <h3 class="div-space">
                            <div class="radio">
                                <label><input type="radio" id="role" name="role" required
                                              value="{{ $roleName['id'] }}">{{$roleName['name']}}</label>
                            </div>
                        </h3>
                    @endforeach
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Enter</button>
                        <button onclick="goBack()" class="btn btn-primary">Back</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@section('scripts')
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection
