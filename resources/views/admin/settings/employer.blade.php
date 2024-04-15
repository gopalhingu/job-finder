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
                    <h1 class="m-0">{{__('message.employer_settings')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{__('message.employer_settings')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.employer_settings')}}</li>
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
                    <h3 class="card-title">{{ __('message.employer_settings') }}</h3>
                    &nbsp;<i class="fa fa-question-circle membership-feature" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.employer_settings_override_msg')}}"></i>                        

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <input type="hidden" id="enable_editor_for_email_templates" value="{{setting('enable_editor_for_email_templates')}}">
                <form id="admin_employer_settings_form" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.banner_text')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="banner_text" id="banner_text">{{setting('banner_text')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.before_blogs_text')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="before_blogs_text" id="before_blogs_text">{{setting('before_blogs_text')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.after_blogs_text')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="after_blogs_text" id="after_blogs_text">{{setting('after_blogs_text')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.before_how_text')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="before_how_text" id="before_how_text">{{setting('before_how_text')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.after_how_text')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="after_how_text" id="after_how_text">{{setting('after_how_text')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.footer_col_1')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="footer_col_1" id="footer_col_1">{{setting('footer_col_1')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.footer_col_2')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="footer_col_2" id="footer_col_2">{{setting('footer_col_2')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.footer_col_3')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="footer_col_3" id="footer_col_3">{{setting('footer_col_3')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.footer_col_4')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" name="footer_col_4" id="footer_col_4">{{setting('footer_col_4')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-12">
                            <h2>{{__('message.email_templates')}}</h2>
                        </div>


                        @php

                        $tags1 = array('((first_name))','((last_name))','((job_title))','((site_name))','((site_link))','((site_logo))');                            
                        $tags2 = array('((job_title))','((site_name))','((site_link))','((site_logo))');
                        $tags3 = array('((first_name))','((last_name))','((job_title))','((site_link))','((site_logo))', '((date_time))', '((description))');
                        $tags4 = array('((first_name))','((last_name))','((job_title))','((site_name))','((site_link))','((site_logo))','((date_time))','((description))');
                        $tags5 =  array('((first_name))','((last_name))','((job_title))','((site_link))','((site_logo))','((quiz))');
                        $tags6 =  array('((first_name))','((last_name))','((email))','((password))', '((link))','((site_link))','((site_logo))');

                        $temp1 = route('uploads-view', 'employer-email-templates/candidate-job-app.html');
                        $temp2 = route('uploads-view', 'employer-email-templates/employer-job-app.html');
                        $temp3 = route('uploads-view', 'employer-email-templates/employer-interview-assign.html');
                        $temp4 = route('uploads-view', 'employer-email-templates/candidate-interview-assign.html');
                        $temp5 = route('uploads-view', 'employer-email-templates/candidate-quiz-assign.html');
                        $temp6 = route('uploads-view', 'employer-email-templates/team-creation.html');

                        @endphp
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.candidate_job_app')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags1)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="candidate_job_app" id="candidate_job_app">{!! setting('candidate_job_app') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <iframe src="{{$temp1}}" class="template-iframe"></iframe>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.employer_job_app')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags2)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="employer_job_app" id="employer_job_app">{!! setting('employer_job_app') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <iframe src="{{$temp2}}" class="template-iframe"></iframe>
                        </div>                                
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.employer_interview_assign')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags3)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="employer_interview_assign" id="employer_interview_assign">{!! setting('employer_interview_assign') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <iframe src="{{$temp3}}" class="template-iframe"></iframe>
                        </div>                                
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.candidate_interview_assign')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags4)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="candidate_interview_assign" id="candidate_interview_assign">{!! setting('candidate_interview_assign') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <iframe src="{{$temp4}}" class="template-iframe"></iframe>
                        </div>                                
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.candidate_quiz_assign')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags5)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="candidate_quiz_assign" id="candidate_quiz_assign">{!! setting('candidate_quiz_assign') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <iframe src="{{$temp5}}" class="template-iframe"></iframe>
                        </div>                                
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.team_creation')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags6)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="team_creation" id="team_creation">{!! setting('team_creation') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                           <iframe src="{{$temp6}}" class="template-iframe"></iframe>
                        </div>                                
                        <div class="col-md-12">
                            <hr />
                        </div>

                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="admin_employer_settings_form_button">
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