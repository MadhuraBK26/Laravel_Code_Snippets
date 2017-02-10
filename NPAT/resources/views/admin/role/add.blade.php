@extends('layouts.applayout')
@section('title')
Create role
@endsection
@section('menu')
@include('partials.navigationMenu')
@endsection

@section('content')
@inject('roleRepository', 'App\Repositories\RoleRepository')
<div class="pl-dashboard">
    <h1>Roles</h1>
    <form role="form" class="form-horizontal" method="POST" action="{{ route('role.update',['role'=>$role->id]) }}">
        {!! Form::token() !!}
        <input name="_method" value="PUT" type="hidden">
        <div class="panel-heading">Role name: {!! Form::text("role[name]", $role->name) !!} </div>
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
                            <?php $isAssigned = $roleRepository->getIsAssignedForPermission($role, $permission);  ?>
                            {!! Form::checkbox("permission[all][permission_id][]", $permission->id,  $isAssigned === 0) !!} {{ $permission->name }}
                        </div>  
                        <div class="col-md-3">
                            {!! Form::checkbox("permission[assigned_to][permission_id][]", $permission->id,  $isAssigned === 1) !!} {{ $permission->name }}
                        </div>   
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        @endforeach
        <div class="col-md-2">
            <button type="submit  control-label" class="btn btn-primary">Save</button>
            <button onclick="goBack()" class="btn btn-info">Back</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
@endsection
