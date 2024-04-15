<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>{{(isset($page_title) ? $page_title.' | ' : '').setting('site_name')}}</title>
        <meta property="route" content="{{url('')}}">
        <meta property="token" content="{{ csrf_token() }}">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <meta name="keywords" content="{{(isset($page_keywords) ? $page_keywords : setting('site_description'))}}">
        <meta name="description" content="{{(isset($page_summary) ? $page_summary : setting('site_description'))}}">
        <!-- Favicons -->
        <link href="{{ setting('site_favicon') }}" rel="icon">
        <link href="{{ setting('site_favicon') }}" rel="apple-touch-icon">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700|Raleway:300,400,400i,500,500i,700,800,900" rel="stylesheet">
        <!-- Fontawesome CSS File -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
        <!-- Bootstrap CSS File -->
        <link href="{{ url('f-assets').viewPrfx(true) }}bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Main Stylesheet File -->
        <link href="{{ url('f-assets').viewPrfx(true) }}css/owl-carousel/owl.carousel.min.css" rel="stylesheet">
        <link href="{{ url('f-assets').viewPrfx(true) }}css/owl-carousel/owl.theme.default.min.css" rel="stylesheet">
        <link href="{{ url('g-assets') }}/css/dropify.min.css" rel="stylesheet">
        <link href="{{ url('f-assets').viewPrfx(true) }}css/scrollbar.css" rel="stylesheet">
        <link href="{{ url('f-assets').viewPrfx(true) }}css/variables.css?ver={{curRand()}}" rel="stylesheet">
        <link href="{{ url('f-assets').viewPrfx(true) }}css/style.css" rel="stylesheet">

        {!! setting('front_header_scripts') !!}
    </head>
    <body data-spy="scroll" data-target="#navbar-example">
        <!-- https://bootstrap-menu.com/ -->
        <!-- Menu Section Starts -->
        <span class="screen-darken"></span>
        <div class="container-fluid d-lg-none sticky-top">
            <div class="row">
                <div class="col-md-12 mobile-menu">
                    <a class="" href="{{route('home')}}">
                    <img src="{{ setting('site_logo') }}" class="logo-mobile" />
                    </a>
                    <a data-trigger="navbar_main" class="mobile-menu-trigger"><i class="fa-solid fa-bars"></i></a>
                </div>
            </div>
        </div>
        <nav id="navbar_main" class="mobile-offcanvas navbar main-navbar navbar-expand-lg sticky-top">
            <div class="container-fluid">
                <div class="offcanvas-header">  
                    <button class="btn-close float-end"></button>
                </div>
                <a class="" href="{{route('home')}}">
                    <img src="{{ setting('site_logo') }}" class="logo-main-menu" />
                </a>
                <ul class="navbar-nav ms-auto">
                    @foreach(mainFrontMenu() as $item)
                        @if($item['childs'])
                            <li class="dropdown front-menu-dd">
                                <a class="nav-link  dropdown-toggle" href="#" data-bs-toggle="dropdown">{{__($item['title'])}}</a>
                                <ul class="dropdown-menu">
                                    @foreach($item['childs'] as $child)
                                    <!-- Login & Register Buttons Not Allowed in Drop Down -->
                                    @if ($child['type'] != 'register_button' && $child['type'] != 'login_button')
                                    <li>
                                        <a class="dropdown-item {{homeLink($child['type'])}}" href="{{$child['link']}}">{{__($child['title'])}}</a>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            @if ($item['type'] == 'login_button' && (candidateSession() || employerSession()))
                                <li class="dropdown front-menu-dd">
                                    @php $img = candidateOrEmployerThumb(); @endphp
                                    <a class="nav-link dropdown-toggle menu-image-dd-link" href="#" data-bs-toggle="dropdown">
                                        <img class="menu-avatar" src="{{$img['image']}}" onerror="this.src='{{$img['error']}}'" alt="Employer ">
                                    </a>
                                    <ul class="dropdown-menu menu-image-dd">
                                        @if (candidateSession())
                                        <li><a class="dropdown-item" href="{{route('front-profile')}}">{{__('message.profile')}}</a></li>
                                        <li><a class="dropdown-item" href="{{route('front-password')}}">{{__('message.password')}}</a></li>
                                        <li><a class="dropdown-item" href="{{route('front-logout')}}">{{__('message.logout')}}</a></li>
                                        @else
                                        <li><a class="dropdown-item" href="{{route('employer-dashboard')}}">{{__('message.dashboard')}}</a></li>
                                        <li><a class="dropdown-item" href="{{route('employer-logout')}}">{{__('message.logout')}}</a></li>
                                        @endif
                                    </ul>
                                </li>
                            @elseif ($item['type'] == 'register_button' && (candidateSession() || employerSession()))
                                <!-- Register Buttons Not Allowed in Logged in Mode -->
                            @else
                            <li class="nav-item">
                                <a class="nav-link {{homeLink($item['type'])}}" href="{{$item['link']}}">{{__($item['title'])}}</a>
                            </li>
                            @endif
                        @endif
                    @endforeach                    
                </ul>
            </div>
            <!-- container-fluid.// -->
        </nav>
        <!-- Menu Section Ends -->

        @yield('content')

        <!-- Footer Starts -->
        <footer>
            <div class="footer-area">
                <div class="container">
                    <div class="row">
                        @php $width = footerColWidth() @endphp
                        <div class="col-md-{{$width}} col-sm-{{$width}} col-xs-12">
                            <div class="footer-content">
                                {!! setting('footer_column_1') !!}
                            </div>
                        </div>
                        <div class="col-md-{{$width}} col-sm-{{$width}} col-xs-12">
                            <div class="footer-content">
                                {!! setting('footer_column_2') !!}
                            </div>
                        </div>
                        <div class="col-md-{{$width}} col-sm-{{$width}} col-xs-12">
                            <div class="footer-content">
                                {!! setting('footer_column_3') !!}
                            </div>
                        </div>
                        <div class="col-md-{{$width}} col-sm-{{$width}} col-xs-12">
                            <div class="footer-content">
                                {!! setting('footer_column_4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer Ends -->

        <!-- Login Modal -->
        <div class="modal fade in login-modal" id="modal-front" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body-container">
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Modal -->
        <div class="modal fade in modal-refer-job" id="modal-refer" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header resume-modal-header">
                        <h4 class="modal-title resume-modal-title">{{__('message.refer_job')}}</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body-container">
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>

        <a href="#" class="back-to-top" style="display: inline;"><i class="fa fa-chevron-up"></i></a>
        <!-- Bootstrap JS File -->
        <script src="{{ url('f-assets').viewPrfx(true) }}bootstrap/js/bootstrap.min.js"></script>
        <!-- Fontawesome JS File -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
        <!-- JS Language Variables file -->
        <script src="{{url('g-assets')}}/js/lang.js"></script>
        <!-- Main File -->
        <script src="{{ url('g-assets') }}/js/jquery-3.6.0.min.js"></script>
        <script src="{{ url('f-assets').viewPrfx(true) }}js/owl.carousel.min.js"></script>
        <script src="{{ url('g-assets') }}/js/bar-rating.min.js"></script>
        <script src="{{ url('g-assets') }}/js/dropify.min.js"></script>
        <script src="{{ url('f-assets').viewPrfx(true) }}js/menu.js"></script>
        <script src="{{ url('f-assets').viewPrfx(true) }}js/app.js"></script>
        <script src="{{ url('f-assets').viewPrfx(true) }}js/front.js"></script>
        @if(candidateSession())
        <script src="{{ url('f-assets').viewPrfx(true) }}js/account.js"></script>
        @endif

        {!! setting('front_footer_scripts') !!}
    </body>
</html>