@inject('acl', 'Acl')
<div id="btn-toolbar" class="section-header btn-toolbar" role="toolbar">
    <div id="btn-group-1" class="btn-group">
        @canCrud ($permissionGroupSlug,'create')
        {!! Form::button('<i class="fa fa-plus"></i> New', array('id' => 'btn-new', 'class' => 'btn btn-default tutorial-tooltip', 'data-container' => 'body', 'data-toggle' => 'tooltip', 'data-original-title' => 'Add a new book')) !!}
        @endCanCrud
        {!! Form::button('<i class="fa fa-refresh"></i> Refresh', array('id' => 'btn-refresh', 'class' => 'btn btn-default tutorial-tooltip', 'data-container' => 'body', 'data-toggle' => 'tooltip', 'data-original-title' => 'Refresh grid data')) !!}
        <div class="btn-group">
            @if(Request::is('navigator'))
                {!! Form::button('<i class="fa fa-share-square-o"></i> Export <span class="caret"></span>', array('class' => 'btn btn-default dropdown-toggle', 'data-container' => 'body', 'data-toggle' => 'dropdown')) !!}
                <ul class="dropdown-menu">
                    <li><a href="{{ route('admin.export_data.navigator',['fileType'=>'csv']) }}" id='export-csv'
                           class="fake-link"><i class="fa fa-file-text-o"></i> csv</a></li>
                </ul>
            @endif
        </div>
    </div>
    <div id="btn-group-2" class="btn-group">
        @canCrud ($permissionGroupSlug,'update')
        {!! Form::button('<i class="fa fa-edit"></i> Edit', array('id' => 'btn-edit', 'class' => 'btn btn-default tutorial-tooltip', 'data-container' => 'body', 'data-toggle' => 'tooltip', 'disabled' => '', 'data-original-title' => 'Edit book')) !!}

        @endCanCrud
        @canCrud ($permissionGroupSlug,'delete')
        {!! Form::button('<i class="fa fa-trash-o"></i> Delete', array('id' => 'btn-delete', 'class' => 'btn btn-default tutorial-tooltip', 'data-container' => 'body', 'data-toggle' => 'tooltip', 'disabled' => '', 'data-original-title' => 'Delete book')) !!}
        @endCanCrud
    </div>
    @if(Request::is('register'))
    <div id="btn-group-4" class="btn-group button-left">
        {!! Form::button('<i class="fa fa-times"></i> Deactivate', array('id' => 'btn-deactive', 'class' => 'btn btn-default tutorial-tooltip', 'data-container' => 'body', 'data-toggle' => 'tooltip', 'disabled' => '', 'data-original-title' => 'Deactivate User')) !!}
    </div>
    <div id="btn-group-5" class="btn-group button-left">
        {!! Form::button('<i class="fa fa-check"></i> Activate', array('id' => 'btn-active', 'class' => 'btn btn-default tutorial-tooltip', 'data-container' => 'body', 'data-toggle' => 'tooltip', 'disabled' => '', 'data-original-title' => 'Activate User')) !!}
    </div>
    @endif
    <div id="btn-group-3" class="btn-group toolbar-block">
        @canCrud ($permissionGroupSlug,'create')
        {!! Form::button('<i class="fa fa-save"></i> Save', array('id' => 'btn-save', 'class' => 'btn btn-default tutorial-tooltip', 'data-container' => 'body', 'data-toggle' => 'tooltip', 'disabled' => '', 'data-original-title' => 'Save book')) !!}
        {!! Form::button('<i class="fa fa-times"></i> Close', array('id' => 'btn-close', 'class' => 'btn btn-default tutorial-tooltip', 'data-container' => 'body', 'data-toggle' => 'tooltip', 'disabled' => '', 'data-original-title' => 'Return to the grid view (data that has not been saved will be lost.)')) !!}
        @endCanCrud
    </div>
</div>