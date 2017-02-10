<div class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">

        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
            data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url('/') }}"><img src="http://i.imgur.com/LRh95f3.png" width='112px'
         height="35px"></a>
     </div>
     <!-- Collect the nav links, forms, and other content for toggling -->
     <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            <li><a href="{{ route('project') }}"><i class="fa fa-tasks"></i> Projects</a></li>
            <li><a href="{{ route('navigator') }}"><i class="fa fa-users"></i> Navigators</a></li>
            <li><a href="{{ route('register') }}"><i class="fa fa-key"></i> User Management </a></li>
            <li><a href="{{ route('metrics') }}"> <i  class="fa fa-trophy"></i> Metrics </a></li>
            <li><a href="{{ route('designation') }}"> <i  class="fa fa-compass"></i> Designation </a></li>
            <li><a href="{{ route('role')}}"> <i class="fa fa-rmb"></i> Roles</a></li>
            <li><a href="{{ route('practices')}}"> <i class="fa fa-cube"></i> Practices</a></li>
            <li><a href="#"><i class="fa fa-archive"></i> Report</a>
                <ul class="dropdown-menu">

                    <li><a href="{{route('adminReport')}}"><i class="fa fa-list"></i> Report List</a></li>
                    <li><a href="{{route('reportsummary-display')}}"><i class="fa fa-th"></i> Report Summary</a></li>

                </ul>
            </li>

        </ul>

        <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
            <li>
                <a href="#">{!! HTML::image('assets/useravatar.png', Auth::user()->name,['class'=>'avatar']) !!} {{ Auth::user()->name }}</a>
            </li>
            @endif
            <li>
                <a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
            </li>
        </ul>
    </div>
</div>
</div>
