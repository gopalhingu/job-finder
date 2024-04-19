<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta property="route" content="{{url('')}}">
        <meta property="token" content="{{ csrf_token() }}">
        <title>{{ settingEmp('site_name') }} | {{ $page }}</title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Favicon -->
        <link href="{{ settingEmp('site_favicon') }}" rel="icon">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/font-awesome.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/dataTables.bootstrap.min.css">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/select2.min.css"/ />
        <!-- Bootstrap -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/bootstrap.min.css">
        <!-- jQuery Multiselect -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/jquery.multi-select.css" />
        <!-- jQuery UI -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/jquery-ui.css" />
        <link rel="stylesheet" href="{{url('e-assets')}}/css/jquery-ui-timepicker-addon.css" />
        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="{{url('e-assets')}}/plugins/iCheck/all.css">
        <!-- dropify for images -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/dropify.min.css">
        <!-- css beautify -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/css-beautify.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/AdminLTE.min.css">
        <!-- AdminLTE Skins -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/skin-black-light.css">
        <!-- Pill Rating -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/bar-rating-pill.css">
        <!-- Toggle -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/toggle.min.css" >
        <!-- Candidate Finder CSS. -->
        <link rel="stylesheet" href="{{url('e-assets')}}/css/cf/dashboard-styles.css">
        <link rel="stylesheet" href="{{url('e-assets')}}/css/cf/team-page-styles.css">
        <link rel="stylesheet" href="{{url('e-assets')}}/css/cf/candidate-page-styles.css">
        <link rel="stylesheet" href="{{url('e-assets')}}/css/cf/job-listing-page-styles.css">
        <link rel="stylesheet" href="{{url('e-assets')}}/css/cf/quiz-page-styles.css">
        <link rel="stylesheet" href="{{url('e-assets')}}/css/cf/interview-page-styles.css">
        <link rel="stylesheet" href="{{url('e-assets')}}/css/cf/job-board-styles.css">
        <link rel="stylesheet" href="{{url('e-assets')}}/css/cf/general-styles.css">
        @php $lang = companyLanguage(); @endphp
        @if($lang['direction'] == 'rtl')
        <link rel="stylesheet" href="{{url('e-assets')}}/css/cf/rtl-styles.css">
        @endif
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- Google Font -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

        {!! setting('company_header_scripts') !!}

    </head>
    <body class="hold-transition skin-black-light sidebar-mini {{ getSessionValues('sidebar_toggle') == 'off' ? '' : 'sidebar-collapse'; }}">
        <div class="wrapper">
            @include('company.layouts.topbar')
            @include('company.layouts.sidebar')
            @yield('content')
            <!-- Top Modal -->
            <div class="modal fade in" id="modal-default" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Default Modal</h4>
                        </div>
                        <div class="modal-body-container">
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <footer class="main-footer">
                <strong>Copyright &copy; {{ date('Y') }}.</strong> {{__('message.all_rights_reserved_2')}}
            </footer>
        </div>
        <!-- ./wrapper -->
    </body>

    
    <!-- jQuery -->
    <script src="{{url('e-assets/')}}/js/jquery.min.js"></script>
    <!-- jQuery UI -->
    <script src="{{url('e-assets/')}}/js/jquery-ui.min.js"></script>
    <script src="{{url('e-assets/')}}/js/jquery-ui-timepicker-addon.min.js"></script>
    <!-- Bootstrap -->
    <script src="{{url('e-assets/')}}/js/popper.min.js"></script>
    <script src="{{url('e-assets/')}}/js/bootstrap.min.js"></script>
    <!-- Dashboard charts -->
    <script src="{{url('e-assets/')}}/js/raphael.min.js"></script>
    <script src="{{url('e-assets/')}}/js/morris.min.js"></script>
    <script src="{{url('e-assets/')}}/js/Chart.js"></script>
    <!-- Slimscroll -->
    <script src="{{url('e-assets/')}}/js/jquery.slimscroll.min.js"></script>
    <!-- DataTables -->
    <script src="{{url('e-assets/')}}/js/jquery.dataTables.min.js"></script>
    <script src="{{url('e-assets/')}}/js/dataTables.bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="{{url('e-assets/')}}/plugins/iCheck/iCheck.min.js"></script>
    <!-- Select2 -->
    <script src="{{url('e-assets/')}}/js/select2.min.js"></script>
    <!-- dropify -->
    <script src="{{url('e-assets/')}}/js/dropify.min.js"></script>
    <!-- jQuery Multiselect -->
    <script src="{{url('e-assets/')}}/js/jquery.multi-select.js"></script>
    <!-- CKEditor -->
    <script src="{{url('e-assets')}}/plugins/ckeditor5/upload-adapter.js"></script>
    <script src="{{url('e-assets/')}}/plugins/ckeditor5/ckeditor.js"></script>
    <!-- Pill Bar Rating -->
    <script src="{{url('e-assets/')}}/js/bar-rating.min.js"></script>
    <!-- Toggle -->
    <script src="{{url('e-assets/')}}/js/toggle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{url('e-assets/')}}/js/adminlte.min.js"></script>
    <!-- Candidate Finder Lang -->
    <script src="{{url('g-assets/')}}/js/lang.js"></script>
    <!-- Candidate Finder App -->
    <script src="{{url('e-assets/')}}/js/cf/app.js"></script>
    <script src="{{url('e-assets/')}}/js/cf/general.js"></script>
    @yield('page-scripts')
    
    {!! setting('company_footer_scripts') !!}

</html>