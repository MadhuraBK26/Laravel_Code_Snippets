@extends('layouts.applayout')

@section('title')
Navigators Performance Analysis Tool
@endsection

@section('menu')
@include('partials.header')
@endsection

<script type="text/javascript">
        function noBack()
         {
             window.history.forward()
         }
        noBack();
        window.onload = noBack;
        window.onpageshow = function(evt) { if (evt.persisted) noBack() }
        window.onunload = function() { void (0) }
    </script>
    
@section('content')
<div class="row head-section">
    <h2>Welcome! To Navigators' Performance Analysis Tool </h2>

    <div class="col-md-8 img-section">
        <img src="{{asset('assets/css/images/s4vs2kU.png')}}" class="img-responsive img-center"/>
    </div>

    <div class="col-md-4 login-section">

        <fieldset class="lg-form-container">
            <legend align="center" class="lg-form-title login-heading">Login to Your Account</legend>
            <form role="form" method="POST" action="{{ route('handle.login') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group">
                    <label class="lg-form-lbl">E-Mail Address</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                </div>

                <div class="form-group">
                    <label class="lg-form-lbl">Password</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <div class="form-group lg-sbmt-btn">
                    <button type="submit" class="btn btn-primary">Login</button>
                    <button type="reset" class="btn btn-primary">Reset</button>
                    {{--<button type="forgot" class="btn btn-primary">Forgot password</button>--}}
                    <a href="{{ URL::route('passwordemail') }}">Forgot Password?</a>
                </div>
            </form>
        </fieldset>
        <p style="color:red;text-align: center">{!!  Session::get('timeout') !!}</p>

    </div>
</div>
@endsection
