@extends('layouts.applayout')

@section('title')
    Create Practices
@endsection

<script type="text/javascript">
    GridName = 'PracticesGrid';
    FormName = 'practices-form';
</script>

@section('menu')
    {{--*/ $permissionGroupSlug = 'practices' /*--}}
        @include('partials.navigationMenu')
@endsection

@section('content')

    @include('partials.jqGridTool')
    <div class="practices-list">
        <div id="grid-section" class="app-grid collapse in grid-style">
            {!!
            GridRender::setGridId("PracticesGrid")
            ->enablefilterToolbar(true, false)
            ->hideXlsExporter()
            ->hideCsvExporter()
            ->setGridOption('url',URL::to('practices/grid'))
            ->setGridOption('rowNum', 20)
            ->setGridOption('rownumbers', true)
            ->setGridOption('width', 1150)
            ->setGridOption('height', 400)
            ->setGridOption('rowList',array(10,20,30))
            ->setGridOption('caption','Practices Details')
            ->setGridOption('viewrecords',true)
            ->setGridOption('autowidth',true)
            ->setGridEvent('onSelectRow', 'onSelectRowEvent')
            ->addColumn(array('index' => 'id', 'hidden' => true))
            ->addColumn(array('label' => 'Practices', 'index' =>'practices'))
            ->addColumn(array('label' => 'Status', 'index' =>'status'))
            ->renderGrid()
            !!}
        </div>
    </div>
    <div id='form-section' class="collapse">
        <div class="form-container">
            <div class="valid"></div>
            {!! Form::open(array('id' => 'practices-form', 'url' => route('practices'), 'role' => 'form', 'class' => 'form-horizontal', 'onsubmit' => 'return false;')) !!}
            <fieldset id="books-form-fieldset">
                <legend id="form-new-title" class="hidden">Create New Practices</legend>
                <legend id="form-edit-title" class="hidden">Edit An Existing Practices</legend>
                <div class="form-group hide-on-edit" id='test'>
                    <label class="col-sm-2 control-label" name="name">Practices</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">           <i class="fa fa-cube"></i></span>
                            {!! Form::text('practices', null , array('id' => 'practices',
                            'class' => 'form-control','data-mg-required'))
                            !!}
                            {!! Form::hidden('id', null, array('id' => 'id')) !!}
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label" type="radio" name="status_text"> Status </label>
                     <div class="col-sm-10">
                        <input type="radio" id="active"   class="status" name="status" value="1">Active </input>
                        <input type="radio" id="inactive" class="status" name="status" value="2">InActive </input>
                        
                    </div>
                </div>
            </fieldset> {!! Form::close() !!}  </div>
    </div>
@endsection


