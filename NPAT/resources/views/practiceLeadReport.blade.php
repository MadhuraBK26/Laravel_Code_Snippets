@if ($arr === null)
    <h3 class="text-center"> Sorry, Data not available! </h3>
@else
    <div class="pl-dashboard">
        @if($roleId != config('custom.adminId'))
            <h1>Practice Lead Dashboard</h1>
        @else
            <h1>Navigator Reports
            @if(!empty($fromdate))
            <div class="btn-group  pull-right">
                {!! Form::button('<i class="fa fa-download fa-lg"></i> Export <span class="caret"></span>', array('class' => 'btn btn-default dropdown-toggle', 'data-container' => 'body', 'data-toggle' => 'dropdown')) !!}
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.export_report_rating_summary',['fileType'=>'csv','projectId'=> $projectId, 'fromdate' => $fromdate,
                'todate' => $todate, 'peopleId'=> $peopleId, 'practicesId' => $practicesId ]) }}" class="fake-link"><i class="fa fa-file-text-o"></i> csv</a></li>
                </ul>
            </div></h1>
            @endif
        @endif
        
        <table class="table table-responsive table-striped pl-table" width="100%">
            <thead>
            <tr>
               @if(empty($practicesId))
                    <th class="metrix-head">Project Title</th>
                @else
                    <th class="metrix-head">Practices Name</th>
                @endif  
                <th class="metrix-head">Resource Name</th>
                <th class="metrix-head">Designation</th>
                @if(!empty($fromdate))
                    <th class="metrix-head">Month</th>
                    <th class="metrix-head">Rating</th>
                @else
                    @if(!empty($practicesId))
                    <th class="metrix-head">Practice Head</th>
                     @else 
                     <th class="metrix-head">Project Manager/Practice Head</th>
                @endif
                @endif
                <th class="metrix-head">Operation</th>
            </tr>
            </thead>
            @foreach($arr as $project )
            <?php $rating = 0;
                if(isset($resourceRatings[$project['peopleIdVal']][2])){
                    $rating = $resourceRatings[$project['peopleIdVal']][2];
                }else{
                    $rating = isset($resourceRatings[$project['peopleIdVal']][1]) ? $resourceRatings[$project['peopleIdVal']][1] : 0;
                }
                $start_arr = explode(',', $project['start_date']);
                $end_arr = explode(',', $project['end_date']);
                $endDate_count = (count($end_arr)-1) >=0 ? count($end_arr)-1 :0;
            ?>
                <tr>
                    <td class="label-metrix-pl">{{ $project['projectName'] }}</td>
                    <td class="label-metrix-pl">{{ $project['peopleName'] }}</td>
                    <td class="label-metrix-pl">{{ $project['designationName'] }}</td>
                    @if(! empty($fromdate))
                        <td class="label-metrix-pl">{{date('M', strtotime($start_arr[0])) }} - {{date('M', strtotime($end_arr[$endDate_count]))}}</td>
                        <td class="label-metrix-pl">{{ ($rating == 0)?'N/A':$rating }}</td>
                        <td><a class="label-show btn btn-primary btn-xs" href="{{ URL::route('resource.rating',array($project['encPeopleId'],$project['encProjectId'],$project['encManagerId'], $fromdate, $todate)) }}">View</a></td>
                        @if(Session::get('role') != config('custom.adminId') && !empty($projectId))
                        <td><a class="glyphicon glyphicon-pencil editicon  btn-sm" href="{{ URL::route('edit.feedback.form', array($project['recordId'],$project['start_date'],$project['end_date'])) }}" class="styleClass"></a></td>
                        @endif
                    @else                    
                        <td class="label-metrix-pl">{{ $project['managerName'] }}</td>
                        <td><a class="label-show btn btn-primary btn-xs" href="{{ URL::route('resource.ratingSheet',array($project['encPeopleId'],$project['encProjectId'],$project['encManagerId']))}}}">View</a></td>
                    @endif
                </tr>
            @endforeach
        </table>
    </div>
@endif