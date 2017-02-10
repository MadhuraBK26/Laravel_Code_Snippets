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

            <a class="navbar-brand" href="{{ route('project') }}"><img src="http://i.imgur.com/LRh95f3.png"
                                                                            width='112px'
                                                                            height="35px"></a>

        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            @inject('acl', 'Acl')
            <ul class="nav navbar-nav common-nav">
                @hasPermission('list-projects')
                <li><a href="{{ route('project') }}"><i class="fa fa-tasks"></i> Projects</a></li>
                @endHasPermission
                @hasPermission('list-of-navigators')
                <li><a href="{{ route('navigator') }}"><i class="fa fa-users"></i> Navigators</a></li>
                @endHasPermission
                @hasPermission('list-of-users')
                <li><a href="{{ route('register') }}"><i class="fa fa-key"></i> User Management </a></li>
                @endHasPermission
                @hasPermission('list-of-metrics')
                <li><a href="{{ route('metrics') }}"> <i class="fa fa-trophy"></i> Metrics </a></li>
                @endHasPermission
                @hasPermission('list-designation')
                <li><a href="{{ route('designation') }}"> <i class="fa fa-compass"></i> Designation </a></li>
                @endHasPermission
                @hasPermission('list-of-reports')
                @if (Session::get('role') == config('custom.projectManagerId'))
                    <li><a href="#"><i class="fa fa-archive"></i> Report</a>
                        <ul class="dropdown-menu">

                            <li><a href="{{url('projectmanager-report')}}"><i class="fa fa-list"></i> Report List</a>
                            </li>

                            <li><a href="{{route('reportsummary-display')}}"><i class="fa fa-th"></i> Report Summary</a>

                            </li>

                        </ul>
                    </li>
                @elseif(Session::get('role') == config('custom.practiceLeadId')|| Session::get('role') == config('custom.adminId') ||
                Session::get('role') == config('custom.projectManagerLead') || Session::get('role') == config('custom.DeliveryHead'))
                    <li><a href="#"><i class="fa fa-archive"></i> Report</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('adminReport')}}"><i class="fa fa-list"></i> Report List</a></li>
                            <li><a href="{{route('reportsummary-display')}}"><i class="fa fa-th"></i> Report Summary</a>
                            </li>
                        </ul>
                    </li>
                @elseif(Session::get('role') == config('custom.peopleId'))
                @endif
                @endHasPermission
                @hasPermission('list-of-roles')
                <li><a href="{{ route('role')}}"> <i class="fa fa-rmb"></i> Roles</a></li>
                @endHasPermission
                @hasPermission('list-of-practices')
                <li><a href="{{ route('practices')}}"> <i class="fa fa-cube"></i> Practices</a></li>
                @endHasPermission
                @hasPermission('feedback-form')
                @if(Session::get('role') != config('custom.adminId'))
                    @if (Session::get('role') == config('custom.projectManagerId'))
                        <li><a href="{{ route('feedback.form') }}"><i class="fa fa-file-word-o"></i> Feedback Form</a>
                        </li>
                    @else
                        <li><a href="{{ route('plfeedback.form') }}"><i class="fa fa-file-word-o"></i> Feedback Form</a>
                        </li>
                    @endif
                @endif
                @endHasPermission

            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li><p class="welcome-dashboard">WELCOME</p></li>
                @if (Auth::check() && Auth::user()->is_super_admin == 1 )
                    <li>
                        <a href="{{route('profile-display')}}">{!! HTML::image('assets/user.png', Auth::user()->name,['class'=>'avatar']) !!} {{ Auth::user()->name }}</a>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('profile-display')}}"><i
                                            class="fa fa-user"></i> Profile</a></li>
                            <li><a href="{{route('organization-chart')}}"><i
                                            class="fa fa-user"></i> Organization Tree</a></li>
                            <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                        </ul>
                @endif
                @if (Auth::check() && Auth::user()->is_super_admin != 1 )
                    @if($getRoleDetails)
                        <li>
                            <a href="{{route('profile-display')}}">{!! HTML::image('assets/user.png', Auth::user()->name,['class'=>'avatar']) !!} {{ Auth::user()->name }}</a>
                            <ul class="dropdown-menu">
                                @foreach($getRoleDetails as $role)
                                    <li>
                                        <a href="{{ action('HomeController@getDashboard', array('role'=>$role['id'])) }}"><i
                                                    class="fa fa-user"></i> {{$role['name']}}</a></li>
                                @endforeach
                                <li><a href="{{route('profile-display')}}"><i
                                                class="fa fa-user"></i> Profile</a></li>
                                @if(Session::get('role') == config('custom.practiceLeadId'))
                                    <li><a href="{{route('organization-chart')}}"><i
                                                    class="fa fa-user"></i> Organization Tree</a></li>
                                @endif
                                <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                            </ul>
                    @else
                        <li>
                            <a href="{{route('profile-display')}}">{!! HTML::image('assets/user.png', Auth::user()->name,['class'=>'avatar']) !!} {{ Auth::user()->name }}</a>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('profile-display')}}"><i
                                                class="fa fa-user"></i> Profile</a></li>
                                @if(Session::get('role') == config('custom.practiceLeadId')||Session::get('role') == config('custom.DeliveryHead'))
                                    <li><a href="{{route('organization-chart')}}"><i
                                                    class="fa fa-user"></i> Organization Tree</a></li>
                                @endif
                                <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                            </li>
                            </ul>
                        </li>
                    @endif

                @endif
            </ul>
        </div>
    </div>
</div>
