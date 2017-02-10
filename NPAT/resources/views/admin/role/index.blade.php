@extends('layouts.applayout')

@section('title')
    Create Roles
@endsection

<script type="text/javascript">
    GridName = 'RolesGrid';
    FormName = 'roles-form';
</script>

@section('menu')
    {{--*/ $permissionGroupSlug = 'roles' /*--}}
    @include('partials.navigationMenu')
@endsection

@section('content')
    @include('partials.jqGridTool')
    <div class="roles-list">
        <div id="grid-section" class="app-grid collapse in grid-style">
            {!!
            GridRender::setGridId("RolesGrid")
            ->enablefilterToolbar(true, false)
            ->hideXlsExporter()
            ->hideCsvExporter()
            ->setGridOption('url',URL::to('admin/role/grid'))
            ->setGridOption('rowNum', 10)
            ->setGridOption('rownumbers', true)
            ->setGridOption('width', 1150)
            ->setGridOption('height', 225)
            ->setGridOption('rowList',array(10,20,30))
            ->setGridOption('caption','Roles Details')
            ->setGridOption('viewrecords',true)
            ->setGridOption('autowidth',true)
            ->setGridEvent('onSelectRow', 'onSelectRowEvent')
            ->addColumn(array('index' => 'id', 'hidden' => true))
            ->addColumn(array('label' => 'Roles', 'index' =>'name'))
            ->addColumn(array('label' => 'Level', 'index' => 'level'))
            ->addColumn(array('index' => 'permission_role_id','hidden' => false, 'edittype'=>'checkbox', 'editoptions'=>['multiple'=>true]))
            ->addColumn(array('index' => 'slug', 'hidden' => true))
            ->renderGrid()
            !!}                       
        </div>
    </div>
    <div id='form-section' class="collapse">
        <div class="form-container">
            {!! Form::open(array('id' => 'roles-form', 'url' => route('role'), 'role' => 'form', 'class' => 'form-horizontal', 'onsubmit' => 'return false;')) !!}
            <fieldset id="books-form-fieldset">
                <div class="panel-heading">
                    Role: {!! Form::text('role[name]', null , array('id' => 'name'))!!}
                    {!! Form::hidden('id', null, array('id' => 'id')) !!}
                </div>
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
                                                {!! Form::checkbox("permission_role_id", $permission->id) !!} {{ $permission->name }}
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
                    </fieldset> {!! Form::close() !!}  </div>
        </div>
@endsection
