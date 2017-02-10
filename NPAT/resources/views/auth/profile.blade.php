@extends('layouts.applayout')

@section('title')
    Navigators Performance Analysis Tool
@endsection

@section('menu')
    @include('partials.navigationMenu')
@endsection

@section('content')
    @include('auth.profile_info')
    @include('auth.profile_user')
@endsection

