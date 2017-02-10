@extends('layouts.applayout')

@section('title')
    Project Manager Report
@endsection

@section('menu')
    @include('partials.navigationMenu')
@endsection


@section('content')
    <div class="col-md-12 header-box">
        <div class="panel panel-info pl-table">
            <div class="panel-heading">Report Summary</div>
            <div class="panel-body">
                {!! Form::open(array('route' => 'reportsummary', 'class' => 'form', 'id' => 'reportsummary-data')) !!}
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class='col-sm-3 drop-space'>
                    <select class="form-control year" id="fromyear" name="fromyear" required>
                        <option value="">Select From Year</option>
                    </select>
                </div>
                <div class='col-sm-3 drop-space'>
                    <select class="form-control year" id="toyear" name="toyear" required>
                        <option value="">Select To Year</option>
                    </select>
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
                    <button type="submit" id="form-submit" name="submit" class="btn btn-info">Submit</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <section id="report-summary-sheet"></section>
@endsection