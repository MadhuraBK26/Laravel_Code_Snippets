@extends('layouts.applayout')

@section('title')
    Navigators Performance Analysis Tool
@endsection

@section('menu')
    @include('partials.navigationMenu')
@endsection

@section('content')
    @include('auth.profile_info')
    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading">Change Password</div>
            <div class="panel-body">
                {!! Form::open(array('route' => 'profile-update', 'class' => 'form', 'id' => 'profile-data')) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group  col-sm-10">
                    {!! Form::label('Old Password') !!}
                    {!! Form::password('current_password',
                    array('required',
                              'class'=>'form-control form-box password'))!!}
                </div>

                <div class="form-group  col-sm-10">
                    {!! Form::label('New Password') !!}
                    {!! Form::password('password',
                    array('required',
                              'class'=>'form-control form-box password'))!!}
                </div>

                <div class="form-group  col-sm-10">
                    {!! Form::label('Confirm Password') !!}
                    {!! Form::password('password_confirmation',
                        array('required',
                              'class'=>'form-control form-box password'))!!}
                </div>

                <div class="form-group col-sm-10">
                    <label><input type="checkbox" id="reset_question" name="Your Question"> Please answer your secure question to reset your password</label>
                    <p id="insertinputs"></p>
                </div>

                <div class="form-group col-sm-10">
                    {!! Form::submit('Update Profile',
                      array('class'=>'btn btn-info' ,'id'=>'form-submit')) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection


