@extends('layouts.applayout')

@section('title')
    Feedback-Form
@endsection

@section('menu')
@section('menu')
    {{--*/ $permissionGroupSlug = 'feedback-form' /*--}}
    @include('partials.navigationMenu')
@endsection
@endsection

@section('content')

    <form role="form" id="feedbackInsert" method="POST" action="{{ route('feedback.update',$recordId) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" value="{{ $recordId }}" name="id">
        <div class="body_wrapper" style="padding-bottom: 60px">
            <div class="row">
                <div class="inner_div feedback-form">
                    <h1>Project Performance Feedback Form</h1>
                    <br>
                    <p>To be filled by Project Manager to provide feedback on project team members.
                        This feedback is provided to respective Practice Leads for assessment during quarterly
                        review</p>
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
                            @if(Session::get('role') === config('custom.practiceLeadId') && $feedbackData['type'] == 2)
                                <select class="form-control"
                                        data-bv-message=""
                                        required data-bv-notempty-message=""
                                        pattern="" data-bv-regexp-message=""
                                        data-bv-stringlength="true" data-bv-stringlength-min=""
                                        name="practiceName" id="practiceName">
                                    <option value="{{ $practiceData->id }}">{{ $practiceData->practices }}</option>
                                    </select>
                            @else
                            <select class="form-control"
                                    data-bv-message=""
                                    required data-bv-notempty-message=""
                                    pattern="" data-bv-regexp-message=""
                                    data-bv-stringlength="true" data-bv-stringlength-min=""
                                    name="projectTitleSelect" id="projectName">
                                <option value="{{$userProjectData->id}}">{{$userProjectData->name}}</option>
                            </select>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" data-bv-message=""
                                    pattern="" data-bv-regexp-message=""
                                    data-bv-stringlength="true" data-bv-stringlength-min="" id="resourceName"
                                    name="resourceSelect">
                                <option value=""> {{ $peopleData->name }}</option>
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
                                <option value="1" {{($quarterYear['quarterMonth'] == 1 ? 'selected="selected"' : '') }}>Jan-Mar</option>
                                <option value="2" {{($quarterYear['quarterMonth'] == 2 ? 'selected="selected"' : '')}}>Apr-Jun</option>
                                <option value="3" {{($quarterYear['quarterMonth'] == 3 ? 'selected="selected"' : '')}}>Jul-Sep</option>
                                <option value="4" {{($quarterYear['quarterMonth'] == 4 ? 'selected="selected"' : '')}} >Oct-Dec</option>
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

@section('scripts')
    <script>
     var year = "{{ $quarterYear['year'] }}";
        $(function () {

            var peopleID = "{{$feedbackData['people_id']}}";
            var feedbackId = "{{$feedbackData['id']}}";
            $.get("/feedback-form/" + peopleID + "/" + feedbackId, function (data) {
                $("#loadMetrics").html(data);
            })
                    .fail(function (data) {
                        $("#loadMetrics").html(data);
                    })
        });
        var projectID = "{{$feedbackData['project_id']}}";
        var feedbackId = "{{$feedbackData['id']}}";

        $('#projectName').val(projectID);
        $(document).ready(function(){
            $("#fromyear").val(year);
        });

    </script>
@endsection