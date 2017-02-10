@extends('layouts.applayout')

@section('title')
Admin
@endsection

<script type="text/javascript">
    GridName = 'AssignMetricsGrid';
    FormName = 'assign-metrics-form';
</script>

@section('menu')

        @include('partials.navigationMenu')
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation"><a href="{{route('metrics')}}">Metrics Master</a></li>
    <li role="presentation" class="active"><a href="{{route('assign-metrics')}}">Assign Metrics</a></li>
    <li role="presentation"><a href="{{route('metric-categories')}}">Metrics Categories</a></li>
</ul>
@include('partials.jqGridTool')
<div id="grid-section" class="app-grid collapse in grid-style">
    {!!
    GridRender::setGridId("AssignMetricsGrid")
    ->enablefilterToolbar(true, false)
    ->hideXlsExporter()
    ->hideCsvExporter()
    ->setGridOption('url',url('assigned-metrics-grid'))
    ->setGridOption('rowNum', 20)
    ->setGridOption('rownumbers', true)
    ->setGridOption('width', 1150)
    ->setGridOption('height', 400)
    ->setGridOption('rowList',array(10,20,30))
    ->setGridOption('caption','Assigned Metrics Details')
    ->setGridOption('viewrecords',true)
    ->setGridOption('autowidth',true)
    ->setGridEvent('onSelectRow', 'onSelectRowEvent')
    ->addColumn(array('index' => 'id', 'hidden' => true))
    ->addColumn(array('index' => 'metrics_id', 'hidden' => true))
    ->addColumn(array('label' => 'Metrics', 'index'=>'metrics_name','width'=>'650'))
    ->addColumn(array('index' => 'navigator_designation_id','hidden' => true,'width'=>'100'))
    ->addColumn(array('label' => 'Designation','index' => 'navigator_designation_name'))
    ->addColumn(array('index' => 'is_mandatory','hidden' => true))
    ->addColumn(array('label' => 'Is Mandatory','index' => 'mandatory'))
    ->renderGrid()
    !!}
</div>
<div id='form-section' class="collapse">
    <div class="form-container">
        {!! Form::open(array('id' => 'assign-metrics-form', 'url' => route('assign-metrics'), 'role' => 'form', 'class' => 'form-horizontal', 'onsubmit' => 'return false;')) !!}
        <fieldset id="books-form-fieldset">
            <legend id="form-new-title" class="hidden">Assign New Metrics</legend>
            <legend id="form-edit-title" class="hidden">Edit an Assigned Metrics</legend>


            {!! Form::hidden('id', null, array('id' => 'id')) !!}

            <div class="form-group" id='test'>
                {!! Form::label('metrics', 'Metrics', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon">                 <i class="fa fa-trophy"></i></span>
                        <select id="metrics-id" name="metrics" class="form-control" data-mg-required>
                            <option value="">Select Metrics</option>
                            @foreach($userMetrics as $userMetric)
                            <option value="{{ $userMetric->id }}"> {{$userMetric->metrics }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" id='test'>
                {!! Form::label('designation', 'Designation', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon">                 <i class="fa fa-compass"></i></span>
                        <select id="navigator-designation-id" name="designation" class="form-control" data-mg-required>
                            <option value="">Select Designation</option>
                            @foreach($userDesignation as $desigationName)
                            <option value="{{ $desigationName->id }}"> {{$desigationName->name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            <div class="form-group">
                {!! Form::label('mandatory', 'Is Mandatory ?', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    <div class="input-group"><span class="input-group-addon">
                        <i class="fa fa-language"></i></span> {!! Form::select('mandatory', array('0' => 'No', '1' => 'Yes'), null , array('id' => 'is-mandatory', 'class' => 'form-control')) !!}
                    </div>
                </div>
            </div>

        </fieldset> {!! Form::close() !!}  </div>
    </div>
    @endsection
