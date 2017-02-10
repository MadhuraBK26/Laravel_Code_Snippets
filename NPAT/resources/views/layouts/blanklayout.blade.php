<html lang="en">
<head>
   <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>NPAT</title>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}">
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,300,400,700">
    <link rel="stylesheet" href="{{'/assets/css/jquery-ui.min.css'}}"/>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>

    {!! HTML::style('assets/font-awesome-v4.1.0/css/font-awesome.min.css')  !!}
    {!! HTML::style('assets/jquery-jqGrid-v4.6.0/css/ui.jqgrid.css')  !!}
    {!! HTML::style('assets/tutorial/css/main.css')  !!}
    {!! HTML::style('assets/tutorial/css/callouts.css')  !!}
    {!! HTML::script('/assets/js/jquery-1.11.3.min.js')  !!}
    {!! HTML::script('/assets/js/jquery.validate.js')  !!}
    {!! HTML::script('assets/jquery-jqGrid-v4.6.0/js/i18n/grid.locale-en.js')  !!}
    {!! HTML::script('assets/jquery-jqGrid-v4.6.0/js/jquery.jqGrid.src.js') !!}
    {!! HTML::script('/assets/js/jquery.monthpicker.min.js')  !!}
    {!! HTML::script('/assets/js/jquery.ui.monthpicker.js')  !!}
    {!! HTML::script('assets/jquery-jqGrid-v4.6.0/js/i18n/grid.locale-en.js')  !!}
    {!! HTML::script('assets/jquery-jqGrid-v4.6.0/js/jquery.jqGrid.src.js') !!}

    <script type="text/javascript" src="{{ asset('app/Controller/practiceLead.js') }}"></script>
    <script type="text/javascript" src="{{ asset('app/Controller/reportController.js') }}"></script>
    <script type="text/javascript" src="{{ asset('app/Controller/homeController.js') }}"></script>
    @yield('css')
</head>
<body>
    <div class="container main-content">
        @yield('content')
    </div>
    {!! HTML::script('/assets/js/bootstrap.min.js') !!}
    {!! HTML::script('/assets/js/jquery-ui.min.js') !!}
    {!! HTML::script('/assets/js/dependent.js') !!}
    {!! HTML::script('assets/jquery-scrollto-v1.4.11/jquery.scrollTo.min.js') !!}
    {!! HTML::script('assets/jquery-jqMgVal-v0.1/jquery.jqMgVal.src.js')  !!}
    {!! HTML::script('assets/js/jqGridMaster.js')  !!}
    {!! HTML::script('assets/tutorial/js/helpers.js')!!}
    {!! HTML::script('assets/tutorial/js/base.js')!!}
    @yield('scripts')
</body>
</html>
