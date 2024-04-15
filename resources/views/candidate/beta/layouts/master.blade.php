<!doctype html>
<html lang="en">
    <head>
        <title>@yield('page-title')</title>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta property="route" content="{{ empUrl() }}">
        <meta property="token" content="{{ csrf_token() }}">
        <meta content="{{ settingEmpSlug('site_keywords') }}" name="keywords">
        <meta content="{{ settingEmpSlug('site_description') }}" name="description">

        <!-- Favicons -->
        <link href="{{ settingEmpSlug('site_favicon') }}" rel="icon">
        <link href="{{ settingEmpSlug('site_favicon') }}" rel="apple-touch-icon">

        <!-- Fontawesome CSS File -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
        <!-- Bootstrap CSS File -->
        <link href="{{ url('c-assets/beta') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ url('c-assets/beta') }}/css/bootstrap-social.css" rel="stylesheet">
        <!-- jQuery UI -->
        <link href="{{ url('c-assets/beta') }}/css/jquery-ui.css" rel="stylesheet">
        <!-- Flag Icons -->
        <link href="{{ url('c-assets/beta') }}/plugins/flag-icons/flag-icon.min.css" rel="stylesheet">
        <!-- Select2 -->
        <link href="{{ url('c-assets/beta') }}/plugins/select2/css/select2.min.css" rel="stylesheet">
        <!-- Owl Carousel -->
        <link href="{{ url('c-assets/beta') }}/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet">
        <link href="{{ url('c-assets/beta') }}/plugins/owl-carousel/owl.theme.default.css" rel="stylesheet">
        <link href="{{ url('c-assets/beta') }}/plugins/owl-carousel/owl.theme.green.min.css" rel="stylesheet">
        <!-- Dropify -->
        <link href="{{ url('c-assets/beta') }}/plugins/dropify/css/dropify.min.css" rel="stylesheet">

        <!-- Internal Style files -->
        @if(empSlugBranding())
        <link href="{{ url('/').'/'.employerPath(true) }}/custom-style.css?ver={{curRand()}}" rel="stylesheet">
        <link href="{{ url('/').'/'.employerPath(true) }}/variables.css?ver={{curRand()}}" rel="stylesheet">
        @else
        <link href="{{ url('c-assets/beta') }}/css/variables.css?ver={{curRand()}}" rel="stylesheet">
        @endif

        <!-- Custom CSS (Admin) -->
        <link href="{{ url('c-assets/beta') }}/css/custom-css.css" rel="stylesheet">

        <!-- System CSS -->
        <link href="{{ url('c-assets/beta') }}/css/ct-{{defaultColorTheme()}}.css" rel="stylesheet">
        <link href="{{ url('c-assets/beta') }}/css/style.css" rel="stylesheet">

        @php $lang = frontLanguage(); @endphp
        @if($lang['direction'] == 'rtl')
        <link href="{{ url('C-assets/beta') }}/css/style-rtl.css" rel="stylesheet">
        @endif

        {!! setting('candidate_header_scripts') !!}

    </head>
    <body>

        @include('candidate.beta.layouts.menu')

        @yield('breadcrumb')
        
        @yield('content')

        @include('candidate.beta.layouts.footer')

    </body>

    <!-- Bootstrap CSS File -->
    <script src="{{ url('c-assets/beta') }}/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- jQuery -->
    <script src="{{ url('c-assets/beta') }}/js/jquery-3.6.1.min.js"></script>
    <!-- jQuery UI -->
    <script src="{{ url('c-assets/beta')}}/js/jquery-ui.min.js"></script>
    <!-- cookie -->
    <script src="{{ url('c-assets/beta') }}/js/js.cookie.min.js"></script>
    <!-- Select2 -->
    <script src="{{ url('c-assets/beta') }}/plugins/select2/js/select2.min.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ url('c-assets/beta') }}/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- Dropify -->
    <script src="{{ url('c-assets/beta') }}/plugins/dropify/js/dropify.min.js"></script>
    <!-- JS Language Variables file -->
    <script src="{{ url('g-assets') }}/js/lang.js"></script>    
    <script src="{{ url('c-assets/beta') }}/js/app.js"></script>
    <script src="{{ url('c-assets/beta') }}/js/helpers.js"></script>
    <script src="{{ url('c-assets/beta') }}/js/account.js"></script>
    <script src="{{ url('c-assets/beta') }}/js/general.js"></script>
    <script src="{{ url('c-assets/beta') }}/js/menu.js"></script>

    @yield('page-scripts')

</html>