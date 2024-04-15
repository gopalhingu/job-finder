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
                    <h1 class="m-0">{{__('message.home')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{__('message.settings')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.home')}}</li>
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
                    <h3 class="card-title">{{ __('message.home_settings') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <form id="admin_home_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.home_banner')}}</label><br />
                                <input type="radio" class="minimal" name="home_banner" value="yes" 
                                {{sel(setting('home_banner'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_banner" value="no"
                                {{sel(setting('home_banner'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    {{__('message.home_banner_text')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" id="home-banner-text" name="home_banner_text">{!! setting('home_banner_text') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.enable_feature_section')}}</label><br />
                                <input type="radio" class="minimal" name="enable_feature_section" value="yes" 
                                {{sel(setting('enable_feature_section'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_feature_section" value="no"
                                {{sel(setting('enable_feature_section'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.quiz_feature')}}</label><br />
                                <input type="radio" class="minimal" name="quiz_feature" value="yes" 
                                {{sel(setting('quiz_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="quiz_feature" value="no"
                                {{sel(setting('quiz_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.interview_feature')}}</label><br />
                                <input type="radio" class="minimal" name="interview_feature" value="yes" 
                                {{sel(setting('interview_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="interview_feature" value="no"
                                {{sel(setting('interview_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.assesment_feature')}}</label><br />
                                <input type="radio" class="minimal" name="assesment_feature" value="yes" 
                                {{sel(setting('assesment_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="assesment_feature" value="no"
                                {{sel(setting('assesment_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.resume_feature')}}</label><br />
                                <input type="radio" class="minimal" name="resume_feature" value="yes" 
                                {{sel(setting('resume_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="resume_feature" value="no"
                                {{sel(setting('resume_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.filter_feature')}}</label><br />
                                <input type="radio" class="minimal" name="filter_feature" value="yes" 
                                {{sel(setting('filter_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="filter_feature" value="no"
                                {{sel(setting('filter_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.referral_feature')}}</label><br />
                                <input type="radio" class="minimal" name="referral_feature" value="yes" 
                                {{sel(setting('referral_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="referral_feature" value="no"
                                {{sel(setting('referral_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.oauth_feature')}}</label><br />
                                <input type="radio" class="minimal" name="oauth_feature" value="yes" 
                                {{sel(setting('oauth_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="oauth_feature" value="no"
                                {{sel(setting('oauth_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.translation_feature')}}</label><br />
                                <input type="radio" class="minimal" name="translation_feature" value="yes" 
                                {{sel(setting('translation_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="translation_feature" value="no"
                                {{sel(setting('translation_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.setting_feature')}}</label><br />
                                <input type="radio" class="minimal" name="setting_feature" value="yes" 
                                {{sel(setting('setting_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="setting_feature" value="no"
                                {{sel(setting('setting_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.roles_feature')}}</label><br />
                                <input type="radio" class="minimal" name="roles_feature" value="yes" 
                                {{sel(setting('roles_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="roles_feature" value="no"
                                {{sel(setting('roles_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.reports_feature')}}</label><br />
                                <input type="radio" class="minimal" name="reports_feature" value="yes" 
                                {{sel(setting('reports_feature'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="reports_feature" value="no"
                                {{sel(setting('reports_feature'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.home_pricing')}}</label><br />
                                <input type="radio" class="minimal" name="home_pricing" value="yes" 
                                {{sel(setting('home_pricing'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_pricing" value="no"
                                {{sel(setting('home_pricing'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.home_news')}}</label><br />
                                <input type="radio" class="minimal" name="home_news" value="yes" 
                                {{sel(setting('home_news'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_news" value="no"
                                {{sel(setting('home_news'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.home_news_limit')}}</label>
                                <input type="number" class="form-control" name="home_news_limit" 
                                value="{{setting('home_news_limit')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.home_portfolio')}}</label><br />
                                <input type="radio" class="minimal" name="home_portfolio" value="yes" 
                                {{sel(setting('home_portfolio'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_portfolio" value="no"
                                {{sel(setting('home_portfolio'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.home_portfolio_limit')}}</label>
                                <input type="text" class="form-control" name="home_portfolio_limit" 
                                value="{{setting('home_portfolio_limit')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.home_testimonial')}}</label><br />
                                <input type="radio" class="minimal" name="home_testimonial" value="yes" 
                                {{sel(setting('home_testimonial'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_testimonial" value="no"
                                {{sel(setting('home_testimonial'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.home_testimonial_limit')}}</label>
                                <input type="text" class="form-control" name="home_testimonial_limit" 
                                value="{{setting('home_testimonial_limit')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.home_contact')}}</label><br />
                                <input type="radio" class="minimal" name="home_contact" value="yes" 
                                {{sel(setting('home_contact'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_contact" value="no"
                                {{sel(setting('home_contact'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.home_contact_form')}}</label><br />
                                <input type="radio" class="minimal" name="home_contact_form" value="yes" 
                                {{sel(setting('home_contact_form'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_contact_form" value="no"
                                {{sel(setting('home_contact_form'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.contact_phone')}}</label>
                                <input type="text" class="form-control" name="contact_phone" 
                                value="{{setting('contact_phone')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.contact_email')}}</label>
                                <input type="text" class="form-control" name="contact_email" 
                                value="{{setting('contact_email')}}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>{{__('message.contact_address')}}</label>
                                <input type="text" class="form-control" name="contact_address" 
                                value="{{setting('contact_address')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
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
                            <button type="submit" class="btn btn-primary" id="admin_home_form_button">
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