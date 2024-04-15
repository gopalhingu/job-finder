@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper Starts -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-cube"></i> {{ __('message.update_css') }}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fas fa-cube"></i> {{ __('message.update_css') }}</li>
        </ol>
    </section>
    <!-- Main content Starts-->
    <section class="content">
        <!-- Main row Starts-->
        <div class="row">
            <section class="col-lg-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">CSS</h3>
                    </div>
                    @if(empAllowedTo('css_settings'))
                    <form id="employer_css_form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <input type="hidden" name="css" id="editor-hidden" />
                                    <textarea id="css-editor">{!! settingEmp('css') !!}</textarea>
                                </div>
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="employer_css_form_button">{{ __('message.save') }}</button>
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
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cssbeautify.codemirror.js"></script>
<script src="{{url('e-assets')}}/js/cssbeautify.css.js"></script>
<script src="{{url('e-assets')}}/js/cssbeautify.js"></script>
<script src="{{url('e-assets')}}/js/cf/setting.js"></script>
@endsection