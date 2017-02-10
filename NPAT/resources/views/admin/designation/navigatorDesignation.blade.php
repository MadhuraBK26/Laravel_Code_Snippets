@extends('layouts.applayout')

@section('title')
    Create Navigator Designation
@endsection

<script type="text/javascript">
    GridName = 'NavigatorDesignationGrid';
    FormName = 'designation-form';
</script>

@section('menu')
    {{--*/ $permissionGroupSlug = 'designations' /*--}}
    @include('partials.navigationMenu')
@endsection

@section('content')

    @include('partials.jqGridTool')
    <div class="designation-list">
        <div id="grid-section" class="app-grid collapse in grid-style">
            {!!
            GridRender::setGridId("NavigatorDesignationGrid")
            ->enablefilterToolbar(true, false)
            ->hideXlsExporter()
            ->hideCsvExporter()
            ->setGridOption('url',URL::to('designation/grid'))
            ->setGridOption('rowNum', 20)
            ->setGridOption('rownumbers', true)
            ->setGridOption('width', 1150)
            ->setGridOption('height', 400)
            ->setGridOption('rowList',array(10,20,30))
            ->setGridOption('caption','Designation Details')
            ->setGridOption('viewrecords',true)
            ->setGridOption('autowidth',true)
            ->setGridEvent('onSelectRow', 'onSelectRowEvent')
            ->addColumn(array('index' => 'id', 'hidden' => true))
            ->addColumn(array('label' => 'Designation', 'index' =>'name'))
            ->addColumn(array('label' => 'Status', 'index' => 'status'))
            ->renderGrid()
            !!}
        </div>
    </div>
    <div id='form-section' class="collapse">
        <div class="form-container">
            <div class="valid"></div>
            {!! Form::open(array('id' => 'designation-form', 'url' => route('designation'), 'role' => 'form', 'class' => 'form-horizontal', 'onsubmit' => 'return false;')) !!}
            <fieldset id="books-form-fieldset">
                <legend id="form-new-title" class="hidden">Create New Designation</legend>
                <div class="form-group hide-on-edit" id='test'>
                    <label class="col-sm-2 control-label" name="name">Designation Name</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">         <i class="fa fa-compass"></i></span>
                            {!! Form::text('name', null , array('id' => 'name',
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


