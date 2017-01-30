@extends('layouts.default')

 

@section('content')


    <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <h2>Car Parking CRUD Login</h2>

            </div>

            <div class="pull-right">

                 <a class="btn btn-success" href="login.blade.php"> Login </a>


            </div>

        </div>

    </div>


   @if (count($errors) > 0)

        <div class="alert alert-danger">

            <strong>Whoops!</strong> There were some problems with your input.<br><br>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    {{ Form::open(array('url' => 'login')) }}

      <div class="row">


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Name:</strong>

                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}

            </div>

        </div>


      <div class="row">


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Email:</strong>

              {{ Form::text('email','',array('id'=>'','class'=>'form-control span6','placeholder' => 'Please Enter your Email')) }}

                <p class="errors">{{$errors->first('email')}}</p>

            </div>

        </div>

         <div class="row">


        <div class="col-xs-12 col-sm-12 col-md-12">

            <div class="form-group">

                <strong>Password:</strong>

              {{ Form::password('password',array('class'=>'form-control span6', 'placeholder' => 'Please Enter your Password')) }}

                <p class="errors">{{$errors->first('password')}}</p>

            </div>

        </div>


{{ Form::submit('Login', array('class'=>'send-btn')) }}
{{ Form::close() }}












