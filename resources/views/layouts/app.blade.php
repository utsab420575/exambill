<!doctype html>
<html class="fixed sidebar-light">
<head>
    <!-- Basic -->
    <meta charset="UTF-8">
    <title>Light Sidebar Layout | Porto Admin - Responsive HTML5 Template</title>
    <meta name="keywords" content="HTML5 Admin Template"/>
    <meta name="description" content="Porto Admin - Responsive HTML5 Template">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="okler.net">
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <!-- Web Fonts  -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800|Shadows+Into+Light"
          rel="stylesheet" type="text/css">
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/vendor/bootstrap/css/bootstrap.css')}}"/>
    <link rel="stylesheet" href="{{asset('dashboard/vendor/animate/animate.compat.css')}}">
    <link rel="stylesheet" href="{{asset('dashboard/vendor/font-awesome/css/all.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('dashboard/vendor/boxicons/css/boxicons.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('dashboard/vendor/magnific-popup/magnific-popup.css')}}"/>
    <link rel="stylesheet" href="{{asset('dashboard/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css')}}"/>
    <link rel="stylesheet" href="{{asset('dashboard/vendor/jquery-ui/jquery-ui.css')}}"/>
    <link rel="stylesheet" href="{{asset('dashboard/vendor/jquery-ui/jquery-ui.theme.css')}}"/>
    <link rel="stylesheet" href="{{asset('dashboard/vendor/bootstrap-multiselect/css/bootstrap-multiselect.css')}}"/>
    <link rel="stylesheet" href="{{asset('dashboard/vendor/morris/morris.css')}}"/>
    <link rel="stylesheet" href="{{asset('dashboard/vendor/elusive-icons/css/elusive-icons.css')}}" />
    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/css/theme.css')}}"/>
    <!-- Skin CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/css/skins/default.css')}}"/>
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{asset('dashboard/css/custom.css')}}">
    <!-- Head Libs -->
    <script src="{{asset('dashboard/vendor/modernizr/modernizr.js')}}"></script>

    @stack('styles')
    @yield('styles')


