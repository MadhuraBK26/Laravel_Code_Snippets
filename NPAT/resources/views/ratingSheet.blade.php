@extends('layouts.applayout')


@section('title')
    Rating Sheet
@endsection

@section('menu')
    @include('partials.navigationMenu')
@endsection


@section('content')

<?php
$i = 0;
$j = 0;
$categoryId = '';
$ratingTotalCount = count($userRatingDetails[0]['values']);
?>
    <div class="container">
        <div class="head-section">
            <h1>Rating Sheet</h1>
        </div>
        <div class="page-head ">
            <div class="btn-group  pull-right">
                {!! Form::button('<i class="fa fa-download fa-lg"></i> Export <span class="caret"></span>', array('class' => 'btn btn-default dropdown-toggle', 'data-container' => 'body', 'data-toggle' => 'dropdown')) !!}
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.export_data.export_rating_report',['fileType'=>'csv','peopleId'=>$peopleId,'projectId'=>$projectId,'managerId'=>$managerId,'fromDate'=>$fromDate,'toDate'=>$toDate]) }}"
                           id='export-csv' class="fake-link"><i class="fa fa-file-text-o"></i> csv</a></li>
                </ul>
            </div>
            <div class="btn-group  pull-right">
                <span>&nbsp;</span>
                <button id="back" class="btn btn-info">Back</button>
            </div>
        </div>
        <div class="resource-name">
            @foreach($managerRatingDetails as $projects )
                <p>
                    <span class="first-value">Resource Name :</span> <span class="second-value">{{$user['name']}}</span>
                    @if($userDetails['name'] === $projects['name'])
                        <span class="first-value">PracticeLead Id :</span>
                    @else
                        <span class="first-value">Manager Id :</span>
                    @endif
                    <span class="second-value">{{ $projects['emp_id'] }}</span>
                    @if($userDetails['name'] === $projects['name'])
                        <span class="first-value">PracticeLead Name :</span>
                    @else
                        <span class="first-value">Manager Name :</span>
                    @endif
                    <span class="second-value">{{ $projects['name'] }}</span>
                </p>
            @endforeach
        </div>
    </div>
    <table id="ratingtable" class="table table-striped">
        <thead>
        <tr>
            <th>Metrics Fields</th>
            @foreach($userRatingDetails[0]['values'] as $rating)
                <?php if(!empty($rating)){ ?>
                <th class="">
                    @if($userDetails['name'] === $rating['name'] || Session::get('role') == config('custom.adminId') || Session::get('role') == config('custom.DeliveryHead'))
                        @if((date('M', strtotime($rating['start_date']))) != (date('M', strtotime($rating['end_date']))))
                            <p align="center">{{date('M', strtotime($rating['start_date']))}}
                                - {{date('M', strtotime($rating['end_date']))}}</p>
                        @else
                            <p align="center">{{date('M', strtotime($rating['start_date']))}}
                            - {{date('M', strtotime($rating['start_date']))}}</p>
                        @endif
                    @else
                        <p align="center">{{date('M', strtotime($rating['start_date']))}}
                       - {{date('M', strtotime($rating['end_date']))}}</p>
                    @endif
                        <p align="center">{{date('Y', strtotime($rating['start_date']))}}</p>
                    <p align="center">{{$rating['emp_id']}}</p>
                    <button type="button" class="btn btn-info com{{$i}}" name="com{{$i}}" data-toggle="modal"
                            data-target="#myModal{{$i}}">Comments
                    </button>
                </th>
                <?php } $i++; ?>
            @endforeach
        </tr>
        </thead>
        
        @if($quarter_percent)
        <tr>
            <td class="label-metrix rating_label"><b>Overall Rating :  </b></td>
            @foreach($quarter_percent as $percentage)
                <td class="rating_value_label label-expection"><b>{{($percentage) == 0 ? 'N/A':$percentage}}</b></td>
            @endforeach
        </tr>
        @endif

        @foreach ($userRatingDetails as $rank)
            
            @if($rank['category_id'] != $categoryId && !in_array($user['navigator_designation_id'], config('custom.designation_group4')) )
                <tr><td><b>{{ $rank['name'] }}</b></td><td></td></tr>
            @endif
            <?php $categoryId = $rank['category_id']; ?>
            <tr>
                <td class="label-metrix ">{{$rank['metrics']}}</td>
                <?php $j = 0; ?>
                @while($j < $ratingTotalCount)
                    <?php
                    $backgroundColor = "";
                    $expectation_id = "--";
                    $rating = array_get($rank, "values." . $j);

                    if($rating && array_has($rating, 'expectation_id') && $rating['expectation_id']) {
                        $expectation_id = $rating['expectation_id'];
                        $backgroundColor = $rating['expectation_id'] > config('custom.expectationId') ? config('custom.color') : ($rating['expectation_id'] < config('custom.expectationId') ? config('custom.colors') : "");
                        $modal[$i][] = $rating['comments'];
                    }
                    ?>
                    <td class="label-expection" style="background-color:{{ $backgroundColor }} ">
                        {{ $expectation_id }}
                    </td>

                    <?php  $j++; ?>  
                @endwhile
                
            </tr>
        @endforeach
    </table>
  
    <?php $j = 0; ?>
    @foreach ($userRatingComments as $rank)
        <div id="myModal{{$j}}" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <p class="modal-title specific">Rated By:{{$rank['name']}}</p>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach($rank['values'] as $rating)
                                <div class="col-md-6">
                                    <span class="specific">{{$rating['metrics']}}:</span> <br>
                                    <span class="commentspace">{{$rating['comments']}}</span>
                                    <hr>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $j++; ?>
    @endforeach
@stop


￼

