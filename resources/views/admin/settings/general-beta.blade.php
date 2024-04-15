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
                <form id="admin_settings_form" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.site_name')}}</label>
                                <input type="text" class="form-control" name="site_name" 
                                value="{{setting('site_name')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.admin_email')}}</label>
                                <input type="text" class="form-control" name="admin_email" 
                                value="{{setting('admin_email')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.purchase_code')}}</label>
                                <input type="text" class="form-control" name="purchase_code" 
                                value="{{setting('purchase_code')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.site_keywords')}}</label>
                                <textarea class="form-control" name="site_keywords">{{setting('site_keywords')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.site_description')}}</label>
                                <textarea class="form-control" name="site_description">{{setting('site_description')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.front_header_scripts')}}</label>
                                <textarea class="form-control" name="front_header_scripts">{{setting('front_header_scripts')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.front_footer_scripts')}}</label>
                                <textarea class="form-control" name="front_footer_scripts">{{setting('front_footer_scripts')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.employer_header_scripts')}}</label>
                                <textarea class="form-control" name="employer_header_scripts">{{setting('employer_header_scripts')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.employer_footer_scripts')}}</label>
                                <textarea class="form-control" name="employer_footer_scripts">{{setting('employer_footer_scripts')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.candidate_header_scripts')}}</label>
                                <textarea class="form-control" name="candidate_header_scripts">{{setting('candidate_header_scripts')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.candidate_footer_scripts')}}</label>
                                <textarea class="form-control" name="candidate_footer_scripts">{{setting('candidate_footer_scripts')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.admin_file_upload_limit')}} (KB)</label>
                                <input type="number" class="form-control" name="admin_file_upload_limit" value="{{setting('admin_file_upload_limit')}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.general_file_upload_limit')}} (KB)</label>
                                <input type="number" class="form-control" name="general_file_upload_limit" value="{{setting('general_file_upload_limit')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_editor_for_email_templates')}}</label><br />
                                <input type="radio" class="minimal" name="enable_editor_for_email_templates" value="yes" 
                                {{sel(setting('enable_editor_for_email_templates'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_editor_for_email_templates" value="no"
                                {{sel(setting('enable_editor_for_email_templates'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <h2>{{__('message.employer')}}</h2>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_employer_registeration')}}</label><br />
                                <input type="radio" class="minimal" name="enable_employer_registeration" value="yes" 
                                {{sel(setting('enable_employer_registeration'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_employer_registeration" value="no"
                                {{sel(setting('enable_employer_registeration'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_employer_email_verification')}}</label><br />
                                <input type="radio" class="minimal" name="enable_employer_email_verification" value="yes" 
                                {{sel(setting('enable_employer_email_verification'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_employer_email_verification" value="no"
                                {{sel(setting('enable_employer_email_verification'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_employer_forgot_password')}}</label><br />
                                <input type="radio" class="minimal" name="enable_employer_forgot_password" value="yes" 
                                {{sel(setting('enable_employer_forgot_password'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_employer_forgot_password" value="no"
                                {{sel(setting('enable_employer_forgot_password'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_employer_free_registeration')}}</label><br />
                                <input type="radio" class="minimal" name="enable_employer_free_registeration" value="yes" 
                                {{sel(setting('enable_employer_free_registeration'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_employer_free_registeration" value="no"
                                {{sel(setting('enable_employer_free_registeration'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_employer_language_selector')}}</label><br />
                                <input type="radio" class="minimal" name="enable_employer_language_selector" value="yes" 
                                {{sel(setting('enable_employer_language_selector'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_employer_language_selector" value="no"
                                {{sel(setting('enable_employer_language_selector'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_only_employer_to_view_candidates')}}</label><br />
                                <input type="radio" class="minimal" name="enable_only_employer_to_view_candidates" value="yes" 
                                {{sel(setting('enable_only_employer_to_view_candidates'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_only_employer_to_view_candidates" value="no"
                                {{sel(setting('enable_only_employer_to_view_candidates'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.employer_free_registeration_days')}}</label>
                                <input type="number" class="form-control" name="employer_free_registeration_days" 
                                value="{{setting('employer_free_registeration_days')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_employer_register_notification')}}</label><br />
                                <input type="radio" class="minimal" name="enable_employer_register_notification" value="yes" 
                                {{sel(setting('enable_employer_register_notification'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_employer_register_notification" value="no"
                                {{sel(setting('enable_employer_register_notification'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_employer_job_apply_notification')}}</label><br />
                                <input type="radio" class="minimal" name="enable_employer_job_apply_notification" value="yes" 
                                {{sel(setting('enable_employer_job_apply_notification'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_employer_job_apply_notification" value="no"
                                {{sel(setting('enable_employer_job_apply_notification'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.import_employer_dummy_data_on_signup')}}</label><br />
                                <input type="radio" class="minimal" name="import_employer_dummy_data_on_signup" value="yes" 
                                {{sel(setting('import_employer_dummy_data_on_signup'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="import_employer_dummy_data_on_signup" value="no"
                                {{sel(setting('import_employer_dummy_data_on_signup'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.import_employer_dummy_data_on_creation')}}</label><br />
                                <input type="radio" class="minimal" name="import_employer_dummy_data_on_creation" value="yes" 
                                {{sel(setting('import_employer_dummy_data_on_creation'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="import_employer_dummy_data_on_creation" value="no"
                                {{sel(setting('import_employer_dummy_data_on_creation'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <h2>{{__('message.candidate')}}</h2>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_candidate_registeration')}}</label><br />
                                <input type="radio" class="minimal" name="enable_candidate_registeration" value="yes" 
                                {{sel(setting('enable_candidate_registeration'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_candidate_registeration" value="no"
                                {{sel(setting('enable_candidate_registeration'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_candidate_email_verification')}}</label><br />
                                <input type="radio" class="minimal" name="enable_candidate_email_verification" value="yes" 
                                {{sel(setting('enable_candidate_email_verification'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_candidate_email_verification" value="no"
                                {{sel(setting('enable_candidate_email_verification'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_candidate_forgot_password')}}</label><br />
                                <input type="radio" class="minimal" name="enable_candidate_forgot_password" value="yes" 
                                {{sel(setting('enable_candidate_forgot_password'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_candidate_forgot_password" value="no"
                                {{sel(setting('enable_candidate_forgot_password'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_candidate_register_notification')}}</label><br />
                                <input type="radio" class="minimal" name="enable_candidate_register_notification" value="yes" 
                                {{sel(setting('enable_candidate_register_notification'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_candidate_register_notification" value="no"
                                {{sel(setting('enable_candidate_register_notification'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_candidate_job_apply_notification')}}</label><br />
                                <input type="radio" class="minimal" name="enable_candidate_job_apply_notification" value="yes" 
                                {{sel(setting('enable_candidate_job_apply_notification'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_candidate_job_apply_notification" value="no"
                                {{sel(setting('enable_candidate_job_apply_notification'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_multiple_resume')}}</label><br />
                                <input type="radio" class="minimal" name="enable_multiple_resume" value="yes" 
                                {{sel(setting('enable_multiple_resume'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_multiple_resume" value="no"
                                {{sel(setting('enable_multiple_resume'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_candidate_dark_mode_button')}}</label><br />
                                <input type="radio" class="minimal" name="enable_candidate_dark_mode_button" value="yes" 
                                {{sel(setting('enable_candidate_dark_mode_button'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_candidate_dark_mode_button" value="no"
                                {{sel(setting('enable_candidate_dark_mode_button'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_candidate_language_selector')}}</label><br />
                                <input type="radio" class="minimal" name="enable_candidate_language_selector" value="yes" 
                                {{sel(setting('enable_candidate_language_selector'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_candidate_language_selector" value="no"
                                {{sel(setting('enable_candidate_language_selector'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.enable_candidate_packages')}}</label><br />
                                <input type="radio" class="minimal" name="enable_candidate_packages" value="yes" 
                                {{sel(setting('enable_candidate_packages'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="enable_candidate_packages" value="no"
                                {{sel(setting('enable_candidate_packages'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.default_settings_for_candidates')}}</label><br />
                                <input type="radio" class="minimal" name="default_settings_for_candidates" value="display" 
                                {{sel(setting('default_settings_for_candidates'), 'display', 'checked')}}>
                                <strong>{{__('message.display')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="default_settings_for_candidates" value="hidden"
                                {{sel(setting('default_settings_for_candidates'), 'hidden', 'checked')}}>
                                <strong>{{__('message.hidden')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <h2>{{__('message.contact')}}</h2>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.contact_phone')}}</label>
                                <input type="text" class="form-control" name="contact_phone" value="{{setting('contact_phone')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.contact_email')}}</label>
                                <input type="text" class="form-control" name="contact_email" value="{{setting('contact_email')}}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__('message.contact_address')}}</label>
                                <input type="text" class="form-control" name="contact_address" value="{{setting('contact_address')}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.contact_map')}}</label>
                                <textarea class="form-control" name="contact_map">{{setting('contact_map')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.contact_text')}}</label>
                                <textarea class="form-control" name="contact_text">{!!setting('contact_text')!!}</textarea>
                            </div>
                        </div>                        
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="admin_settings_form_button">
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