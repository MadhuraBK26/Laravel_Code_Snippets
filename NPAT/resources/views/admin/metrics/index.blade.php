@extends('layouts.applayout')

@section('title')
    Admin
@endsection
<script type="text/javascript">
    GridName = 'MetricsGrid';
    FormName = 'metrics-form';
</script>

@section('menu')
    {{--*/ $permissionGroupSlug = 'metrics' /*--}}

        @include('partials.navigationMenu')
@endsection

@section('content')
    <ul class="nav nav-tabs">
        <li role="presentation" class="active"><a href="{{route('metrics')}}">Metrics Master</a></li>
        <li role="presentation"><a href="{{route('assign-metrics')}}">Assign Metrics</a></li>
        <li role="presentation"><a href="{{route('metric-categories')}}">Metrics Categories</a></li>
    </ul>
    @include('partials.jqGridTool')
    <div id="grid-section" class="app-grid collapse in grid-style">
        {!!
        GridRender::setGridId("MetricsGrid")
        ->enablefilterToolbar(true, false)
        ->hideXlsExporter()
        ->hideCsvExporter()
        ->setGridOption('url',URL::to('metrics/grid'))
        ->setGridOption('rowNum', 20)
        ->setGridOption('rownumbers', true)
        ->setGridOption('width', 1150)
        ->setGridOption('height', 400)
        ->setGridOption('rowList',array(30,10,30))
        ->setGridOption('caption','Metrics Details')
        ->setGridOption('viewrecords',true)
        ->setGridOption('autowidth',true)
        ->setGridEvent('onSelectRow', 'onSelectRowEvent')
        ->addColumn(array('index' => 'id', 'hidden' => true))
        ->addColumn(array('index' => 'category_id', 'hidden' => true))
        ->addColumn(array('label' => 'Metrics', 'index'=>'metrics','width'=>'650'))
        ->addColumn(array('label' => 'Category', 'index'=>'category_name','width'=>'100'))
        ->addColumn(array('label' => 'Sort', 'index'=>'sort','width'=>'50'))
        ->renderGrid()
        !!}
    </div>

    <div id='form-section' class="collapse">
        <div class="form-container">
            <div class="valid"></div>
            {!! Form::open(array('id' => 'metrics-form', 'url' => route('metrics'), 'role' => 'form', 'class' => 'form-horizontal', 'onsubmit' => 'return false;')) !!}
            <fieldset id="books-form-fieldset">
                <legend id="form-new-title" class="hidden">Add a new Metrics</legend>
                <legend id="form-edit-title" class="hidden">Edit an existing Metrics</legend>
                <div class="form-group" id='test'>
                    {!! Form::label('metrics', 'Metrics', array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">                 <i class="fa fa-trophy"></i></span>
                            {!! Form::text('metrics', null , array('id' => 'metrics',
                            'class' => 'form-control',
                            'data-mg-required' => ''))
                            !!}
                            {!! Form::hidden('id', null, array('id' => 'id')) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group" id='test'>
                    {!! Form::label('category', 'Category', array('class' => 'col-sm-2 control-label','data-mg-required' => '')) !!}
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">                 <i class="fa fa-filter"></i></span>
                            <select id="name" name="category" class="form-control" data-mg-required>
                                <option value="">Select Category</option>
                                @foreach($categoryList as $categoryName)
                                    <option value="{{ $categoryName->id }}"> {{$categoryName->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group" id='test'>
                    {!! Form::label('sort', 'Sort', array('class' => 'col-sm-2 control-label')) !!}
                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">                 <i
                                        class="fa fa-sort-amount-desc"></i></span>
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
