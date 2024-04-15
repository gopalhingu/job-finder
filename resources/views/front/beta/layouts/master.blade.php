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

        <!-- Bootstrap CSS File -->
        <link href="{{ url('f-assets/beta') }}/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Fontawesome CSS File -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
        <!-- Flag Icons -->
        <link href="{{ url('f-assets/beta') }}/plugins/flag-icons/flag-icon.min.css" rel="stylesheet">
        <!-- Select2 -->
        <link href="{{ url('f-assets/beta') }}/plugins/select2/css/select2.min.css" rel="stylesheet">
        <!-- Owl Carousel -->
        <link href="{{ url('f-assets/beta') }}/plugins/owl-carousel/owl.carousel.min.css" rel="stylesheet">
        <link href="{{ url('f-assets/beta') }}/plugins/owl-carousel/owl.theme.default.css" rel="stylesheet">
        <link href="{{ url('f-assets/beta') }}/plugins/owl-carousel/owl.theme.green.min.css" rel="stylesheet">
        <!-- Dropify -->
        <link href="{{ url('f-assets/beta') }}/plugins/dropify/css/dropify.min.css" rel="stylesheet">

        <!-- System CSS -->
        <link href="{{ url('f-assets/beta') }}/css/variables.css" rel="stylesheet">
        <link href="{{ url('f-assets/beta') }}/css/ct-{{defaultColorTheme()}}.css" rel="stylesheet">
        <link href="{{ url('f-assets/beta') }}/css/animations.css" rel="stylesheet">
        <link href="{{ url('f-assets/beta') }}/css/patterns.css" rel="stylesheet">
        <link href="{{ url('f-assets/beta') }}/css/style.css" rel="stylesheet">
        @php $lang = frontLanguage(); @endphp
        @if($lang['direction'] == 'rtl')
        <link href="{{ url('f-assets/beta') }}/css/style-rtl.css" rel="stylesheet">
        @endif

        <!-- Custom CSS (Admin) -->
        <link href="{{ url('f-assets/beta') }}/css/custom-css.css" rel="stylesheet">        
    </head>
    <body>

        @include('front.beta.layouts.menu')

        @yield('breadcrumb')
        
        @yield('content')

        @include('front.beta.layouts.footer')

    </body>

    <!-- Bootstrap CSS File -->
    <script src="{{ url('f-assets/beta') }}/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- jQuery -->
    <script src="{{ url('f-assets/beta') }}/js/jquery-3.6.1.min.js"></script>
    <!-- cookie -->
    <script src="{{ url('f-assets/beta') }}/js/js.cookie.min.js"></script>
    <!-- Select2 -->
    <script src="{{ url('f-assets/beta') }}/plugins/select2/js/select2.min.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ url('f-assets/beta') }}/plugins/owl-carousel/owl.carousel.min.js"></script>
    <!-- Dropify -->
    <script src="{{ url('f-assets/beta') }}/plugins/dropify/js/dropify.min.js"></script>
    <!-- JS Language Variables file -->
    <script src="{{url('g-assets')}}/js/lang.js"></script>    
    <script src="{{ url('f-assets/beta') }}/js/app.js"></script>
    <script src="{{ url('f-assets/beta') }}/js/helpers.js"></script>
    <script src="{{ url('f-assets/beta') }}/js/main.js"></script>
    <script src="{{ url('f-assets/beta') }}/js/account.js"></script>
    <script src="{{ url('f-assets/beta') }}/js/menu.js"></script>

    @yield('page-scripts')

</html>