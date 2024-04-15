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
                        <li class="breadcrumb-item active">{{__('message.portal_vs_multitenancy').' '.__('message.settings')}}</li>
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
                    <h3 class="card-title">{{ __('message.portal_vs_multitenancy').' '.__('message.settings') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <form id="portal_vs_multitenancy_settings_form" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.enable_separate_employer_site')}}</label><br />
                                <input type="radio" class="minimal" name="enable_separate_employer_site" value="yes" 
                                {{sel(setting('enable_separate_employer_site'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_separate_employer_site" value="no"
                                {{sel(setting('enable_separate_employer_site'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_separate_employer_site" value="only_for_employers_with_separate_site"
                                {{sel(setting('enable_separate_employer_site'), 'only_for_employers_with_separate_site', 'checked')}}>
                                <strong>{{__('message.only_for_employers_with_separate_site')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.notes')}}</label><br />
                                <ul>
                                    <li>{{__('message.yes_separate_site_msg')}}</li>
                                    <li>{{__('message.no_separate_site_msg')}}</li>
                                    <li>{{__('message.employers_with_separate_site_msg')}}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.departments_creation')}}</label><br />
                                <input type="radio" class="minimal" name="departments_creation" value="only_admin" 
                                {{sel(setting('departments_creation'), 'only_admin', 'checked')}}>
                                <strong>{{__('message.only_admin')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="departments_creation" value="both_admin_and_employer"
                                {{sel(setting('departments_creation'), 'both_admin_and_employer', 'checked')}}>
                                <strong>{{__('message.both_admin_and_employer')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.notes')}}</label><br />
                                <ul>
                                    <li>{{__('message.only_admin_msg_departments')}}</li>
                                    <li>{{__('message.both_creation_msg_department')}}</li>
                                    <li>{{__('message.departments_display_scenario_1')}}</li>
                                    <li>{{__('message.departments_display_scenario_2')}}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.job_filters_creation')}}</label><br />
                                <input type="radio" class="minimal" name="job_filters_creation" value="only_admin" 
                                {{sel(setting('job_filters_creation'), 'only_admin', 'checked')}}>
                                <strong>{{__('message.only_admin')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="job_filters_creation" value="both_admin_and_employer"
                                {{sel(setting('job_filters_creation'), 'both_admin_and_employer', 'checked')}}>
                                <strong>{{__('message.both_admin_and_employer')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.notes')}}</label><br />
                                <ul>
                                    <li>{{__('message.only_admin_msg_job_filters')}}</li>
                                    <li>{{__('message.both_creation_msg_job_filters')}}</li>
                                    <li>{{__('message.job_filters_display_scenario_1')}}</li>
                                    <li>{{__('message.job_filters_display_scenario_2')}}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.front_login_type')}}</label><br />
                                <input type="radio" class="minimal" name="front_login_type" value="only_candidates" 
                                {{sel(setting('front_login_type'), 'only_candidates', 'checked')}}>
                                <strong>{{__('message.only_candidates')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="front_login_type" value="only_employers" 
                                {{sel(setting('front_login_type'), 'only_employers', 'checked')}}>
                                <strong>{{__('message.only_employers')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="front_login_type" value="both"
                                {{sel(setting('front_login_type'), 'both', 'checked')}}>
                                <strong>{{__('message.both')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.display_jobs_front')}}</label><br />
                                <input type="radio" class="minimal" name="display_jobs_front" value="from_all_employers" 
                                {{sel(setting('display_jobs_front'), 'from_all_employers', 'checked')}}>
                                <strong>{{__('message.from_all_employers')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="display_jobs_front" value="employers_without_separate_site"
                                {{sel(setting('display_jobs_front'), 'employers_without_separate_site', 'checked')}}>
                                <strong>{{__('message.employers_without_separate_site')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.display_departments_front')}}</label><br />
                                <input type="radio" class="minimal" name="display_departments_front" value="yes" 
                                {{sel(setting('display_departments_front'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="display_departments_front" value="no"
                                {{sel(setting('display_departments_front'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.display_employers_front')}}</label><br />
                                <input type="radio" class="minimal" name="display_employers_front" value="yes" 
                                {{sel(setting('display_employers_front'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="display_employers_front" value="no"
                                {{sel(setting('display_employers_front'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="portal_vs_multitenancy_settings_form_button">
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