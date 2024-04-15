<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('page-title')</title>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="{{ settingEmpSlug('site_keywords') }}" name="keywords">
        <meta content="{{ settingEmpSlug('site_description') }}" name="description">
        <meta property="route" content="{{ empUrl() }}">
        <meta property="token" content="{{ csrf_token() }}">
        <!-- Favicon -->
        <link href="{{ settingEmpSlug('site_favicon') }}" rel="icon">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Montserrat:300,400,500,700" rel="stylesheet">
        <!-- CSS Libraries (For External components/plugins) -->
        <link href="{{ url('/') }}/c-assets/alpha/css/jquery-ui.css" rel="stylesheet">
        <link href="{{ url('/') }}/c-assets/alpha/css/dropify.min.css" rel="stylesheet">
        <link href="{{ url('/') }}/c-assets/alpha/css/font-awesome-all.min.css" rel="stylesheet">
        <link href="{{ url('/') }}/c-assets/alpha/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ url('/') }}/c-assets/alpha/css/bootstrap-social.css" rel="stylesheet">
        <link href="{{ url('/') }}/c-assets/alpha/css/bar-rating-pill.css" rel="stylesheet">
        <link href="{{ url('/') }}/c-assets/alpha/plugins/iCheck/square/blue.css" rel="stylesheet">
        <!-- Internal Style files -->
        @if(empSlugBranding())
        <link href="{{ url('/').'/'.employerPath(true) }}/custom-style.css?ver={{curRand()}}" rel="stylesheet">
        <link href="{{ url('/').'/'.employerPath(true) }}/variables.css?ver={{curRand()}}" rel="stylesheet">
        @else
        <link href="{{ url('c-assets/alpha') }}/css/variables.css?ver={{curRand()}}" rel="stylesheet">
        @endif
        <link href="{{ url('/') }}/c-assets/alpha/css/style.css" rel="stylesheet">

        {!! setting('candidate_header_scripts') !!}
    </head>
    <body>
        <!--==========================
                Header
        ============================-->
        <header id="header" class="fixed-top">
            <div class="container">
                <div class="logo float-left">
                    <a href="{{ rtrim(empUrl(), '/') }}" class="scrollto">
                    <img src="{{ settingEmpSlug('site_logo') }}" onerror="this.src='{{setting('site_logo')}}'" alt="" class="img-fluid">
                    </a>
                </div>
                <nav class="main-nav float-right">
                    <ul>
                        <li>
                            @if (candidateSession())
                            <a class="btn btn-primary btn-sm front-account-btn" href="{{ empUrl() }}login"
                                title="{{ candidateSession('first_name') }}">
                            Hi, {{ trimString(candidateSession('first_name'), 7) }}
                            </a>
                            @else
                            <a class="btn btn-primary btn-sm front-account-btn" href="{{ empUrl() }}login">{{ __('message.account') }}</a>
                            @endif
                        </li>
                    </ul>
                </nav>
                <!-- .main-nav -->
            </div>
        </header>
        <!-- #header -->

        @yield('content')

        <!-- Top Modal -->
        <div class="modal fade in modal-refer-job" id="modal-default" data-backdrop="static" data-keyboard="false">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header resume-modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title resume-modal-title">{{__('message.refer_job')}}</h4>
                    </div>
                    <div class="modal-body-container">
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </div>
        <!--==========================
                Footer
        ============================-->
        @php $footer = footerColumns(); @endphp
        <footer id="footer">
            @if($footer['columns'])
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        @foreach ($footer['columns'] as $column)
                        <div class="col-md-{{ $footer['column_count'] }} col-sm-12">
                            {!! $column !!}
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </footer>
        <!-- #footer -->
        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
    </body>


    <!-- JavaScript Libraries (For External components/plugins) -->
    <script src="{{url('c-assets/alpha')}}/js/jquery.min.js"></script>
    <script src="{{url('c-assets/alpha')}}/js/jquery-ui.min.js"></script>
    <script src="{{url('c-assets/alpha')}}/js/bootstrap.min.js"></script>
    <script src="{{url('c-assets/alpha')}}/js/dropify.min.js"></script>
    <script src="{{url('c-assets/alpha')}}/js/bar-rating.min.js"></script>
    <script src="{{url('c-assets/alpha')}}/plugins/iCheck/iCheck.js"></script>
    <!-- JS Language Variables file -->
    <script src="{{url('g-assets')}}/js/lang.js"></script>
    <!-- Files For Functionalities -->
    <script src="{{url('c-assets/alpha')}}/js/app.js"></script>
    <script src="{{url('c-assets/alpha')}}/js/account.js"></script>
    <script src="{{url('c-assets/alpha')}}/js/general.js"></script>
    <script src="{{url('c-assets/alpha')}}/js/dot_menu.js"></script>

    {!! setting('candidate_footer_scripts') !!}
    
</html>
