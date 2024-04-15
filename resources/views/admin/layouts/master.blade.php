<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta property="route" content="{{url('')}}">
        <meta property="token" content="{{ csrf_token() }}">
        <title>{{setting('site_name')}} | @yield('page-title')</title>
        <!-- Favicons -->
        <link href="{{ setting('site_favicon') }}" rel="icon">
        <link href="{{ setting('site_favicon') }}" rel="apple-touch-icon">
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/fontawesome-free/css/all.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <!-- dropify for images -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/dropify/dropify.min.css">
        <!-- Bootstrap4 Duallistbox -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
        <!-- Bootstrap Color Picker -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/iCheck/all.css">
        <!-- CSS Beautify -->
        <link rel="stylesheet" href="{{url('a-assets')}}/plugins/css-beautify/css-beautify.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{url('a-assets')}}/css/general.css">
        <link rel="stylesheet" href="{{url('a-assets')}}/css/adminlte.min.css">
    </head>
    <body class="hold-transition sidebar-mini">
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link header-bar-btn" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                    </li>
                </ul>
                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                {!! adminNotificationsWidget() !!}
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
            </nav>
            <!-- /.navbar -->

            @include('admin.layouts.sidebar')

            @yield('content')

            @include('admin.partials.modals')

            <!-- Main Footer -->
            <footer class="main-footer">
                <strong></strong>
            </footer>
        </div>
        <!-- ./wrapper -->
        <!-- REQUIRED SCRIPTS -->
        <!-- jQuery -->
        <script src="{{url('a-assets')}}/js/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="{{url('a-assets')}}/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="{{url('a-assets')}}/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="{{url('a-assets')}}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="{{url('a-assets')}}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="{{url('a-assets')}}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="{{url('a-assets')}}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="{{url('a-assets')}}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <!-- AdminLTE -->
        <script src="{{url('a-assets')}}/js/adminlte.min.js"></script>
        <!-- OPTIONAL SCRIPTS -->
        <script src="{{url('a-assets')}}/plugins/chart.js/Chart.min.js"></script>
        <!-- Select2 -->
        <script src="{{url('a-assets')}}/plugins/select2/js/select2.full.min.js"></script>
        <!-- Select2 -->
        <script src="{{url('a-assets')}}/plugins/dropify/dropify.min.js"></script>
        <!-- Bootstrap4 Duallistbox -->
        <script src="{{url('a-assets')}}/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
        <!-- bootstrap color picker -->
        <script src="{{url('a-assets')}}/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <!-- Nestable -->
        <script src="{{url('a-assets')}}/js/jquery.nestable.js"></script>
        <!-- iCheck -->
        <script src="{{url('a-assets')}}/plugins/iCheck/iCheck.min.js"></script>
        <!-- CSS Beautify -->
        <script src="{{url('a-assets')}}/plugins/css-beautify/cssbeautify.codemirror.js"></script>
        <script src="{{url('a-assets')}}/plugins/css-beautify/cssbeautify.css.js"></script>
        <script src="{{url('a-assets')}}/plugins/css-beautify/cssbeautify.js"></script>        
        <!-- CKEditor -->
        <script src="{{url('a-assets')}}/plugins/ckeditor5/upload-adapter.js"></script>
        <script src="{{url('a-assets')}}/plugins/ckeditor5/ckeditor.js"></script>
        <!-- Lang -->
        <script src="{{url('g-assets')}}/js/lang.js"></script>
        <!-- CF App -->
        <script src="{{url('a-assets')}}/js/cf/app.js"></script>
        <script src="{{url('a-assets')}}/js/cf/general.js"></script>
        @yield('page-scripts')
    </body>
</html>