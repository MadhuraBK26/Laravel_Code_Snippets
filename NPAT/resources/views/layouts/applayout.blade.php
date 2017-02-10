<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>NPAT ::@yield('title')</title>
    <link rel="icon" href="/assets/compass.jpg"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css"
          href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,300,400,700">
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css')}}"/>

    {!! HTML::style('assets/font-awesome-v4.1.0/css/font-awesome.min.css')  !!}
    {!! HTML::style('assets/jquery-jqGrid-v4.6.0/css/ui.jqgrid.css')  !!}
    {!! HTML::style('assets/tutorial/css/main.css')  !!}
    {!! HTML::style('assets/tutorial/css/callouts.css')  !!}
    {!! HTML::script('/assets/js/jquery-1.11.3.min.js') !!}
    {!! HTML::script('/assets/js/notify.min.js')  !!}
    {!! HTML::script('/assets/js/jquery.validate.js')  !!}
    {!! HTML::script('assets/jquery-jqGrid-v4.6.0/js/i18n/grid.locale-en.js')  !!}
    {!! HTML::script('assets/jquery-jqGrid-v4.6.0/js/jquery.jqGrid.src.js') !!}
    {!! HTML::script('/assets/js/select2.min.js')!!}
    {!! HTML::script('/assets/js/jquery.ui.monthpicker.js')  !!}
    {!! HTML::script('assets/jquery-jqGrid-v4.6.0/js/i18n/grid.locale-en.js')  !!}
    {!! HTML::script('assets/jquery-jqGrid-v4.6.0/js/jquery.jqGrid.src.js') !!}
    {!! HTML::script('/assets/js/alert.js')!!}


    <script type="text/javascript" src="{{ asset('app/Controller/practiceLead.js') }}"></script>
    <script type="text/javascript" src="{{ asset('app/Controller/reportController.js') }}"></script>
    <script type="text/javascript" src="{{ asset('app/Controller/homeController.js') }}"></script>
    <script type="text/javascript" src="{{ asset('app/Controller/profile.js') }}"></script>

    <script type="text/javascript">

        var GridName;
        var FormName;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function onSelectRowEvent(rowid, status, e) {

            $('#btn-group-2').enableButtonGroup();
            rowdata = $('#' + GridName).getRowData(rowid);
            if(rowdata['status'] != 'Active'){
                $('#btn-group-4').disabledButtonGroup();
                $('#btn-group-5').enableButtonGroup();
            }else{
                $('#btn-group-5').disabledButtonGroup();
                $('#btn-group-4').enableButtonGroup();
            }


        }
        // window.setTimeout(function () {
        //     $(".alert").alert('close');
        // }, 5000);
        $(function () {
            $(document).ready(function () {
                bindFetchQuarterDateRangeToOnChange("select.quarter");
                emptyFieldValidation();
                selectDate();
                loadMetrices();
                feedbackFormValidation();
            });
            getMonthPickerStart("#startMonthDate,#startMonth");
            getMonthPickerStart("#endMonthDate,#endMonth");
            getYearSelector();
            $('#dob').datepicker();
            $('#doj').datepicker();
            $('#lwd').datepicker();
        });
    </script>
    @yield('css')
</head>
<body>
@yield('menu')
<div class="container main-content">
    @include('partials.notification')
    @yield('content')
</div>
{!! HTML::script('/assets/js/bootstrap.min.js') !!}
{!! HTML::script('/assets/js/bootstrap-confirm.min.js') !!}
{!! HTML::script('/assets/js/jquery-ui.min.js') !!}
{!! HTML::script('/assets/js/dependent.js') !!}
{!! HTML::script('assets/jquery-scrollto-v1.4.11/jquery.scrollTo.min.js') !!}
{!! HTML::script('assets/jquery-jqMgVal-v0.1/jquery.jqMgVal.src.js')  !!}
{!! HTML::script('assets/js/jqGridMaster.js')  !!}
{!! HTML::script('assets/tutorial/js/helpers.js')!!}
{!! HTML::script('assets/tutorial/js/base.js')!!}
{!! HTML::script('app/Controller/revisionController.js')!!}
@yield('scripts')

<div class="footer navbar-default">
    <div class="container-fluid">
        <p class="text-muted text-center">&COPY; Copyright - Compassitesinc, 2015</p>
    </div>
</div>
@include('admin.revisions.partial.revisionsListingModal')
</body>
</html>
