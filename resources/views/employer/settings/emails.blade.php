@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper Starts -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-cube"></i> {{ __('message.update_emails_settings') }}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fas fa-cube"></i> {{ __('message.update_emails_settings') }}</li>
        </ol>
    </section>
    <!-- Main content Starts-->
    <section class="content">
        <!-- Main row Starts-->
        <div class="row">
            <section class="col-lg-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('message.emails_settings') }}</h3>
                        <i class="fa fa-question-circle membership-feature" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.membership_custom_email_msg')}}"></i>
                    </div>
                    @if(empAllowedTo('emails'))
                    <input type="hidden" id="enable_editor_for_email_templates" value="{{setting('enable_editor_for_email_templates')}}">
                    <form id="employer_settings_emails_form">
                        @csrf
                        <div class="box-body">
                            <div class="row">         
                                @php

                                $tags1 = array('((first_name))','((last_name))','((job_title))','((site_name))','((site_link))','((site_logo))');                            
                                $tags2 = array('((job_title))','((site_name))','((site_link))','((site_logo))');
                                $tags3 = array('((first_name))','((last_name))','((job_title))','((site_link))','((site_logo))', '((date_time))', '((description))');
                                $tags4 = array('((first_name))','((last_name))','((job_title))','((site_name))','((site_link))','((site_logo))','((date_time))','((description))');
                                $tags5 =  array('((first_name))','((last_name))','((job_title))','((site_link))','((site_logo))','((quiz))');
                                $tags6 =  array('((first_name))','((last_name))','((email))','((password))', '((link))','((site_link))','((site_logo))');

                                @endphp
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            {{__('message.candidate_job_app')}}
                                            <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags1)))}}"></i>
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea rows="20" class="form-control" name="candidate_job_app" id="candidate_job_app">{!! settingEmp('candidate_job_app', true) !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   <iframe src="{{route('uploads-view', employerPath().'/templates/candidate-job-app.html')}}" class="template-iframe"></iframe>
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
                                        <textarea rows="20" class="form-control" name="employer_job_app" id="employer_job_app">{!! settingEmp('employer_job_app', true) !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   <iframe src="{{route('uploads-view', employerPath().'/templates/employer-job-app.html')}}" class="template-iframe"></iframe>
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
                                        <textarea rows="20" class="form-control" name="employer_interview_assign" id="employer_interview_assign">{!! settingEmp('employer_interview_assign', true) !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   <iframe src="{{route('uploads-view', employerPath().'/templates/employer-interview-assign.html')}}" class="template-iframe"></iframe>
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
                                        <textarea rows="20" class="form-control" name="candidate_interview_assign" id="candidate_interview_assign">{!! settingEmp('candidate_interview_assign', true) !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   <iframe src="{{route('uploads-view', employerPath().'/templates/candidate-interview-assign.html')}}" class="template-iframe"></iframe>
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
                                        <textarea rows="20" class="form-control" name="candidate_quiz_assign" id="candidate_quiz_assign">{!! settingEmp('candidate_quiz_assign', true) !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   <iframe src="{{route('uploads-view', employerPath().'/templates/candidate-quiz-assign.html')}}" class="template-iframe"></iframe>
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
                                        <textarea rows="20" class="form-control" name="team_creation" id="team_creation">{!! settingEmp('team_creation', true) !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                   <iframe src="{{route('uploads-view', employerPath().'/templates/team-creation.html')}}" class="template-iframe"></iframe>
                                </div>                                
                                <div class="col-md-12">
                                    <hr />
                                </div>
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="employer_settings_emails_form_button">{{ __('message.save') }}</button>
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