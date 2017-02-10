@extends('layouts.applayout')

@section('title')
Create Roles
@endsection

<script type="text/javascript">
    GridName = 'RolesGrid';
    FormName = 'roles-form';
</script>

@section('menu')

        @include('partials.navigationMenu')
@endsection

@section('content')
<div class="pl-dashboard">
    <h1>Roles</h1>
    <div id="btn-toolbar" class="section-header btn-toolbar" role="toolbar">
        <div id="btn-group-1" class="btn-group">
         <a href="{{route('admin.role.show') }}"> <i class="fa fa-plus"></i> New </a>
         
        </div>
    </div>
    <br>
    <div class="roles-list">
        <div id="grid-section" class="app-grid collapse in grid-style">        
            <table class="table table-responsive table-striped pl-table" width="100%">
                <tbody>
                    <tr>
                        <th class="metrix-head">Role name</th>
                        <th class="metrix-head">Actions</th>
                    </tr>
                    @foreach($roles as $role)
                    <tr>

                        <td class="label-metrix-pl">{{ $role->name }}</td>
                        <td class="label-metrix-pl"><a href="{{route('admin.role.edit',['role'=>$role->id])}}">Edit</a> &nbsp; &nbsp;
                        <a href="{{route('admin.role.destroy', $role->id)}}">Delete</a></td>


                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
