@extends('layouts.applayout')

@section('title')
    Feedback-Form
@endsection

@section('menu')
    {{--*/ $permissionGroupSlug = 'feedback-form' /*--}}
    @include('partials.navigationMenu')
@endsection


@section('content')

    <form role="form" id="feedbackInsert" method="POST" action="{{ route('feedback.resource.save') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <div class="body_wrapper" style="padding-bottom: 60px">
            <div class="row">
                <div class="inner_div feedback-form">
                    <h1>Project Performance Feedback Form</h1>
                    <br>

                    <p>To be filled by Practice Head to provide feedback on project team members.
                        This feedback is provided to respective Delivery Heads for assessment during quarterly
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
                            <select class="form-control" name="practiceName" id="practiceName">
                                <option value="" selected>Select Practice</option>
                                @foreach($practicesDetails as $practices)
                                    <option value="{{ $practices['id'] }}">{{ $practices['practices'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control" name="resourceName" id="resourceName">
                                <option value="" selected>Select People</option>
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
@section('scripts')
    <script type='text/javascript'>
        $(function () {
            $('#resourceName,#fromdate').on('change', function () {
                fetchQuarterDateRange();
                var people_id = $('#resourceName').val();
                var startdate = $('#start').val();
                var enddate = $('#end').val();
                if (people_id != null && startdate != null) {
                    getResourceMetrics(people_id, startdate, enddate);
                }
            });

            $('#resourceName').change(function () {
                var val = $(this).val();

                $.get("feedback-form/" + val, function (data) {
                    $("#loadMetrics").html(data);
                    //$('form').validate();

                }).fail(function (data) {
                            $("#loadMetrics").html(data);
                });
            });

            var getResourceMetrics = function (people_id, startdate, enddate) {
                var param = {};
                param.people_id = people_id;
                param.startdate = startdate;
                param.enddate = enddate;

                $.ajax({
                    url: "/ajax-resourcelistcategory/" + people_id + '/' + startdate + '/' + enddate,
                    type: "GET",
                    data: JSON.stringify(param),

                    contentType: "application/json",

                    success: function (response) {
                        $('#loadMetrics').html(response);
                    },
                    error: function () {

                    }
                });
            }
        });   
    </script>

@endsection
