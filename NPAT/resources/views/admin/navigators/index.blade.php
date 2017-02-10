@extends('layouts.applayout')

@section('title')
    Assign Navigators To Respective Projects
@endsection

<script type="text/javascript">
    GridName = 'NavigatorsGrid';
    FormName = 'navigator-form';
</script>

@section('menu')
    {{--*/ $permissionGroupSlug = 'navigators' /*--}}

    @include('partials.navigationMenu')
@endsection

@section('content')
    @include('partials.jqGridTool')
    <div class="navigator-list">
        <div id="grid-section" class="app-grid collapse in grid-style">
            {!!
            GridRender::setGridId("NavigatorsGrid")
            ->enablefilterToolbar(true, false)
            ->hideXlsExporter()
            ->hideCsvExporter()
            ->setGridOption('url',URL::to('navigator-grid'))
            ->setGridOption('rowNum', 20)
            ->setGridOption('rownumbers', true)
            ->setGridOption('width', 1150)
            ->setGridOption('height', 400)
            ->setGridOption('autowidth',true)
            ->setGridOption('rowList',array(10,20,30))
            ->setGridOption('caption','Navigators Assigned to Project Details')
            ->setGridOption('viewrecords',true)
            ->setGridEvent('onSelectRow', 'onSelectRowEvent')
            ->addColumn(array('index' => 'id', 'hidden' => true))
            ->addColumn(array('index' => 'project_id', 'hidden' => true))
            ->addColumn(array('label' => 'Project', 'index'=>'project_name'))
            ->addColumn(array('index' => 'manager_id', 'hidden' => true))
            ->addColumn(array('label' => 'Manager','index' => 'manager_name'))
            ->addColumn(array('index' => 'people_id', 'hidden' => true))
            ->addColumn(array('label' => 'Navigator','index' => 'people_name'))
            ->addColumn(array('label' => 'Percentage Involved','index' => 'percentage_involved'))
            ->addColumn(array('label' => 'Start Date','index' => 'start_date'))
            ->addColumn(array('label' => 'End Date','index' => 'end_date'))
            ->addColumn(array('label' => 'Status','index' => 'status'))
            ->renderGrid()
            !!}
        </div>
    </div>
    <div id='form-section' class="collapse" hidden>
        <div class="form-container">
            {!! Form::open(array('id' => 'navigator-form', 'url' => route('navigator'), 'role' => 'form', 'class' => 'form-horizontal', 'onsubmit' => 'return false;')) !!}
            {!! Form::hidden('id', null, array('id' => 'id')) !!}
            <fieldset id="books-form-fieldset">
                <legend id="form-new-title" class="hidden">Assign Navigators</legend>
                <div class="form-group hide-on-edit" id='test'>
                    {!! Form::label('project', 'Select Project', array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">                 <i class="fa fa-tasks"></i></span>
                            <select id="project-id" name="project" class="form-control" data-mg-required>
                                <option value="">Select Project</option>
                                @foreach($formData['projects'] as $projectValue)
                                    <option value="{{ $projectValue->id }}"> {{ $projectValue->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group" id='test'>

                    {!! Form::label('manager', 'Select Manager', array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">                 <i class="fa fa-user"></i></span>
                            <select id="manager-id" name=" manager" class="form-control" data-mg-required>
                                <option value="">Select Manager</option>
                                @foreach($formData['users'] as $userValue)
                                    <option value="{{ $userValue->id }}"> {{$userValue->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group" id='test'>
                    {!! Form::label('navigator', 'Select Navigator', array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">                 <i class="fa fa-user"></i></span>
                            <select id="people-id" name="navigator" class="form-control no-edit" data-mg-required>
                                <option value="">Select Navigator</option>
                                @foreach($formData['navigators'] as $navigatorValue)
                                    <option value="{{ $navigatorValue->id }}"> {{$navigatorValue->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" name="status"> Percentage Of People Involved In
                        Project </label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">    <i class="fa fa-shield"></i></span>
                            <input type="text" id="percentage-involved" name="percentage_involved" value="100"
                                   class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" name="start_date"> Start Date </label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">    <i class="fa fa-calendar"></i></span>
                            <input type="text" id="start-date" name="start_date"
                                   class="form-control no-edit" data-mg-required>
                        </div>
                    </div>
                </div>
                <div class="form-group" id='test'>
                    <label class="col-sm-2 control-label" name="end_date"> End Date </label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">    <i class="fa fa-calendar"></i></span>
                            <input type="text" id="end-date" name="end_date" class="form-control edit" data-mg-required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" type="radio" name="status"> Status </label>

                    <div class="col-sm-10">
                        <input type="radio" id="active"   class="status" name="status" value="1">Active </input>
                        <input type="radio" id="inactive" class="status" name="status" value="2">InActive </input>
                        <input type="radio" id="released" class="status" name="status" value="3">Released</input>
                    </div>
                </div>

            </fieldset> {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('app/Controller/projectIndex.js') }}"></script>
@endsection

