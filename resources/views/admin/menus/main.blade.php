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
                    <h1 class="m-0">{{__('message.menu')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.menu')}}</li>
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
                    <h3 class="card-title">{{__('message.edit_menu')}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-12" id="message-container">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <h5 class="mt-4 mb-2">
                                        {{__('message.alignment')}} 
                                    </h5>
                                    <div class="input-group input-group-sm">
                                        <select class="form-control select2" id="alignment-dropdown">
                                            <option value="left">{{__('message.left')}}</option>
                                            <option value="middle">{{__('message.middle')}}</option>
                                            <option value="right">{{__('message.right')}}</option>
                                        </select>
                                    </div>
                                    <!-- /input-group -->
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="mt-4 mb-2">
                                                {{__('message.select_to_add')}} 
                                            </h5>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control select2" id="menu-dropdown">
                                                    <option value="home_main">{{__('message.home_main')}}</option>
                                                    <option value="features">{{__('message.features')}}</option>
                                                    <option value="pricing">{{__('message.pricing')}}</option>
                                                    <option value="news">{{__('message.news')}}</option>
                                                    <option value="contact">{{__('message.contact')}}</option>
                                                    <option value="faqs">{{__('message.faqs')}}</option>
                                                    <option value="all_companies_page">{{__('message.all_companies_page')}}</option>
                                                    <option value="all_candidates_page">{{__('message.all_candidates_page')}}</option>
                                                    <option value="all_news_page">{{__('message.all_news_page')}}</option>
                                                    <option value="all_jobs_page">{{__('message.all_jobs_page')}}</option>
                                                    <option value="register_button">{{__('message.register_button')}}</option>
                                                    <option value="language_button">{{__('message.language_button')}}</option>
                                                    <option value="login_button">{{__('message.login_button')}}</option>
                                                    <option value="dark_mode_button">{{__('message.dark_mode_button')}}</option>
                                                    <option value="select_page">{{__('message.select_page_from_list')}}</option>
                                                    <option value="select_news">{{__('message.select_news_from_list')}}</option>
                                                    <option value="static_external">{{__('message.static_external')}}</option>
                                                </select>
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                        <div class="col-12 submenu-dropdown-container">
                                            <h5 class="mt-4 mb-2">
                                                {{__('message.sub_item')}} 
                                            </h5>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control select2" id="submenu-dropdown">
                                                </select>
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                        <div class="col-12 static-link-container">
                                            <h5 class="mt-4 mb-2">
                                                {{__('message.title')}} & {{__('message.link')}} 
                                                <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 
                                                title="{{__('message.static_link_message')}}"></i>
                                            </h5>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="static-external-title" placeholder="{{__('message.enter_title')}} e.g. Explore" />
                                            </div>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control" id="static-external-link" placeholder="{{__('message.enter')}} # or https://www.google.com" />
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                        <div class="col-12">
                                            <Br />
                                            <button type="button" class="btn btn-info btn-flat" id="add-to-menu">
                                                {{__('message.add_to_menu')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="mt-4 mb-2">
                                                {{__('message.select_to_delete')}} 
                                            </h5>
                                            <div class="input-group input-group-sm">
                                                <select class="form-control select2" id="delete-dropdown">
                                                </select>
                                            </div>
                                            <!-- /input-group -->
                                        </div>
                                        <div class="col-12">
                                            <Br />
                                            <button type="button" class="btn btn-info btn-flat" id="delete-from-menu">
                                                {{__('message.delete_from_menu')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <h5 class="mt-4 mb-2">
                                    {{__('message.menu')}}
                                    - &nbsp;{{__('message.drag_drop_to_order')}}
                                </h5>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-12 text-center used-items-container" id="menu-list">
                                    </div>
                                </div>
                            </div>
                            <!-- /.form-group -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                </div>
            </div>
            <!-- /.card -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/menu.js"></script>
@endsection