@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-briefcase"></i> {{ __('message.blogs') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/employer/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fa fa-briefcase"></i> {{ __('message.blogs') }}</li>
            <li class="active">{{ __('message.create') }}</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('message.details') }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="employer_blog_create_update_form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.title') }}</label>
                                        <input type="hidden" name="blog_id" value="{{ encode($blog['blog_id']) }}" />
                                        <input type="text" class="form-control" placeholder="{{__('message.enter_title')}}" name="title" 
                                            value="{{ $blog['title'] }}">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.status') }}</label>
                                        <select class="form-control">
                                        <option value="1" {{ sel($blog['status'], 1) }}>{{ __('message.active') }}</option>
                                        <option value="0" {{ sel($blog['status'], 0) }}>{{ __('message.inactive') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-12">
                                    <div class="form-group">
                                        <label>
                                        {{ __('message.categories') }}
                                        </label>
                                        <select class="form-control select2" id="categories" name="blog_category_id">
                                            <option value="">{{ __('message.none') }}</option>
                                            @foreach ($categories as $key => $value)
                                            <option value="{{ encode($value['blog_category_id']) }}" {{ sel($blog['blog_category_id'], $value['blog_category_id']) }}>{{ ($value['title']) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            {{ __('message.description') }}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea id="description" name="description" rows="10" cols="80">{!! $blog['description'] !!}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <br />
                                        <label>{{ __('message.image') }}</label>
                                        @php $thumb = blogThumb($blog['image']); @endphp
                                        <input type="file" class="form-control dropify" name="image" data-default-file="{{ $thumb['image'] }}" />
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="employer_blog_create_update_form_button">{{ __('message.save') }}</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/blog.js"></script>
@endsection