</head>
<body>
<section class="body">
    <!-- start: header -->
    <header class="header">
        <div class="logo-container">
            <a href="../4.3.0" class="logo">
                <img src="{{asset('dashboard/img/logo.png')}}" width="75" height="35" alt="Porto Admin"/>
            </a>
            <div class="d-md-none toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html"
                 data-fire-event="sidebar-left-opened">
                <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
            </div>
        </div>
        <!-- start: search & user box -->
        {{-- <div class="header-right">
             <form action="pages-search-results.html" class="search nav-form">
                 <div class="input-group">
                     <input type="text" class="form-control" name="q" id="q" placeholder="Search...">
                     <button class="btn btn-default" type="submit"><i class="bx bx-search"></i></button>
                 </div>
             </form>
             <span class="separator"></span>
             <ul class="notifications">
                 <li>
                     <a href="#" class="dropdown-toggle notification-icon" data-bs-toggle="dropdown">
                         <i class="bx bx-list-ol"></i>
                         <span class="badge">3</span>
                     </a>
                     <div class="dropdown-menu notification-menu large">
                         <div class="notification-title">
                             <span class="float-end badge badge-default">3</span>
                             Tasks
                         </div>
                         <div class="content">
                             <ul>
                                 <li>
                                     <p class="clearfix mb-1">
                                         <span class="message float-start">Generating Sales Report</span>
                                         <span class="message float-end text-dark">60%</span>
                                     </p>
                                     <div class="progress progress-xs light">
                                         <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;"></div>
                                     </div>
                                 </li>
                                 <li>
                                     <p class="clearfix mb-1">
                                         <span class="message float-start">Importing Contacts</span>
                                         <span class="message float-end text-dark">98%</span>
                                     </p>
                                     <div class="progress progress-xs light">
                                         <div class="progress-bar" role="progressbar" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100" style="width: 98%;"></div>
                                     </div>
                                 </li>
                                 <li>
                                     <p class="clearfix mb-1">
                                         <span class="message float-start">Uploading something big</span>
                                         <span class="message float-end text-dark">33%</span>
                                     </p>
                                     <div class="progress progress-xs light mb-1">
                                         <div class="progress-bar" role="progressbar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width: 33%;"></div>
                                     </div>
                                 </li>
                             </ul>
                         </div>
                     </div>
                 </li>
                 <li>
                     <a href="#" class="dropdown-toggle notification-icon" data-bs-toggle="dropdown">
                         <i class="bx bx-envelope"></i>
                         <span class="badge">4</span>
                     </a>
                     <div class="dropdown-menu notification-menu">
                         <div class="notification-title">
                             <span class="float-end badge badge-default">230</span>
                             Messages
                         </div>
                         <div class="content">
                             <ul>
                                 <li>
                                     <a href="#" class="clearfix">
                                         <figure class="image">
                                             <img src="{{asset('dashboard/img/!sample-user.jpg')}}" alt="Joseph Doe Junior" class="rounded-circle" />
                                         </figure>
                                         <span class="title">Joseph Doe</span>
                                         <span class="message">Lorem ipsum dolor sit.</span>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#" class="clearfix">
                                         <figure class="image">
                                             <img src="{{asset('dashboard/img/!sample-user.jpg')}}" alt="Joseph Junior" class="rounded-circle" />
                                         </figure>
                                         <span class="title">Joseph Junior</span>
                                         <span class="message truncate">Truncated message. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet lacinia orci. Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla molestie malesuada est ac tincidunt. Quisque eget convallis diam, nec venenatis risus. Vestibulum blandit faucibus est et malesuada. Sed interdum cursus dui nec venenatis. Pellentesque non nisi lobortis, rutrum eros ut, convallis nisi. Sed tellus turpis, dignissim sit amet tristique quis, pretium id est. Sed aliquam diam diam, sit amet faucibus tellus ultricies eu. Aliquam lacinia nibh a metus bibendum, eu commodo eros commodo. Sed commodo molestie elit, a molestie lacus porttitor id. Donec facilisis varius sapien, ac fringilla velit porttitor et. Nam tincidunt gravida dui, sed pharetra odio pharetra nec. Duis consectetur venenatis pharetra. Vestibulum egestas nisi quis elementum elementum.</span>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#" class="clearfix">
                                         <figure class="image">
                                             <img src="{{asset('dashboard/img/!sample-user.jpg')}}" alt="Joe Junior" class="rounded-circle" />
                                         </figure>
                                         <span class="title">Joe Junior</span>
                                         <span class="message">Lorem ipsum dolor sit.</span>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#" class="clearfix">
                                         <figure class="image">
                                             <img src="{{asset('dashboard/img/!sample-user.jpg')}}" alt="Joseph Junior" class="rounded-circle" />
                                         </figure>
                                         <span class="title">Joseph Junior</span>
                                         <span class="message">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sit amet lacinia orci. Proin vestibulum eget risus non luctus. Nunc cursus lacinia lacinia. Nulla molestie malesuada est ac tincidunt. Quisque eget convallis diam.</span>
                                     </a>
                                 </li>
                             </ul>
                             <hr />
                             <div class="text-end">
                                 <a href="#" class="view-more">View All</a>
                             </div>
                         </div>
                     </div>
                 </li>
                 <li>
                     <a href="#" class="dropdown-toggle notification-icon" data-bs-toggle="dropdown">
                         <i class="bx bx-bell"></i>
                         <span class="badge">3</span>
                     </a>
                     <div class="dropdown-menu notification-menu">
                         <div class="notification-title">
                             <span class="float-end badge badge-default">3</span>
                             Alerts
                         </div>
                         <div class="content">
                             <ul>
                                 <li>
                                     <a href="#" class="clearfix">
                                         <div class="image">
                                             <i class="fas fa-thumbs-down bg-danger text-light"></i>
                                         </div>
                                         <span class="title">Server is Down!</span>
                                         <span class="message">Just now</span>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#" class="clearfix">
                                         <div class="image">
                                             <i class="bx bx-lock bg-warning text-light"></i>
                                         </div>
                                         <span class="title">User Locked</span>
                                         <span class="message">15 minutes ago</span>
                                     </a>
                                 </li>
                                 <li>
                                     <a href="#" class="clearfix">
                                         <div class="image">
                                             <i class="fas fa-signal bg-success text-light"></i>
                                         </div>
                                         <span class="title">Connection Restaured</span>
                                         <span class="message">10/10/2023</span>
                                     </a>
                                 </li>
                             </ul>
                             <hr />
                             <div class="text-end">
                                 <a href="#" class="view-more">View All</a>
                             </div>
                         </div>
                     </div>
                 </li>
             </ul>
             <span class="separator"></span>
             <div id="userbox" class="userbox">
                 <a href="#" data-bs-toggle="dropdown">
                     <figure class="profile-picture">
                         <img src="{{asset('dashboard/img/!logged-user.jpg')}}" alt="Joseph Doe" class="rounded-circle" data-lock-picture="{{asset('dashboard/img/!logged-user.jpg')}}" />
                     </figure>
                     <div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
                         <span class="name">John Doe Junior</span>
                         <span class="role">Administrator</span>
                     </div>
                     <i class="fa custom-caret"></i>
                 </a>
                 <div class="dropdown-menu">
                     <ul class="list-unstyled mb-2">
                         <li class="divider"></li>
                         <li>
                             <a role="menuitem" tabindex="-1" href="pages-user-profile.html"><i class="bx bx-user-circle"></i> My Profile</a>
                         </li>
                         <li>
                             <a role="menuitem" tabindex="-1" href="#" data-lock-screen="true"><i class="bx bx-lock"></i> Lock Screen</a>
                         </li>
                         <li>
                             <a role="menuitem" tabindex="-1" href="pages-signin.html"><i class="bx bx-power-off"></i> Logout</a>
                         </li>
                     </ul>
                 </div>
             </div>
         </div>--}}
        <!-- end: search & user box -->
    </header>
    <!-- end: header -->
    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <aside id="sidebar-left" class="sidebar-left">
            <div class="sidebar-header">
                <div class="sidebar-title">
                    Navigation
                </div>
                <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed"
                     data-target="html" data-fire-event="sidebar-left-toggle">
                    <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>
            <div class="nano">
                <div class="nano-content">
                    <nav id="menu" class="nav-main" role="navigation">
                        <ul class="nav nav-main">
                            <li>
                                <a class="nav-link" href="{{route('dashboard')}}">
                                    <i class="bx bx-home-alt" aria-hidden="true"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            {{-- All Sessions--}}
                            {{-- <li class="nav-parent {{ request()->routeIs('all_form') ? 'nav-expanded nav-active' : '' }}">--}}
                            <li class="nav-parent navbar-expand {{
                                    (request()->is('sessions/regular') ||
                                     request()->is('sessions/regular/*') ||
                                     request()->is('sessions/review') ||
                                     request()->is('sessions/review/*'))
                                    ? 'nav-expanded nav-active' : ''
                                }}">
                                <a class="nav-link {{
                                            (request()->is('sessions/regular') ||
                                             request()->is('sessions/regular/*') ||
                                             request()->is('sessions/review') ||
                                             request()->is('sessions/review/*'))
                                                ? 'active' : ''
                                        }}" href="#">
                                    <i class="el el-time" aria-hidden="true"></i>
                                    <span>Sessions</span>
                                </a>
                                <ul class="nav nav-children {{
                                            (request()->is('sessions/regular') ||
                                             request()->is('sessions/regular/*') ||
                                             request()->is('sessions/review') ||
                                             request()->is('sessions/review/*'))
                                            ? 'display' : ''
                                        }}">
                                    <li>
                                        <a class="nav-link {{ (request()->is('sessions/regular') || request()->is('sessions/regular/*')) ? 'active-child' : '' }}"
                                           href="{{ route('sessions.regular') }}">
                                            All Regular Sessions
                                        </a>
                                    </li>
                                    <li>
                                        <a class="nav-link {{ (request()->is('sessions/review') || request()->is('sessions/review/*')) ? 'active-child' : '' }}"
                                           href="{{ route('sessions.review') }}">
                                            All Review Sessions
                                        </a>
                                    </li>
                                </ul>
                            </li>


                            {{--Reports--}}
                             <li class="nav-parent navbar-expand {{
                                    (request()->is('report/sessions/regular') ||
                                     request()->is('report/sessions/regular/*') ||
                                     request()->is('report/sessions/review') ||
                                     request()->is('report/sessions/review/*'))
                                    ? 'nav-expanded nav-active' : ''
                                }}">
                                 <a class="nav-link {{
                                            (request()->is('report/sessions/regular') ||
                                             request()->is('report/sessions/regular/*') ||
                                             request()->is('report/sessions/review') ||
                                             request()->is('report/sessions/review/*'))
                                                ? 'active' : ''
                                        }}" href="#">
                                     <i class="bx bxs-report" aria-hidden="true"></i>
                                     <span>Reports</span>
                                 </a>
                                 <ul class="nav nav-children {{
                                            (request()->is('report/sessions/regular') ||
                                             request()->is('report/sessions/regular/*') ||
                                             request()->is('report/sessions/review') ||
                                             request()->is('report/sessions/review/*'))
                                            ? 'display' : ''
                                        }}">
                                     <li>
                                         <a class="nav-link {{ (request()->is('report/sessions/regular') || request()->is('report/sessions/regular/*')) ? 'active-child' : '' }}"
                                            href="{{ route('report.sessions.regular') }}">
                                                Regular Session Report
                                         </a>
                                     </li>
                                     <li>
                                         <a class="nav-link {{ (request()->is('report/sessions/review') || request()->is('report/sessions/review/*')) ? 'active-child' : '' }}"
                                            href="{{ route('report.sessions.review') }}">
                                             Review Session Report
                                         </a>
                                     </li>
                                 </ul>
                             </li>
                        </ul>
                    </nav>
                </div>
                <script>
                    // Maintain Scroll Position
                    if (typeof localStorage !== 'undefined') {
                        if (localStorage.getItem('sidebar-left-position') !== null) {
                            var initialPosition = localStorage.getItem('sidebar-left-position'),
                                sidebarLeft = document.querySelector('#sidebar-left .nano-content');
                            sidebarLeft.scrollTop = initialPosition;
                        }
                    }
                </script>
            </div>
        </aside>
        <!-- end: sidebar -->
        @yield('content')
    </div>
    <aside id="sidebar-right" class="sidebar-right">
        <div class="nano">
            <div class="nano-content">
                <a href="#" class="mobile-close d-md-none">
                    Collapse <i class="fas fa-chevron-right"></i>
                </a>
                <div class="sidebar-right-wrapper">
                    <div class="sidebar-widget widget-calendar">
                        <h6>Upcoming Tasks</h6>
                        <div data-plugin-datepicker data-plugin-skin="dark"></div>
                        <ul>
                            <li>
                                <time datetime="2023-04-19T00:00+00:00">04/19/2023</time>
                                <span>Company Meeting</span>
                            </li>
                        </ul>
                    </div>
                    <div class="sidebar-widget widget-friends">
                        <h6>Friends</h6>
                        <ul>
                            <li class="status-online">
                                <figure class="profile-picture">
                                    <img src="{{asset('dashboard/img/!sample-user.jpg')}}" alt="Joseph Doe"
                                         class="rounded-circle">
                                </figure>
                                <div class="profile-info">
                                    <span class="name">Joseph Doe Junior</span>
                                    <span class="title">Hey, how are you?</span>
                                </div>
                            </li>
                            <li class="status-online">
                                <figure class="profile-picture">
                                    <img src="{{asset('dashboard/img/!sample-user.jpg')}}" alt="Joseph Doe"
                                         class="rounded-circle">
                                </figure>
                                <div class="profile-info">
                                    <span class="name">Joseph Doe Junior</span>
                                    <span class="title">Hey, how are you?</span>
                                </div>
                            </li>
                            <li class="status-offline">
                                <figure class="profile-picture">
                                    <img src="{{asset('dashboard/img/!sample-user.jpg')}}" alt="Joseph Doe"
                                         class="rounded-circle">
                                </figure>
                                <div class="profile-info">
                                    <span class="name">Joseph Doe Junior</span>
                                    <span class="title">Hey, how are you?</span>
                                </div>
                            </li>
                            <li class="status-offline">
                                <figure class="profile-picture">
                                    <img src="{{asset('dashboard/img/!sample-user.jpg')}}" alt="Joseph Doe"
                                         class="rounded-circle">
                                </figure>
                                <div class="profile-info">
                                    <span class="name">Joseph Doe Junior</span>
                                    <span class="title">Hey, how are you?</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </aside>
