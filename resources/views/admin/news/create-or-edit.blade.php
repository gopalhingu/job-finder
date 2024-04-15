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
                    <h1 class="m-0">{{__('message.news')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.news')}}</li>
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('message.news').' '.__('message.edit')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <form id="admin_news_create_update_form">
                                <input type="hidden" name="news_id" value="{{ $news['news_id'] }}" />
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{__('message.title') }}</label>
                                                <input type="text" class="form-control" name="title" value="{{ $news['title'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{__('message.slug') }}
                                                    <i 
                                                        class="fas fa-question-circle" 
                                                        data-toggle="tooltip" 
                                                        data-placement="right" 
                                                        title="{{__('message.enter_blank_for_auto')}}"></i>
                                                </label>
                                                <input type="text" class="form-control" name="slug" value="{{ $news['slug'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('message.status') }}</label>
                                                <select class="form-control" name="status">
                                                    <option value="0" {{ sel($news['status'], '0') }}>{{ __('message.no') }}</option>
                                                    <option value="1" {{ sel($news['status'], '1') }}>{{ __('message.yes') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>{{ __('message.category') }}</label>
                                                <select class="form-control select2" name="category_id">
                                                    @foreach ($categories as $key => $value)
                                                    <option value="{{ $value['category_id'] }}" {{sel($value['category_id'], $news['category_id'])}}>{{ $value['title'] }}</option>
                                                    @endforeach
                                                </select>                    
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__('message.keywords')}}</label>
                                                <textarea class="form-control" name="keywords">{{$news['keywords']}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__('message.summary')}}</label>
                                                <textarea class="form-control" name="summary">{{$news['summary']}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>
                                                    {{__('message.description')}}
                                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                                </label>
                                                <textarea class="form-control" id="news-description" name="description">{{$news['description']}}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>{{__('message.image') }}</label>
                                                @php $thumb = newsThumb($news['image']); @endphp
                                                <input type="file" class="form-control dropify" name="image" 
                                                    data-default-file="{{$thumb['image']}}" />
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
                                    <button type="submit" class="btn btn-primary btn-blue" id="admin_news_create_update_form_button">{{__('message.save') }}</button>
                                </div>
                            </form>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Forms for actions -->
<form id="news-form" method="POST" action="{{url(route('admin-news-excel'))}}" target='_blank'></form>

@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/news.js"></script>
@endsection