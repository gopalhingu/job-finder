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
                    <h1 class="m-0">{{__('message.home')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{__('message.settings')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.home')}}</li>
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
                    <h3 class="card-title">{{ __('message.home_settings') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <form id="admin_home_form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.home_banner')}}</label><br />
                                <input type="radio" class="minimal" name="home_banner" value="yes" 
                                {{sel(setting('home_banner'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_banner" value="no"
                                {{sel(setting('home_banner'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.home_banner_type')}}</label><br />
                                <input type="radio" class="minimal" name="home_banner_type" value="side_image" 
                                {{sel(setting('home_banner_type'), 'side_image', 'checked')}}>
                                <strong>{{__('message.side_image')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_banner_type" value="full_background_image"
                                {{sel(setting('home_banner_type'), 'full_background_image', 'checked')}}>
                                <strong>{{__('message.full_background_image')}}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__('message.home_banner_filters_display')}}</label><br />
                                <input type="radio" class="minimal" name="home_banner_filters_display" value="yes" 
                                {{sel(setting('home_banner_filters_display'), 'yes', 'checked')}}>
                                <strong>{{__('message.yes')}}</strong>&nbsp;&nbsp;&nbsp;
                                <input type="radio" class="minimal" name="home_banner_filters_display" value="no"
                                {{sel(setting('home_banner_filters_display'), 'no', 'checked')}}>
                                <strong>{{__('message.no')}}</strong>
                            </div>                            
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>
                                    {{__('message.home_banner_text')}}
                                    <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                </label>
                                <textarea class="form-control" id="home-banner-text" name="home_banner_text">{!! setting('home_banner_text') !!}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            @php 
                                $orders = setting('home_display_order');
                                $orders = json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $orders), true );
                                $home_highlights_section_order = issetVal($orders, 'home_highlights_section');
                                $home_departments_section_order = issetVal($orders, 'home_departments_section');
                                $home_companies_section_order = issetVal($orders, 'home_companies_section');
                                $home_jobs_section_order = issetVal($orders, 'home_jobs_section');
                                $home_candidates_section_order = issetVal($orders, 'home_candidates_section');
                                $home_guide_section_order = issetVal($orders, 'home_guide_section');
                                $home_make_account_section_order = issetVal($orders, 'home_make_account_section');
                                $home_pricing_section_order = issetVal($orders, 'home_pricing_section');
                                $home_testimonials_section_order = issetVal($orders, 'home_testimonials_section');
                                $home_features_section_order = issetVal($orders, 'home_features_section');
                                $home_news_section_order = issetVal($orders, 'home_news_section');
                            @endphp
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Status</th>
                                        <th>Display Order</th>
                                        <th>Sort Order</th>
                                        <th>Limit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_highlights_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_highlights_section" value="enabled" 
                                            {{sel(setting('home_highlights_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_highlights_section" value="disabled"
                                            {{sel(setting('home_highlights_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_highlights_section]" 
                                            value="{{$home_highlights_section_order}}">
                                        </td>
                                        <td>---</td>
                                        <td>---</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_departments_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_departments_section" value="enabled" 
                                            {{sel(setting('home_departments_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_departments_section" value="disabled"
                                            {{sel(setting('home_departments_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_departments_section]" 
                                            value="{{$home_departments_section_order}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="text" class="form-control" name="home_departments_section_sort_order" 
                                            value="{{setting('home_departments_section_sort_order')}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_departments_section_limit" 
                                            value="{{setting('home_departments_section_limit')}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_companies_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_companies_section" value="enabled" 
                                            {{sel(setting('home_companies_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_companies_section" value="disabled"
                                            {{sel(setting('home_companies_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_companies_section]" 
                                            value="{{$home_companies_section_order}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="text" class="form-control" name="home_companies_section_sort_order" 
                                            value="{{setting('home_companies_section_sort_order')}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_companies_section_limit" 
                                            value="{{setting('home_companies_section_limit')}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_jobs_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_jobs_section" value="enabled" 
                                            {{sel(setting('home_jobs_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_jobs_section" value="disabled"
                                            {{sel(setting('home_jobs_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_jobs_section]" 
                                            value="{{$home_jobs_section_order}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="text" class="form-control" name="home_jobs_section_sort_order" 
                                            value="{{setting('home_jobs_section_sort_order')}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_jobs_section_limit" 
                                            value="{{setting('home_jobs_section_limit')}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_candidates_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_candidates_section" value="enabled" 
                                            {{sel(setting('home_candidates_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_candidates_section" value="disabled"
                                            {{sel(setting('home_candidates_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_candidates_section]" 
                                            value="{{$home_candidates_section_order}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="text" class="form-control" name="home_candidates_section_sort_order" 
                                            value="{{setting('home_candidates_section_sort_order')}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_candidates_section_limit" 
                                            value="{{setting('home_candidates_section_limit')}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_guide_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_guide_section" value="enabled" 
                                            {{sel(setting('home_guide_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_guide_section" value="disabled"
                                            {{sel(setting('home_guide_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_guide_section]" 
                                            value="{{$home_guide_section_order}}">
                                        </td>
                                        <td>---</td>
                                        <td>---</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_make_account_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_make_account_section" value="enabled" 
                                            {{sel(setting('home_make_account_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_make_account_section" value="disabled"
                                            {{sel(setting('home_make_account_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_make_account_section]" 
                                            value="{{$home_make_account_section_order}}">
                                        </td>
                                        <td>---</td>
                                        <td>---</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_pricing_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_pricing_section" value="enabled" 
                                            {{sel(setting('home_pricing_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_pricing_section" value="disabled"
                                            {{sel(setting('home_pricing_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_pricing_section]" 
                                            value="{{$home_pricing_section_order}}">
                                        </td>
                                        <td>---</td>
                                        <td>---</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_testimonials_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_testimonials_section" value="enabled" 
                                            {{sel(setting('home_testimonials_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_testimonials_section" value="disabled"
                                            {{sel(setting('home_testimonials_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_testimonials_section]" 
                                            value="{{$home_testimonials_section_order}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="text" class="form-control" name="home_testimonials_section_sort_order" 
                                            value="{{setting('home_testimonials_section_sort_order')}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_testimonials_section_limit" 
                                            value="{{setting('home_testimonials_section_limit')}}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_features_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_features_section" value="enabled" 
                                            {{sel(setting('home_features_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_features_section" value="disabled"
                                            {{sel(setting('home_features_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_features_section]" 
                                            value="{{$home_features_section_order}}">
                                        </td>
                                        <td>---</td>
                                        <td>---</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label>{{__('message.home_news_section')}}</label><br />
                                        </td>
                                        <td>
                                            <input type="radio" class="minimal" name="home_news_section" value="enabled" 
                                            {{sel(setting('home_news_section'), 'enabled', 'checked')}}>
                                            <strong>{{__('message.enabled')}}</strong>&nbsp;&nbsp;&nbsp;
                                            <input type="radio" class="minimal" name="home_news_section" value="disabled"
                                            {{sel(setting('home_news_section'), 'disabled', 'checked')}}>
                                            <strong>{{__('message.disabled')}}</strong> 
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_display_order[home_news_section]" 
                                            value="{{$home_news_section_order}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="text" class="form-control" name="home_news_section_sort_order" 
                                            value="{{setting('home_news_section_sort_order')}}">
                                        </td>
                                        <td width="150px;">
                                            <input type="number" class="form-control" name="home_news_section_limit" 
                                            value="{{setting('home_news_section_limit')}}">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>                     
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="admin_home_form_button">
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