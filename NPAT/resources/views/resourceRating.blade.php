@if($projectUsers === null)
    <h3 class="text-center"> Sorry, Data not available! </h3>
@else
    <div class="pl-dashboard">
        <h1>Your Ratings</h1>
        <table class="table table-responsive table-striped pl-table" width="100%">
            <tr>
                <th class="metrix-head">Project Title</th>
                <th class="metrix-head">Project Manager</th>
                <th class="metrix-head">From</th>
                <th class="metrix-head">To</th>
                <th class="metrix-head">Rating</th>
            </tr>
            @foreach($arraydata as $project)
                <tr>
                    <td class="label-metrix-pl">{{ $project['projectName']}}</td>
                    <td class="label-metrix-pl">{{ $project['managerName']}}</td>
                    <td class="label-metrix-pl">{{ date('M',strtotime($startdate))}}</td>
                    <td class="label-metrix-pl">{{ date('M',strtotime($enddate))}}</td>
                    <td><a class="label-show btn btn-primary btn-xs" href="{{ URL::route('resource.rating',array($project['encPeopleId'],$project['encProjectId'],$project['encManagerId'],
                            $startdate, $enddate)) }}">View</a>
                    </td>

                </tr>
            @endforeach
        </table>
    </div>

@endif