@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper Starts -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-cube"></i> {{ __('message.update_general_settings') }}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fas fa-cube"></i> {{ __('message.update_general_settings') }}</li>
        </ol>
    </section>
    <!-- Main content Starts-->
    <section class="content">
        <!-- Main row Starts-->
        <div class="row">
            <section class="col-lg-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('message.general_settings') }}</h3>
                    </div>
                    @if(empAllowedTo('general'))
                    <form id="employer_settings_general_form">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.admin_email')}}</label>
                                        <input type="text" class="form-control" name="admin_email" 
                                        value="{{settingEmp('admin_email', true)}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.from_email')}}</label>
                                        <input type="text" class="form-control" name="from_email" 
                                        value="{{settingEmp('from_email', true)}}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.jobs_per_page')}}</label><br />
                                        <input type="radio" class="minimal" name="jobs_per_page" value="5" 
                                        {{sel(settingEmp('jobs_per_page', true), '5', 'checked')}}>
                                        <strong>{{__('message.5')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="jobs_per_page" value="10"
                                        {{sel(settingEmp('jobs_per_page', true), '10', 'checked')}}>
                                        <strong>{{__('message.10')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="jobs_per_page" value="25"
                                        {{sel(settingEmp('jobs_per_page', true), '25', 'checked')}}>
                                        <strong>{{__('message.25')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="jobs_per_page" value="50"
                                        {{sel(settingEmp('jobs_per_page', true), '50', 'checked')}}>
                                        <strong>{{__('message.50')}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.blogs_per_page')}}</label><br />
                                        <input type="radio" class="minimal" name="blogs_per_page" value="5" 
                                        {{sel(settingEmp('blogs_per_page', true), '5', 'checked')}}>
                                        <strong>{{__('message.5')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="blogs_per_page" value="10"
                                        {{sel(settingEmp('blogs_per_page', true), '10', 'checked')}}>
                                        <strong>{{__('message.10')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="blogs_per_page" value="25"
                                        {{sel(settingEmp('blogs_per_page', true), '25', 'checked')}}>
                                        <strong>{{__('message.25')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="blogs_per_page" value="50"
                                        {{sel(settingEmp('blogs_per_page', true), '50', 'checked')}}>
                                        <strong>{{__('message.50')}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.charts_count_on_dashboard')}}</label><br />
                                        <input type="radio" class="minimal" name="charts_count_on_dashboard" value="5" 
                                        {{sel(settingEmp('charts_count_on_dashboard', true), '5', 'checked')}}>
                                        <strong>{{__('message.5')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="charts_count_on_dashboard" value="10"
                                        {{sel(settingEmp('charts_count_on_dashboard', true), '10', 'checked')}}>
                                        <strong>{{__('message.10')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="charts_count_on_dashboard" value="25"
                                        {{sel(settingEmp('charts_count_on_dashboard', true), '25', 'checked')}}>
                                        <strong>{{__('message.25')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="charts_count_on_dashboard" value="50"
                                        {{sel(settingEmp('charts_count_on_dashboard', true), '50', 'checked')}}>
                                        <strong>{{__('message.50')}}</strong>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.default_landing_page')}}</label><br />
                                        <input type="radio" class="minimal" name="default_landing_page" value="home" 
                                        {{sel(settingEmp('default_landing_page', true), 'home', 'checked')}}>
                                        <strong>{{__('message.5')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="default_landing_page" value="blog"
                                        {{sel(settingEmp('default_landing_page', true), 'blog', 'checked')}}>
                                        <strong>{{__('message.10')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="default_landing_page" value="jobs"
                                        {{sel(settingEmp('default_landing_page', true), 'jobs', 'checked')}}>
                                        <strong>{{__('message.25')}}</strong>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.enable_home_banner')}}</label><br />
                                        <input type="radio" class="minimal" name="enable_home_banner" value="yes" 
                                        {{sel(settingEmp('enable_home_banner', true), 'yes', 'checked')}}>
                                        <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="enable_home_banner" value="no"
                                        {{sel(settingEmp('enable_home_banner', true), 'no', 'checked')}}>
                                        <strong>{{__('message.no')}}</strong>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.home_how_it_works')}}</label><br />
                                        <input type="radio" class="minimal" name="home_how_it_works" value="yes" 
                                        {{sel(settingEmp('home_how_it_works', true), 'yes', 'checked')}}>
                                        <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="home_how_it_works" value="no"
                                        {{sel(settingEmp('home_how_it_works', true), 'no', 'checked')}}>
                                        <strong>{{__('message.no')}}</strong>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.home_department_section')}}</label><br />
                                        <input type="radio" class="minimal" name="home_department_section" value="yes" 
                                        {{sel(settingEmp('home_department_section', true), 'yes', 'checked')}}>
                                        <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="home_department_section" value="no"
                                        {{sel(settingEmp('home_department_section', true), 'no', 'checked')}}>
                                        <strong>{{__('message.no')}}</strong>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.home_blogs_section')}}</label><br />
                                        <input type="radio" class="minimal" name="home_blogs_section" value="yes" 
                                        {{sel(settingEmp('home_blogs_section', true), 'yes', 'checked')}}>
                                        <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="home_blogs_section" value="no"
                                        {{sel(settingEmp('home_blogs_section', true), 'no', 'checked')}}>
                                        <strong>{{__('message.no')}}</strong>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.display_jobs_to_only_logged_in_users')}}</label><br />
                                        <input type="radio" class="minimal" name="display_jobs_to_only_logged_in_users" value="yes" 
                                        {{sel(settingEmp('display_jobs_to_only_logged_in_users', true), 'yes', 'checked')}}>
                                        <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="display_jobs_to_only_logged_in_users" value="no"
                                        {{sel(settingEmp('display_jobs_to_only_logged_in_users', true), 'no', 'checked')}}>
                                        <strong>{{__('message.no')}}</strong>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.display_jobs_last_date')}}</label><br />
                                        <input type="radio" class="minimal" name="display_jobs_last_date" value="yes" 
                                        {{sel(settingEmp('display_jobs_last_date', true), 'yes', 'checked')}}>
                                        <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="display_jobs_last_date" value="no"
                                        {{sel(settingEmp('display_jobs_last_date', true), 'no', 'checked')}}>
                                        <strong>{{__('message.no')}}</strong>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                @if(setting('departments_creation') != 'only_admin')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.display_admin_created_departments')}}</label><br />
                                        <input type="radio" class="minimal" name="display_admin_created_departments" value="yes" 
                                        {{sel(settingEmp('display_admin_created_departments', true), 'yes', 'checked')}}>
                                        <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="display_admin_created_departments" value="no"
                                        {{sel(settingEmp('display_admin_created_departments', true), 'no', 'checked')}}>
                                        <strong>{{__('message.no')}}</strong>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                @endif
                                @if(setting('job_filters_creation') != 'only_admin')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{__('message.display_admin_created_job_filters')}}</label><br />
                                        <input type="radio" class="minimal" name="display_admin_created_job_filters" value="yes" 
                                        {{sel(settingEmp('display_admin_created_job_filters', true), 'yes', 'checked')}}>
                                        <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                        <input type="radio" class="minimal" name="display_admin_created_job_filters" value="no"
                                        {{sel(settingEmp('display_admin_created_job_filters', true), 'no', 'checked')}}>
                                        <strong>{{__('message.no')}}</strong>&nbsp;&nbsp;&nbsp;
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-12">
                                    <hr />
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{__('message.testimonial')}}</label>
                                        <textarea rows="10" class="form-control" name="testimonial">{{ issetVal($testimonial, 'testimonial') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>{{__('message.testimonial').' '.__('message.rating')}}</label>
                                        <select class="form-control" name="rating">
                                            <option value="1" {{sel(1, issetVal($testimonial, 'rating'))}}>{{__('message.one')}}</option>
                                            <option value="2" {{sel(2, issetVal($testimonial, 'rating'))}}>{{__('message.two')}}</option>
                                            <option value="3" {{sel(3, issetVal($testimonial, 'rating'))}}>{{__('message.three')}}</option>
                                            <option value="4" {{sel(4, issetVal($testimonial, 'rating'))}}>{{__('message.four')}}</option>
                                            <option value="5" {{sel(5, issetVal($testimonial, 'rating'))}}>{{__('message.five')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="employer_settings_general_form_button">{{ __('message.save') }}</button>
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