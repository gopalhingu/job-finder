@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper Starts -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-cube"></i> {{ __('message.update_branding_settings') }}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fas fa-cube"></i> {{ __('message.update_branding_settings') }}</li>
        </ol>
    </section>
    <!-- Main content Starts-->
    <section class="content">
        <!-- Main row Starts-->
        <div class="row">
            <section class="col-lg-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('message.branding_settings') }}</h3>
                        <i class="fa fa-question-circle membership-feature" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.membership_branding_msg')}}"></i>                        
                    </div>
                    @if(empAllowedTo('branding'))
                    <form id="employer_settings_branding_form">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('message.logo')}}</label>
                                        <input type="file" class="form-control dropify" name="site_logo" 
                                        data-default-file="{{settingEmp('site_logo', true)}}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('message.banner')}}</label>
                                        <input type="file" class="form-control dropify" name="site_banner" 
                                        data-default-file="{{settingEmp('site_banner', true)}}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>{{__('message.favicon')}}</label>
                                        <input type="file" class="form-control dropify" name="site_favicon" 
                                        data-default-file="{{settingEmp('site_favicon', true)}}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.site_name')}}</label>
                                        <input type="text" class="form-control" name="site_name" 
                                        value="{{settingEmp('site_name', true)}}">
                                    </div>
                                </div>
                                @php 
                                    $cssVars = settingEmp('css_variables'); 
                                    $cssVars = objToArr(json_decode($cssVars));
                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.site_keywords')}}</label>
                                        <textarea rows="6" class="form-control" name="site_keywords">{{settingEmp('site_keywords', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.site_description')}}</label>
                                        <textarea rows="6" class="form-control" name="site_description">{{settingEmp('site_description', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.banner_text')}}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="6" class="form-control" name="banner_text">{{settingEmp('banner_text', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.before_blogs_text')}}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="6" class="form-control" name="before_blogs_text">{{settingEmp('before_blogs_text', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.after_blogs_text')}}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="6" class="form-control" name="after_blogs_text">{{settingEmp('after_blogs_text', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.before_how_text')}}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="6" class="form-control" name="before_how_text">{{settingEmp('before_how_text', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.after_how_text')}}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="6" class="form-control" name="after_how_text">{{settingEmp('after_how_text', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.footer_col_1')}}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="6" class="form-control" name="footer_col_1">{{settingEmp('footer_col_1', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.footer_col_2')}}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="6" class="form-control" name="footer_col_2">{{settingEmp('footer_col_2', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.footer_col_3')}}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="6" class="form-control" name="footer_col_3">{{settingEmp('footer_col_3', true)}}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.footer_col_4')}}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="6" class="form-control" name="footer_col_4">{{settingEmp('footer_col_4', true)}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="employer_settings_branding_form_button">{{ __('message.save') }}</button>
                        </div>
                    </form>
                    @endif
                </div>
            </section>
        </div>
        <!-- Main row Ends-->
    </section>
    <!-- Main content Ends-->
</div>
<!-- Content Wrapper Ends -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/setting.js"></script>
@endsection