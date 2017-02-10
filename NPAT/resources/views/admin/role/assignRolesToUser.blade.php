@extends('layouts.applayout')
@section('title')
Create role
@endsection
@section('menu')
    @include('partials.navigationMenu')
@endsection

@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('admin.role.store_assign_roles_to_user') }}">
    {!! Form::token() !!}
    <div class="panel-heading">
        Role:  {!! Form::select('user', $users->lists("name","id"), null,array('name'=>'user_id')) !!}
    </div>
    <div class="panel-heading">
        Role:  {!! Form::select('roles', $roles->lists("name","id"), null,array('multiple'=>'multiple','name'=>'role_ids[]')) !!}
    </div>
    <div class="col-md-2">
        <button type="submit  control-label" class="btn btn-primary">Save</button>
    </div>
</form>
@endsection
