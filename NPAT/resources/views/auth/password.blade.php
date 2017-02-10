@extends('layouts.applayout')

@section('title')
Password
@endsection

@section('menu')
@include('partials.header')
@endsection

@section('content')

<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Forgot Password</div>
        <div class="panel-body">
          @if (session('status'))
          <div class="alert alert-success">
            {{ session('status') }}
          </div>
          @endif
          <form class="form-horizontal" role="form" method="POST" action={{ route('password.email') }}>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
              <label class="col-md-4 control-label">E-Mail Address</label>
              <div class="col-md-6">
                <input type="email" class="form-control" name="email" value="{{ old('email') }}">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">
                  Send Password Reset Link
                </button>
                <a class="btn btn-primary" href="{{ URL::route('dashboard') }}">Cancel</a>
              </div>

            </div>
            <p style="color:red;text-align: center">{!!  Session::get('data') !!}</p>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
