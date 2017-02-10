<?php
/**
 * Created by
 *  rohini
 * Date: 5/5/16
 * Time: 1:20 PM
 */

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
                            <form method="POST" action={{ route('password.reset') }}>
                                {!! csrf_field() !!}
                                <input type="hidden" name="token" value="{{ $token }}">

                                @if (count($errors) > 0)
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                @endif

                                <div>
                                    Email
                                    <input type="email" name="email" value="{{ old('email') }}">
                                </div>

                                <div>
                                    Password
                                    <input type="password" name="password">
                                </div>

                                <div>
                                    Confirm Password
                                    <input type="password" name="password_confirmation">
                                </div>

                                <div>
                                    <button type="submit">
                                        Reset Password
                                    </button>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
