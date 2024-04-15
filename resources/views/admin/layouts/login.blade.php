<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{setting('site_name')}} | {{__('message.admin')}}</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/fontawesome-free/css/all.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/iCheck/all.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{url('a-assets/login')}}/css/adminlte.min.css">
    </head>
    <body class="hold-transition login-page">
    @yield('content')
    <!-- jQuery -->
    <script src="{{url('a-assets/login')}}/js/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{url('a-assets/login')}}/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{url('a-assets/login')}}/js/adminlte.min.js"></script>
    <!-- iCheck -->
    <script src="{{url('a-assets')}}/plugins/iCheck/iCheck.min.js"></script>
    @yield('page-scripts')
    </body>
</html>