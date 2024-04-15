@extends('admin.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{__('message.settings')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{__('message.settings')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.general')}}</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">{{ __('message.general') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <form id="admin_display_settings_form" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.logo')}}</label>
                                <input type="file" class="form-control dropify" name="site_logo" 
                                data-default-file="{{setting('site_logo')}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.banner')}}</label>
                                <input type="file" class="form-control dropify" name="site_banner" 
                                data-default-file="{{setting('site_banner')}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.breadcrumb_image')}}</label>
                                <input type="file" class="form-control dropify" name="testimonials_banner" 
                                data-default-file="{{setting('testimonials_banner')}}" />
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.favicon')}}</label>
                                <input type="file" class="form-control dropify" name="site_favicon" 
                                data-default-file="{{setting('site_favicon')}}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <h2>{{__('message.colors_menu')}}</h2>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.display_front_color_theme_selector_panel')}}</label><br />
                                <input type="radio" class="minimal" name="display_front_color_theme_selector_panel" value="yes" 
                                {{sel(setting('display_front_color_theme_selector_panel'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="display_front_color_theme_selector_panel" value="no"
                                {{sel(setting('display_front_color_theme_selector_panel'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.default_front_color_theme')}}</label><br />
                                <select class="form-control" name="default_front_color_theme">
                                    <option value="blue" {{sel(setting('default_front_color_theme'), 'blue')}}>
                                        {{__('message.blue')}}
                                    </option>
                                    <option value="green" {{sel(setting('default_front_color_theme'), 'green')}}>
                                        {{__('message.green')}}
                                    </option>
                                    <option value="orange" {{sel(setting('default_front_color_theme'), 'orange')}}>
                                        {{__('message.orange')}}
                                    </option>
                                    <option value="magenta" {{sel(setting('default_front_color_theme'), 'magenta')}}>
                                        {{__('message.magenta')}}
                                    </option>
                                    <option value="brown" {{sel(setting('default_front_color_theme'), 'brown')}}>
                                        {{__('message.brown')}}
                                    </option>
                                    <option value="maldives" {{sel(setting('default_front_color_theme'), 'maldives')}}>
                                        {{__('message.maldives')}}
                                    </option>                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.display_main_menu_as_full_width')}}</label><br />
                                <input type="radio" class="minimal" name="display_main_menu_as_full_width" value="yes" 
                                {{sel(setting('display_main_menu_as_full_width'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="display_main_menu_as_full_width" value="no"
                                {{sel(setting('display_main_menu_as_full_width'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.display_main_menu_bg_as_transparent')}}</label><br />
                                <input type="radio" class="minimal" name="display_main_menu_bg_as_transparent" value="yes" 
                                {{sel(setting('display_main_menu_bg_as_transparent'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="display_main_menu_bg_as_transparent" value="no"
                                {{sel(setting('display_main_menu_bg_as_transparent'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.main_menu_bg')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="1">
                                    <input type="text" name="main_menu_bg" class="form-control" value="{{setting('main_menu_bg')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('main_menu_bg')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.main_banner_height')}}</label>
                                <input type="text" class="form-control" name="main_banner_height" value="{{setting('main_banner_height')}}">
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.main_banner_bg')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="main_banner_bg" class="form-control" value="{{setting('main_banner_bg')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('main_banner_bg')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.main_menu_font_color')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="main_menu_font_color" class="form-control" value="{{setting('main_menu_font_color')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('main_menu_font_color')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.main_menu_font_highlight_color')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="main_menu_font_highlight_color" class="form-control" value="{{setting('main_menu_font_highlight_color')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('main_menu_font_highlight_color')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.body_bg')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="body_bg" class="form-control" value="{{setting('body_bg')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('body_bg')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.main_menu_btn_bg')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="main_menu_btn_bg" class="form-control" value="{{setting('main_menu_btn_bg')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('main_menu_btn_bg')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.main_menu_sticky_bg')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="main_menu_sticky_bg" class="form-control" value="{{setting('main_menu_sticky_bg')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('main_menu_sticky_bg')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.main_menu_sticky_font_color')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="main_menu_sticky_font_color" class="form-control" value="{{setting('main_menu_sticky_font_color')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('main_menu_sticky_font_color')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.mobile_menu_bg')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="mobile_menu_bg" class="form-control" value="{{setting('mobile_menu_bg')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('mobile_menu_bg')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.mobile_menu_sidebar_bg')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="mobile_menu_sidebar_bg" class="form-control" value="{{setting('mobile_menu_sidebar_bg')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('mobile_menu_sidebar_bg')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.mobile_menu_font_color')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="mobile_menu_font_color" class="form-control" value="{{setting('mobile_menu_font_color')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('mobile_menu_font_color')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.icons_color')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="icons_color" class="form-control" value="{{setting('icons_color')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('icons_color')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.breadcrumb_background')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="breadcrumb_background" class="form-control" value="{{setting('breadcrumb_background')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('breadcrumb_background')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.breadcrumb_font_color')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="breadcrumb_font_color" class="form-control" value="{{setting('breadcrumb_font_color')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('breadcrumb_font_color')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.site_btn_bg')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="site_btn_bg" class="form-control" value="{{setting('site_btn_bg')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('site_btn_bg')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.site_btn_font_color')}}</label>
                                <div class="input-group colorpicker2 colorpicker-element" data-colorpicker-id="2">
                                    <input type="text" name="site_btn_font_color" class="form-control" value="{{setting('site_btn_font_color')}}" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <!--Inline style used because of dynamic data requirement-->
                                            <i class="fas fa-square" style="color: {{setting('site_btn_font_color')}};"></i>
                                        </span>
                                    </div>
                                </div>
                                <!-- /.input group -->
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <h2>{{__('message.listing_limits')}}</h2>
                        </div>                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.jobs_per_page')}}</label><br />
                                <input type="radio" class="minimal" name="jobs_per_page" value="5" 
                                {{sel(setting('jobs_per_page'), '5', 'checked')}}>
                                <strong>{{__('message.5')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="jobs_per_page" value="10"
                                {{sel(setting('jobs_per_page'), '10', 'checked')}}>
                                <strong>{{__('message.10')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="jobs_per_page" value="25"
                                {{sel(setting('jobs_per_page'), '25', 'checked')}}>
                                <strong>{{__('message.25')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="jobs_per_page" value="50"
                                {{sel(setting('jobs_per_page'), '50', 'checked')}}>
                                <strong>{{__('message.50')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.news_per_page')}}</label><br />
                                <input type="radio" class="minimal" name="news_per_page" value="5" 
                                {{sel(setting('news_per_page'), '5', 'checked')}}>
                                <strong>{{__('message.5')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="news_per_page" value="10"
                                {{sel(setting('news_per_page'), '10', 'checked')}}>
                                <strong>{{__('message.10')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="news_per_page" value="25"
                                {{sel(setting('news_per_page'), '25', 'checked')}}>
                                <strong>{{__('message.25')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="news_per_page" value="50"
                                {{sel(setting('news_per_page'), '50', 'checked')}}>
                                <strong>{{__('message.50')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.companies_per_page')}}</label><br />
                                <input type="radio" class="minimal" name="companies_per_page" value="5" 
                                {{sel(setting('companies_per_page'), '5', 'checked')}}>
                                <strong>{{__('message.5')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="companies_per_page" value="10"
                                {{sel(setting('companies_per_page'), '10', 'checked')}}>
                                <strong>{{__('message.10')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="companies_per_page" value="25"
                                {{sel(setting('companies_per_page'), '25', 'checked')}}>
                                <strong>{{__('message.25')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="companies_per_page" value="50"
                                {{sel(setting('companies_per_page'), '50', 'checked')}}>
                                <strong>{{__('message.50')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.candidates_per_page')}}</label><br />
                                <input type="radio" class="minimal" name="candidates_per_page" value="5" 
                                {{sel(setting('candidates_per_page'), '5', 'checked')}}>
                                <strong>{{__('message.5')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="candidates_per_page" value="10"
                                {{sel(setting('candidates_per_page'), '10', 'checked')}}>
                                <strong>{{__('message.10')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="candidates_per_page" value="25"
                                {{sel(setting('candidates_per_page'), '25', 'checked')}}>
                                <strong>{{__('message.25')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="candidates_per_page" value="50"
                                {{sel(setting('candidates_per_page'), '50', 'checked')}}>
                                <strong>{{__('message.50')}}</strong>
                            </div>
                        </div>                         
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <h2>{{__('message.footer')}}</h2>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    {{__('message.footer_column_1')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="footer_column_1">{{setting('footer_column_1')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    {{__('message.footer_column_2')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="footer_column_2">{{setting('footer_column_2')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    {{__('message.footer_column_3')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="footer_column_3">{{setting('footer_column_3')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    {{__('message.footer_column_4')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="footer_column_4">{{setting('footer_column_4')}}</textarea>
                            </div>
                        </div> 
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="admin_display_settings_form_button">
                                {{__('message.update')}}
                            </button>
                        </div>
                        <!-- /.col -->
                    </div>

                </form>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/setting.js"></script>
@endsection