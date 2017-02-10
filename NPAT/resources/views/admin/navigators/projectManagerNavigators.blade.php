@extends('layouts.applayout')

@section('title')
    Assign Navigators To Respective Projects
@endsection

<script type="text/javascript">
    GridName = 'ProjectManagerNavigatorsGrid';
    FormName = 'projectManagerNavigator-form';
</script>

@section('menu')
    @include('partials.PmHeader')
@endsection

@section('content')
    @include('partials.jqGridTool')
    <div class="navigator-list">
        <div id="grid-section" class="app-grid collapse in grid-style">
            {!!
            GridRender::setGridId("ProjectManagerNavigatorsGrid")
            ->enablefilterToolbar(true, false)
            ->hideXlsExporter()
            ->hideCsvExporter()
            ->setGridOption('url',URL::to('projectmanager-report/projectManagerNavigators/grid'))
            ->setGridOption('rowNum', 18)
            ->setGridOption('rownumbers', true)
            ->setGridOption('width', 1150)
            ->setGridOption('height', 400)
            ->setGridOption('autowidth',true)
            ->setGridOption('rowList',array(10,20,30))
            ->setGridOption('caption','Navigators Assigned to Project')
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
            ->renderGrid()
            !!}
        </div>
    </div>
    <div id='form-section' class="collapse" hidden>
        <div class="form-container">
            {!! Form::open(array('id' => 'projectManagerNavigator-form', 'url' => route('projectManagerNavigators'), 'role' => 'form', 'class' => 'form-horizontal', 'onsubmit' => 'return false;')) !!}
            {!! Form::hidden('id', null, array('id' => 'id')) !!}
            <fieldset id="books-form-fieldset">
                <legend id="form-new-title" class="hidden">Assign Navigators</legend>
                <div class="form-group" id='test'>
                    {!! Form::label('project', 'Select Project', array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">                 <i class="fa fa-book"></i></span>
                            <select id="project-id" name="project" class="form-control">
                                <option value="">Select Project</option>
                                @foreach($projectData['project'] as $projectValue)
                                    <option value="{{ $projectValue->id }}">{{ $projectValue->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" name="manager"> Manager </label>
                        <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">    </span>
                                @if(Auth::user()->role_id == config('custom.projectManagersId') && Session::get('role') != config('custom.projectManagerLead'))
                                <select id="manager-id" name="manager" class="form-control" readonly="readonly" style="-webkit-appearance: none;">
                                <option value="{{ $userDetails['id'] }}"> {{$userDetails['name']}} </option>
                                @else
                                <select id="manager-id" name="manager" class="form-control">
                                <option value="">Select Manager</option>
                                @foreach($formData['users'] as $userValue)
                                    <option value="{{ $userValue->id }}"> {{$userValue->name }} </option>
                                @endforeach
                                @endif
                            </select>

                        </div>
                    </div>
                </div>
                <div class="form-group" id='test'>
                    {!! Form::label('navigator', 'Select Navigator', array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">                 <i class="fa fa-book"></i></span>
                            <select id="people-id" name="navigator" class="form-control">
                                <option value="">Select Navigator</option>
                                @foreach($formData['users'] as $navigatorValue)
                                    <option value="{{ $navigatorValue->id }}"> {{$navigatorValue->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" name="status"> Percentage Of People Involved In Project </label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">    </span>
                            <input type="text" id="percentage-involved" name="percentage_involved" value="100" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" name="start_date"> Start Date </label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">    </span>
                            <input type="text" id="start-date" name="start_date"
                                   class="form-control no-edit">
                        </div>
                    </div>
                </div>
                <div class="form-group" id='test'>
                    <label class="col-sm-2 control-label" name="end_date"> End Date </label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">    </span>
                            <input type="text" id="end-date" name="end_date" class="form-control edit">
                        </div>
                    </div>
                </div>
            </fieldset> {!! Form::close() !!}
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('app/Controller/projectIndex.js') }}"></script>
@endsection

