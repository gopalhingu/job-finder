<!doctype html>
<html>
<head>
    <title>Installation | Candidate Finder SAAS</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta property="route" content="{{ base_url(true) }}">
    <meta property="token" content="{{ csrf_token() }}">
    <link href="{{ base_url(true) }}/g-assets/essentials/css/fonts.css" rel="stylesheet" type="text/css">
    <link href="{{ base_url(true) }}/g-assets/essentials/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="{{ base_url(true) }}/g-assets/essentials/css/stylesheet.css" rel="stylesheet" type="text/css">
    <link href="{{ base_url(true) }}/g-assets/essentials/css/responsive.css" rel="stylesheet" type="text/css">
</head>
<body class="">
    <div class="wrapper">
        @yield('content')
    </div>
</body>
<footer>
    <script src="{{ base_url(true) }}/g-assets/essentials/js/jquery.min.js"></script>
    <script src="{{ base_url(true) }}/g-assets/essentials/js/app.js"></script>
    @yield('page-scripts')
</footer>
</html>