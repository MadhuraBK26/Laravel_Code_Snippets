@if(!$getCountOfRatings)
    <h1>Your Ratings Has Not Been Issued Yet</h1>
@else
    <div class="pl-dashboard">
        <h1>Report Summary
        <a href="{{ route('admin.export_report_summary',['fileType'=>'csv','fromyear'=>$fromyear, 'toyear'=>$toyear,'peopleId'=>$peopleId]) }}"
                     title="Download Report Summary"  id='export-report-summary' class="fake-link"><i class="fa fa-download fa-fw"></i></a>
        </h1>
        <table class="table table-responsive table-striped pl-table" id='year-table' width="100%">
            <thead>
            <tr>
                <th class="metrix-head">Resource Name</th>
                <th class="metrix-head">Year</th>
                <th class="metrix-head">Given by</th>
                @foreach($getCountOfRatings[0]['ratingMonth'] as $rating)
                <td class="label-metrix-pl tblwidth">{{$rating['month']}}</td>
                @endforeach
            </tr>
            </thead>

            @foreach($getCountOfRatings as $people)
                <tr>
                    <td class="label-metrix-pl tblwidth">{{ $people['name']}}</td>
                    <td class="label-metrix-pl tblwidth">{{ $people['fromYear']}}</td>
                    <td class="label-metrix-pl rating-table ">{{$people['roleName']}}</td>
                    @foreach($people['ratingMonth'] as $ratingMonth)
                        <td class="label-metrix-pl tblwidth">{{ $ratingMonth['count']}}</td>
                    @endforeach
                </tr>
            @endforeach
        </table>
    </div>
@endif