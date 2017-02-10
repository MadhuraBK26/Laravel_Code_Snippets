@extends('layouts.applayout')

@section('title')
    Report List
@endsection

@section('menu')
    @include('partials.navigationMenu')
@endsection

@section('content')
    <div class="container">

        <div class="col-md-12 header-box">
            <div class="panel panel-info pl-header pl-table head-width">
                <div class="panel-heading">Select Records Based On Dates:</div>
                <div class="panel-body">
                    <input type="hidden" name="role-id" id="role_id" value="{{Session::get('role')}}"> 
                    {!! Form::open(array('route' => 'reportdata', 'class' => 'form', 'id' => 'pl-report-data')) !!}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @if(Session::get('role') != config('custom.DeliveryHead') && Session::get('role') != config('custom.adminId'))
                    <div class='col-sm-3 drop-space'>
                        <select class="form-control placeholder project" id="project" name="project">
                            <option value="">Select Project</option>
                            @foreach($projectNames as $project)
                                <option data-custom="projects" value="{{ $project['id']}}">{{$project['name']}}</option>
                            @endforeach
                            @foreach($practiceNames as $practice)
                                <option data-custom="practices" value="{{ $practice['id']}}">{{$practice['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <input type="hidden" name="selected-data" id="selected-data">

                    @if(Session::get('role') != config('custom.adminId'))
                        <div class='col-sm-3 drop-space'>
                            <select class="form-control placeholder people" id="people" name="people">
                                <option value="">Select People</option>
                                @foreach($getPeopleName as $project)
                                    <option value="{{ $project['id']}}">{{$project['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if(Session::get('role') == config('custom.adminId'))

                        <div class='col-sm-3 drop-space'>
                            <select class="form-control" name="practiceName" id="practiceName">
                                <option value="" selected>Select Practice</option>
                                @foreach($practicesDetails as $practices)
                                    <option value="{{ $practices['id'] }}">{{ $practices['practices'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class='col-sm-3 drop-space'>
                            <select class="form-control placeholder people" id="people" name="people">
                                <option value="">Select People</option>
                                @foreach($users as $peopleName)
                                    <option value="{{ $peopleName['id'] }}">{{ $peopleName['name']}}</option>
                               @endforeach
                            </select>
                        </div>

                    @endif

                    <div class='col-sm-2'>
                        <select class="form-control year" id="fromyear" name="fromyear">
                            <option value="">Select Year</option>
                        </select>
                    </div>
                    <div class='col-sm-2'>
                        <select class="form-control placeholder quarter" id="fromdate"
                                name="fromdate">
                            <option value="">Select Quarter</option>
                            <option value="1">Jan-Mar</option>
                            <option value="2">Apr-Jun</option>
                            <option value="3">Jul-Sep</option>
                            <option value="4">Oct-Dec</option>
                        </select>
                        <input type="hidden" id="start" name="start">
                        <input type="hidden" id="end" name="end">
                    </div>
                    <button type="submit" id="search-id" class="btn btn-primary">Search</button>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        @if(Session::has('message'))
            <div class="alert alert-info">
                {{Session::get('message')}}x
            </div>
        @endif
    </div>
    <section id="pl-report-sheet"></section>
@endsection
