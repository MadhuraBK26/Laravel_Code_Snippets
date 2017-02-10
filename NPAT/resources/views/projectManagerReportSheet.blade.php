@if ($projectUsers === null)
    <h3 class="text-center"> Sorry, Data not available! </h3>
@else
    <div class="pl-dashboard">
        <h1>Project Manager Dashboard</h1>
        <table class="table table-responsive table-striped pl-table" width="100%">
            <thead>
            <tr>
                <th class="metrix-head">Project Title</th>
                <th class="metrix-head">Resource Name</th>
                <th class="metrix-head">From</th>
                <th class="metrix-head">To</th>
                <th class="metrix-head">Operation</th>
                <th></th>
            </tr>
            </thead>
            @foreach($projectUsers as $project)
                <tr>
                    <td class="label-metrix-pl">{{ $project['projectName']}}</td>
                    <td class="label-metrix-pl">{{ $project['peopleName']}}</td>
                    <td class="label-metrix-pl">{{ date('d-m-y',strtotime($project['start_date']))}}</td>
                    <td class="label-metrix-pl">{{ date('d-m-y',strtotime($project['end_date']))}}</td>
                    <td><a class="label-show btn btn-primary btn-xs"
                           href="{{ URL::route('report.rating',array($project['peopleId'],$project['projectId'],$project['start_date'],$project['end_date'])) }}"
                           class="styleClass">View</a></td>
                    @if (Session::get('role') != config('custom.projectManagerLead'))
                        <td><a class="glyphicon glyphicon-pencil editicon  btn-sm"
                               href="{{ URL::route('edit.feedback.form',array($project['recordId'],$project['start_date'],$project['end_date'])) }}"
                               class="styleClass"></a></td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
@endif
