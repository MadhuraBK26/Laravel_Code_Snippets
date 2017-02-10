@extends('layouts.applayout')

@section('title')
    Project Manager Report
@endsection

@section('menu')
    @include('partials.navigationMenu')
@endsection


@section('content')
    <div class="container">
        <div class="col-md-12 header-box">
            <div class="panel panel-info pl-table">
                <div class="panel-heading">Project Manager Report</div>
                <div class="panel-body">
                    {!! Form::open(array('route' => 'report', 'class' => 'form', 'id' => 'projectManagerReport')) !!}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">

                    <div class="form-group">

                        <div class='col-sm-3 drop-space'>
                            <input class="form-control" value="{{old('start_date')}}" id="startMonthDate" name="start_date" placeholder="Select Month" required>
                        </div>

                        <div class='col-sm-3 drop-space'>
                            <input class="form-control" value="{{old('end_date')}}" id="endMonthDate" name="end_date" placeholder="Select Month" required>
                        </div>

                        <div class='col-sm-3'>
                            <select class="form-control placeholder" id="resource" name="resource" value="{{old('resource')}}" required>
                                <option value="">Select People</option>
                                @foreach($users as $peopleName)
                                    <option value="{{ $peopleName['id'] }}">{{ $peopleName['name']}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="col-sm-1">
                            <button type="submit" name="submit" class="btn btn-info">Submit</button>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(Session::has('message'))
            <div class="alert alert-info">
                {{Session::get('message')}}x
            </div>
        @endif
    </div>
    <section id="pm-report-sheet"></section>
@endsection