</section>
<!-- Vendor -->
<script src="{{asset('dashboard/vendor/jquery/jquery.js')}}"></script>
<script src="{{asset('dashboard/vendor/jquery-browser-mobile/jquery.browser.mobile.js')}}"></script>
<script src="{{asset('dashboard/vendor/popper/umd/popper.min.js')}}"></script>
<script src="{{asset('dashboard/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('dashboard/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
<script src="{{asset('dashboard/vendor/common/common.js')}}"></script>
<script src="{{asset('dashboard/vendor/nanoscroller/nanoscroller.js')}}"></script>
<script src="{{asset('dashboard/vendor/magnific-popup/jquery.magnific-popup.js')}}"></script>
<script src="{{asset('dashboard/vendor/jquery-placeholder/jquery.placeholder.js')}}"></script>
<!-- Specific Page Vendor -->
<script src="{{asset('dashboard/vendor/jquery-ui/jquery-ui.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqueryui-touch-punch/jquery.ui.touch-punch.js')}}"></script>
<script src="{{asset('dashboard/vendor/jquery-appear/jquery.appear.js')}}"></script>
<script src="{{asset('dashboard/vendor/bootstrap-multiselect/js/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('dashboard/vendor/jquery.easy-pie-chart/jquery.easypiechart.js')}}"></script>
<script src="{{asset('dashboard/vendor/flot/jquery.flot.js')}}"></script>
<script src="{{asset('dashboard/vendor/flot.tooltip/jquery.flot.tooltip.js')}}"></script>
<script src="{{asset('dashboard/vendor/flot/jquery.flot.pie.js')}}"></script>
<script src="{{asset('dashboard/vendor/flot/jquery.flot.categories.js')}}"></script>
<script src="{{asset('dashboard/vendor/flot/jquery.flot.resize.js')}}"></script>
<script src="{{asset('dashboard/vendor/jquery-sparkline/jquery.sparkline.js')}}"></script>
<script src="{{asset('dashboard/vendor/raphael/raphael.js')}}"></script>
<script src="{{asset('dashboard/vendor/morris/morris.js')}}"></script>
<script src="{{asset('dashboard/vendor/gauge/gauge.js')}}"></script>
<script src="{{asset('dashboard/vendor/snap.svg/snap.svg.js')}}"></script>
<script src="{{asset('dashboard/vendor/liquid-meter/liquid.meter.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqvmap/jquery.vmap.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqvmap/data/jquery.vmap.sampledata.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqvmap/maps/jquery.vmap.world.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqvmap/maps/continents/jquery.vmap.africa.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqvmap/maps/continents/jquery.vmap.asia.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqvmap/maps/continents/jquery.vmap.australia.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqvmap/maps/continents/jquery.vmap.europe.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js')}}"></script>
<script src="{{asset('dashboard/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js')}}"></script>
<!-- Theme Base, Components and Settings -->
<script src="{{asset('dashboard/js/theme.js')}}"></script>
<!-- Theme Custom -->
<script src="{{asset('dashboard/js/custom.js')}}"></script>
<!-- Theme Initialization Files -->
<script src="{{asset('dashboard/js/theme.init.js')}}"></script>
<!-- Examples -->
<script src="{{asset('dashboard/js/examples/examples.dashboard.js')}}"></script>

<!-- Sweetalert-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stack('scripts')
</body>
</html>
