@extends('layouts.applayout')

@section('title')
Admin
@endsection
<script type="text/javascript">
    GridName = 'MetricsCategoryGrid';
    FormName = 'metrics-categories-form';
</script>
@section('menu')

        @include('partials.navigationMenu')
@endsection

@section('content')
<ul class="nav nav-tabs">
    <li role="presentation"><a href="{{route('metrics')}}">Metrics Master</a></li>
    <li role="presentation"><a href="{{route('assign-metrics')}}">Assign Metrics</a></li>
    <li role="presentation" class="active"><a href="{{route('metric-categories')}}">Metrics Categories</a></li>
</ul>
@include('partials.jqGridTool')
<div id="grid-section" class="app-grid collapse in grid-style">
    {!!
    GridRender::setGridId("MetricsCategoryGrid")
    ->enablefilterToolbar(true, false)
    ->hideXlsExporter()
    ->hideCsvExporter()
    ->setGridOption('url',URL::to('metrics-categories-grid'))
    ->setGridOption('rowNum', 20)
    ->setGridOption('rownumbers', true)
    ->setGridOption('width', 1150)
    ->setGridOption('height', 400)
    ->setGridOption('rowList',array(10,20,30))
    ->setGridOption('caption','Metrics')
    ->setGridOption('viewrecords',true)
    ->setGridOption('autowidth',true)
    ->setGridEvent('onSelectRow', 'onSelectRowEvent')
    ->addColumn(array('index' => 'id', 'hidden' => true))
    ->addColumn(array('label' => 'Categories Name', 'index'=>'name'))
    ->addColumn(array('label' => 'Description','index' => 'description'))
    ->addColumn(array('label' => 'Sort','index' => 'sort'))
    ->renderGrid()
    !!}
</div>

<div id='form-section' class="collapse">
    <div class="form-container">
        {!! Form::open(array('id' => 'metrics-categories-form', 'url' => route('metric-categories'), 'role' => 'form', 'class' => 'form-horizontal', 'onsubmit' => 'return false;')) !!}
        <fieldset id="books-form-fieldset">
            <legend id="form-new-title" class="hidden">Add a new categories</legend>
            <legend id="form-edit-title" class="hidden">Edit an existing categories</legend>
            <div class="form-group" id='test'>
                {!! Form::label('name', 'Category', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon">                 <i class="fa fa-filter"></i></span>
                        {!! Form::text('name', null , array('id' => 'name',
                        'class' => 'form-control',
                        'data-mg-required' => ''))
                        !!}
                        {!! Form::hidden('id', null, array('id' => 'id')) !!}
                    </div>
                </div>
            </div>
            <div class="form-group" id='test'>
                {!! Form::label('description', 'Description', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon">                 <i class="fa fa-font"></i></span>
                        {!! Form::text('description', null , array('id' => 'description',
                        'class' => 'form-control',
                        ))
                        !!}

                    </div>
                </div>
            </div>

            <div class="form-group" id='test'>
                {!! Form::label('sort', 'Sort', array('class' => 'col-sm-2 control-label')) !!}
                <div class="col-sm-10">
                    <div class="input-group">
                        <span class="input-group-addon">                 <i class="fa fa-sort-amount-desc"></i></span>
                        {!! Form::text('sort', null , array('id' => 'sort',
                        'class' => 'form-control',
                        'data-mg-required' => ''))
                        !!}

                    </div>
                </div>
            </div>
        </fieldset> {!! Form::close() !!}  </div>
    </div>

    @endsection
