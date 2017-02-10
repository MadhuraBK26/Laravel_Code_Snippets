@extends('layouts.applayout')

@section('title')
    People
@endsection

@section('menu')
    @include('partials.navigationMenu')
@endsection
@section('content')
    <div class="col-md-12 header-box">
        <div class="panel panel-info pl-table">
            <div class="panel-heading">Select Your Report</div>
            <div class="panel-body">
                {!! Form::open(array('route' => 'resource-ratingsheet', 'class' => 'form', 'id' => 'resource-data')) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="col-sm-3 drop-space">
                    <select class="form-control placeholder" id="resource-project" name="resource-project"
                            value="{{old('resource-project')}}" required>
                        <option value="">Select Project</option>
                        @foreach($projectDetails as $project)
                            <option value="{{ $project['id'] }}">{{ $project['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3 drop-space">
                    <input class="form-control fieldnull" value="{{old('start_date')}}" placeholder="From"
                           type="text" id="startMonth" name="start_date" required/>
                </div>
                <div class="col-sm-3">
                    <input class="form-control fieldnull" value="{{old('end_date')}}" placeholder="To"
                           type="text" id="endMonth" name="end_date" required/>
                </div>

                <div class="col-sm-1">
                    <button type="submit" id="form-submit" name="submit" class="btn btn-info">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <section id="resource-sheet"></section>
@endsection

