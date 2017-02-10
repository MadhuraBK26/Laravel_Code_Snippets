<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">Revisions</h4>
</div>
<div class="modal-body">
    <div class="pl-dashboard">
        <table>
            @foreach($revisions as $date => $revisionsForDate)
            <tr>
                <th><u>{{ $date }}</u></th>
            </tr>
            @foreach($revisionsForDate as $revision)
            <tr>
                <td>
                    <b>{{ $revision->key }}</b> was changed from <b>{{ $revision->old_value }}</b>
                    to <b>{{ $revision->new_value }}</b> by <b>{{ $revision->user->name }} </b>
                </td>
            </tr>
            @endforeach
            @endforeach
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

