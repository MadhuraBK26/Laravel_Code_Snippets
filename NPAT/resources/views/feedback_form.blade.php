@extends('layouts.applayout')

@section('title')
Feedback-Form
@endsection

@section('menu')
    {{--*/ $permissionGroupSlug = 'practices' /*--}}
    @include('partials.navigationMenu')
@endsection

@section('content')

<form role="form" id="feedbackInsert" method="POST" action="{{ route('feedback.save') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="body_wrapper" style="padding-bottom: 60px">
        <div class="row">
            <div class="inner_div feedback-form">
                <h1>Project Performance Feedback Form</h1>
                <br>
                <p>To be filled by Project Manager to provide feedback on project team members.
                    This feedback is provided to respective Practice Leads for assessment during quarterly
                    review.</p>
                    <br>
                </div>
                <div class="form_top1">
                    Project Information
                </div>
            </div>
            <section>
            
                <div class="row form_3">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control"
                            data-bv-message=""
                            required data-bv-notempty-message=""
                            pattern="" data-bv-regexp-message=""
                            data-bv-stringlength="true" data-bv-stringlength-min=""
                            name="projectTitleSelect" id="projectName">
                            <option value="" selected>Select Project Title</option>
                            @foreach($projectData['project'] as $projectValue)
                            <option value="{{ $projectValue->id }}">{{ $projectValue->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <select class="form-control" data-bv-message=""
                        required data-bv-notempty-message=""
                        pattern="" data-bv-regexp-message=""
                        data-bv-stringlength="true" data-bv-stringlength-min=""  id="resourceName" name="resourceSelect">
                        <option value="">Select people</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                        <div class='col-sm-3'>
                            <select class="form-control year" id="fromyear" name="fromyear" required>
                                <option value="">Select Year</option>
                            </select>
                        </div>
                        <div class='col-sm-3'>
                            <select class="form-control placeholder quarter" id="fromdate" name="fromdate" required>
                                <option value="">Select Quarter</option>
                                <option value="1">Jan-Mar</option>
                                <option value="2">Apr-Jun</option>
                                <option value="3">Jul-Sep</option>
                                <option value="4">Oct-Dec</option>
                            </select>
                            <input type="hidden" id="start" name="start">
                            <input type="hidden" id="end" name="end">
                        </div>
                    </div>
        </div>
    </section>
    <section id="loadMetrics">
    </section>
</div>
</form>
@endsection
