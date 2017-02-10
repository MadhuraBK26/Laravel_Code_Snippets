@extends('layouts.applayout')

@section('title')
    Create projects
@endsection

<script type="text/javascript">
    GridName = 'ProjectGrid';
    FormName = 'project-form';
</script>

@section('menu')
    {{--*/ $permissionGroupSlug = 'projects' /*--}}
    @include('partials.navigationMenu')
@endsection

@section('content')

    @include('partials.jqGridTool')
    <div class="project-list">
        <div id="grid-section" class="app-grid collapse in grid-style">
            {!!
            GridRender::setGridId("ProjectGrid")
            ->enablefilterToolbar(true, false)
            ->hideXlsExporter()
            ->hideCsvExporter()
            ->setFilterToolbarOptions(array('autosearch'=>true)) 
            ->setGridOption('url',URL::to('project/grid'))
            ->setGridOption('rowNum', 20)
            ->setGridOption('rownumbers', true)
            ->setGridOption('width', 1150)
            ->setGridOption('height', 400)
            ->setGridOption('rowList',array(10,20,30))
            ->setGridOption('caption','Project Details')
            ->setGridOption('viewrecords',true,'width',100)
            ->setGridOption('autowidth',100)
            ->setGridOption('shrinkToFit',true)
            ->setGridEvent('onSelectRow', 'onSelectRowEvent')
            ->addColumn(array('index' => 'id', 'hidden' => true))
            ->addColumn(array('label' => 'Project', 'index'=>'name'))
             ->addColumn(array('index' => 'status', 'hidden' => true))
            ->addColumn(array('label' => 'Project Created Date','index' => 'project_created_date'))
            ->addColumn(array('label' => 'Project End Date','index' => 'project_end_date'))
            ->addColumn(array('label' => 'Status','index' => 'status'))
            ->renderGrid()
            !!}
        </div>
    </div>
    <div id='form-section' class="collapse">
        <div class="valid"></div>
        <div class="form-container">
            {!! Form::open(array('id' => 'project-form', 'url' => route('project'), 'role' => 'form', 'class' => 'form-horizontal', 'onsubmit' => 'return false;')) !!}
            <fieldset id="books-form-fieldset">
                <legend id="form-new-title" class="hidden">Create New Project</legend>
                <div class="form-group hide-on-edit" id='test'>
                    <label class="col-sm-2 control-label" name="name">Project Name</label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">         <i class="fa fa-tasks"></i></span>
                            {!! Form::text('name', null , array('id' => 'name',
                            'class' => 'form-control no-edit','data-mg-required' => ''))
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
                <div class="form-group">
                    <label class="col-sm-2 control-label" name="project_created_date"> Start Date </label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">    <i class="fa fa-calendar"></i></span>
                            <input type="text" id="project-created-date" name="project_created_date"
                                   class="form-control no-edit" data-mg-required>
                        </div>
                    </div>
                </div>
                <div class="form-group" id='test'>
                    <label class="col-sm-2 control-label" name="project_end_date"> End Date </label>

                    <div class="col-sm-10">
                        <div class="input-group">
                            <span class="input-group-addon">    <i class="fa fa-calendar"></i></span>
                            <input type="text" id="project-end-date" name="project_end_date" class="form-control edit"
                                   data-mg-required>
                        </div>
                    </div>
                </div>
            </fieldset>
            {!! Form::close() !!}  </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{ asset('app/Controller/projectIndex.js') }}"></script>
@endsection
