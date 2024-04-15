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
                @php

                $tags1 = array('((site_link))','((site_logo))', '((first_name))','((last_name))','((email))');
                $tags2 = array('((site_link))','((site_logo))', '((first_name))','((last_name))','((email))','((link))');
                $tags3 = array('((site_link))','((site_logo))', '((first_name))','((last_name))','((link))');
                $tags4 = array('((site_link))','((site_logo))', '((first_name))','((last_name))','((email))','((package))');
                $tags5 = array('((site_link))','((site_logo))', '((first_name))','((last_name))','((email))','((link))');
                $tags6 = array('((site_link))','((site_logo))', '((first_name))','((last_name))','((link))');
                $tags7 = array('((site_link))','((site_logo))', '((name))', '((first_name))','((last_name))','((link))');

                $temp1 = route('uploads-view', 'templates/candidate-signup.html');
                $temp2 = route('uploads-view', 'templates/candidate-verify-email.html');
                $temp3 = route('uploads-view', 'templates/candidate-reset-password.html');
                $temp4 = route('uploads-view', 'templates/employer-signup.html');
                $temp5 = route('uploads-view', 'templates/employer-verify-email.html');
                $temp6 = route('uploads-view', 'templates/employer-reset-password.html');
                $temp7 = route('uploads-view', 'templates/employer-refer-job.html');

                @endphp

                <input type="hidden" id="enable_editor_for_email_templates" value="{{setting('enable_editor_for_email_templates')}}">
                <form id="admin_email_templates_form" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.candidate_signup')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags1)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="candidate_signup" id="candidate_signup">
                                    {!! setting('candidate_signup') !!}
                                </textarea>                                
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
                                    {{__('message.candidate_verify_email')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags2)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="candidate_verify_email" id="candidate_verify_email">
                                    {!! setting('candidate_verify_email') !!}
                                </textarea>
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
                                    {{__('message.candidate_reset_password')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags3)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="candidate_reset_password" id="candidate_reset_password">
                                    {!! setting('candidate_reset_password') !!}
                                </textarea>
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
                                    {{__('message.employer_signup')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags4)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="employer_signup" id="employer_signup">
                                    {!! setting('employer_signup') !!}
                                </textarea>
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
                                    {{__('message.employer_verify_email')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags5)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="employer_verify_email" id="employer_verify_email">
                                    {!! setting('employer_verify_email') !!}
                                </textarea>
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
                                    {{__('message.employer_reset_password')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags6)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="employer_reset_password" id="employer_reset_password">
                                    {!! setting('employer_reset_password') !!}
                                </textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <iframe src="{{$temp6}}" class="template-iframe"></iframe>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>
                                    {{__('message.employer_refer_job')}}
                                    <i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.tags_reserved_valid_msg', array('tags' => implode(', ', $tags7)))}}"></i>
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea rows="20" class="form-control" name="employer_refer_job" id="employer_refer_job">
                                    {!! setting('employer_refer_job') !!}
                                </textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <iframe src="{{$temp7}}" class="template-iframe"></iframe>
                        </div>
                        <div class="col-md-12">
                            <hr />
                        </div>
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="admin_email_templates_form_button">
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