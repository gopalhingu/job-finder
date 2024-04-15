<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta property="route" content="{{url('')}}">
        <meta property="token" content="{{ csrf_token() }}">
        <title>{{ setting('site_name') }}{{ isset($page) ? ' | '.$page : ''}}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/bootstrap.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/AdminLTE.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{url('e-assets')}}/plugins/iCheck/square/blue.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition login-page">
        @yield('content')
        <!-- jQuery -->
        <script src="{{url('e-assets')}}/js/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="{{url('e-assets')}}/js/bootstrap.min.js"></script>
        <!-- iCheck -->
        <script src="{{url('e-assets')}}/plugins/iCheck/iCheck.js"></script>
        <!-- dropify -->
        <script src="{{url('e-assets')}}/js/dropify.min.js"></script>
        <!-- Site -->
        <script src="{{url('e-assets')}}/js/cf/app.js"></script>
        <script src="{{url('e-assets')}}/js/cf/general.js"></script>
    </body>
</html>