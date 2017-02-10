@extends('layouts.applayout')

@section('title')
    Practice Lead
@endsection

@section('menu')
    @include('partials.navigationMenu')
@endsection

@section('content')

    <div class="container">
        <div class="head-section">
            <h1>Rating Sheet</h1>
        </div>
        <div class="page-head">
            <p>
                <button id="back" class="btn btn-info">Back</button>
            </p>
        </div>

        <div class="resource-name">
            <p>
                <span class="first-value">Resource Name :</span> <span class="second-value">{{ $user['name'] }}</span>
            </p>


            <p>
                <span class="from-date">From :</span> <span
                        class="second-value">{{ date('d-m-y',strtotime($fromDate)) }}</span>
                <span class="to-date">To :</span> <span
                        class="second-value">{{ date('d-m-y',strtotime($toDate)) }}</span>
            </p>
        </div>
        <table class="table table-responsive table-striped pl-table" width="100%">
            <tr>
                <th class="metrix-head bg-info">Metrics Fields</th>
                <th class="metrix-head bg-info">Ratings</th>
                <th class="metrix-head bg-info">Comments</th>
            </tr>
            @foreach ($ratingValue['values'] as $rank)
                <tr>
                    <div class="row">
                        <td class="label-metrix">
                            <div class="col-md-8">{{$rank->metrics}}</div>
                        </td>
                        @if($rank['expectation_id']<config('custom.expectationId'))
                            <td style="background-color:{{config('custom.colors')}} ">
                                <div class="col-md-1"> {{$rank['expectation_id']}}</div>
                            </td>
                        @elseif($rank['expectation_id']==config('custom.expectationId'))
                            <td> <div class="col-md-1">{{$rank['expectation_id'] }}</div></td>
                        @else
                            <td style="background-color:{{config('custom.color')}} ">
                                <div class="col-md-1">{{$rank['expectation_id'] }}</div>
                            </td>
                        @endif
                        <td class="label-metrix">
                            <div class="col-md-8">{{$rank->comments}}</div>
                        </td>
                    </div>
                </tr>
            @endforeach
        </table>
    </div>
@stop
