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
            <a class="navbar-brand" href="{{ url('/') }}"><img src="http://i.imgur.com/LRh95f3.png" height="35px"></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @if(Auth::user()->role_id == '4')
                    <li><a href="{{route('dashboard')}}"><i class="fa fa-bars"></i> Reports</a></li>
                @else
                    <li><a href="#"><i class="fa fa-archive"></i> Report</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('dashboard')}}"> <i class="fa fa-list"></i> Report List</a></li>
                            <li><a href="{{route('reportsummary-display')}}"><i class="fa fa-th"></i> Report Summary</a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="{{ route('register') }}"><i class="fa fa-key"></i> User Management </a></li>
                    <li><a href="{{ route('plfeedback.form') }}"><i class="fa fa-square-o"></i> Feedback Form</a></li>

                @endif

            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::check())
                    <li>
                        <a href="{{route('profile-display')}}">{!! HTML::image('assets/useravatar.png', Auth::user()->name,['class'=>'avatar']) !!} {{ Auth::user()->name }}</a>
                    </li>
                @endif
                <li>
                    <a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</div>
