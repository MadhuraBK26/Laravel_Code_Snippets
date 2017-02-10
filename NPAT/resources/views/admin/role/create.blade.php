@extends('layouts.applayout')
@section('title')
Create role
@endsection
@section('menu')
    @include('partials.navigationMenu')
@endsection

@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{route('role.store') }}">
    {!! Form::token() !!}
    <div class="panel-heading">Role: {!! Form::text("role[name]") !!} </div>
    @foreach($permissionGroups as $permissionGroup)
    @if(count($permissionGroup->permissions)>0) 
    <div class="col-md-14">
        <div class="panel panel-info">
            <div class="panel-heading"> {{ $permissionGroup->group_name }}</div>
            <div class="panel-body">
                <div class="form-group">                   
                    <div class="col-md-3 ">
                        <u>All</u>
                    </div>  
                    <div class="col-md-3">
                        <u>Assigned to</u>
                    </div>   
                </div>
                @foreach($permissionGroup->permissions as $permission)
                <div class="form-group">                   
                    <div class="col-md-3">
                        {!! Form::checkbox("permission[all][permission_id][]", $permission->id) !!} {{ $permission->name }}
                    </div>  
                    <div class="col-md-3">
                        {!! Form::checkbox("permission[assigned_to][permission_id][]", $permission->id) !!} {{ $permission->name }}
                    </div>   
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
    @endforeach
    <div class="col-md-2">
        <button type="submit  control-label" class="btn btn-primary">Create</button>
    </div>
</form>
@endsection
