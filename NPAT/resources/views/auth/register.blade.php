@extends('layouts.applayout')

@section('title')
    Register
@endsection

<script type="text/javascript">

    GridName = 'UserGrid';
    FormName = 'user-form';
</script>

@section('menu')
    {{--*/ $permissionGroupSlug = 'users' /*--}}
    @include('partials.navigationMenu')
@endsection
@section('content')

    <div class="container-fluid">
        <style>
            .hoe {
                display: none !important;
            }
        </style>
        <ul class="nav nav-tabs">
            <li role="presentation" class="{{Active::pattern('account/register')}}"><a
                        href="{{url('register')}}"></a></li>
        </ul>

        @include('partials.jqGridTool')

        <div id="grid-section" class="app-grid collapse in">
            {!!
            GridRender::setGridId("UserGrid")
            ->enablefilterToolbar(true, false)
            ->hideXlsExporter()
            ->hideCsvExporter()
            ->setGridOption('url',URL::to('register/grid'))
            ->setGridOption('rowNum', 20)
            ->setGridOption('rownumbers', true)
            ->setGridOption('width', 1150)
            ->setGridOption('height', 400)
            ->setGridOption('rowList',array(10,20,30))
            ->setGridOption('caption','User Details')
            ->setGridOption('viewrecords',true)
            ->setGridOption('autowidth',true)
            ->setGridEvent('onSelectRow', 'onSelectRowEvent')
            ->addColumn(array('index' => 'id', 'hidden' => true))
            ->addColumn(array('label' => 'Name', 'index'=>'name'))
            ->addColumn(array('label' => 'Employee Id', 'index'=>'emp_id'))
            ->addColumn(array('index' => 'Employee Id', 'index'=>'emp_id', 'hidden' => true))
            ->addColumn(array('label' => 'Role', 'index'=>'role_id', 'hidden' => true))
            ->addColumn(array('label' => 'Role', 'index'=>'role_name'))
            ->addColumn(array('label' => 'Is Manager','index'=>'is_manager'))
            ->addColumn(array('label' => 'Designation', 'index'=>'navigator_designation_id', 'hidden' => true))
            ->addColumn(array('label' => 'Reporting Manager', 'index'=>'reporting_manager_name'))
            ->addColumn(array('label' => 'Reporting Manager', 'index'=>'reporting_manager_id', 'hidden' => true))
            ->addColumn(array('label' => 'Email', 'index'=>'email', 'hidden' => true))
            ->addColumn(array('label' => 'Designation', 'index'=>'navigator_name'))
            ->addColumn(array('label' => 'Practices', 'index'=>'practices_name'))
            ->addColumn(array('label' => 'Practices', 'index'=>'practices_id', 'hidden' => true))
            ->addColumn(array('label' => 'Status', 'index'=>'status'))
            ->renderGrid()
            !!}
        </div>

        <div id='form-section' class="collapse">
            <div class="form-container">
                <div class="valid"></div>
                <form id="user-form" action="<?php echo route('register');?>" method="post" role="form"
                      class="form-horizontal">
                    <fieldset id="books-form-fieldset">
                        <legend id="form-new-title" class="hidden">Add a new Record</legend>
                        <legend id="form-edit-title" class="hidden">Edit an existing Record
                           
                            <a href="{{url('user-details')}}" target="_blank" id="edit_url">
                                <button type="button" class="btn btn-primary reg-button">
                                Advanced Edit</button></a>
                        </legend>
                        <input type="hidden" id="edit_base_url" value="{{ url('user-details') }}">

                        <div class="form-group hide-on-edit" id='test'>
                            <label class="col-sm-2 control-label" name="name"> Name <span
                                        class="asteric">*</span></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"> <i class="fa fa-user"></i> </span>
                                    {!! Form::text('name', null , array('id' => 'name',
                                    'class' => 'form-control','data-mg-required'=>''))
                                    !!}
                                    {!! Form::hidden('id', null, array('id' => 'id')) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" name="emp_id"> Employee Id <span
                                        class="asteric">*</span></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"> <i class="fa fa-credit-card"></i>   </span>
                                    <input type="text" id="emp-id" name="emp_id" class="form-control no-edit"
                                           data-mg-required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label" name="email"> Email <span
                                        class="asteric">*</span></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon">   <i class="fa fa-envelope"></i>  </span>
                                    {!! Form::text('email', null , array('id' => 'email',
                                    'class' => 'form-control no-edit','data-mg-required'=>''))
                                    !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group hide-on-edit" id='test'>
                            <label class="col-sm-2 control-label" name="password"> Password <span
                                        class="asteric">*</span></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"> <i class="fa fa-pencil-square-o"></i>   </span>
                                    <input type="password" id="password" name="password" class="form-control"
                                    >

                                </div>
                            </div>
                        </div>

                        <div class="form-group hide-on-edit" id='test'>
                            <label class="col-sm-2 control-label"> Confirm Password <span
                                        class="asteric">*</span></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon">  <i class="fa fa-pencil-square"></i>       </span>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                           class="form-control">

                                </div>
                            </div>
                        </div>

                        <div class="form-group" id='test'>
                            <label class="col-sm-2 control-label" name="roles"> Role <span
                                        class="asteric">*</span></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon">            <i class="fa fa-user"></i></span>
                                    <select id="role-id" name="role" class="form-control" multiple data-mg-required>
                                        <option value="">Select Role</option>
                                        @foreach ($roles as $role)
                                            <option value="{{$role['id']}}">{{$role['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id='test'>
                            <label class="col-sm-2 control-label" name="roles"> Managing People <span
                                        class="asteric">*</span></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-compass"></i></span>
                                    <select id="is-manager" name="is_manager" class="form-control"
                                            data-mg-required>
                                        <option value="">Select Yes/No</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group hide-on-edit" id='test'>
                            <label class="col-sm-2 control-label" name="practices"> Practices <span
                                        class="asteric">*</span></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon">       <i class="fa fa-cube"></i></span>
                                    <select id="practices-id" name="practices" class="form-control" multiple
                                            data-mg-required>
                                        <option value="">Select Practices</option>
                                        @foreach($practicesDetails as $practices)
                                            <option value="{{ $practices['id'] }}">{{ $practices['practices'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id='test'>
                            <label class="col-sm-2 control-label" name="role"> Designation <span
                                        class="asteric">*</span></label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon">       <i class="fa fa-compass"></i></span>
                                    <select id="navigator-designation-id" name="designation" class="form-control"
                                            data-mg-required>
                                        <option value="">Select Designation</option>
                                        @foreach($designations as $des)
                                            <option value="{{$des['id']}}">{{$des['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group hide-on-edit" id='reporter'>
                            <label class="col-sm-2 control-label" name="role"> Reporting Manager</label>

                            <div class="col-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon">      <i class="fa fa-star-o"></i></span>
                                    <select id="reporting-manager-id" name="ReportingManager" class="form-control">
                                        <option value="">Select Reporting Manager</option>

                                    </select>
                                </div>
                            </div>
                        </div>


                    </fieldset>
                </form>
            </div>
        </div>
    </div>
@endsection