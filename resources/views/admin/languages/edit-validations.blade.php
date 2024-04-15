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
                    <h1 class="m-0">{{__('message.languages')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item"><a href="#">{{__('message.settings')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.languages')}}</li>
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
    <section class="content">
        <div class="container-fluid">
            <div class="card card-default">
                <div class="card-header">
                    <h3 class="card-title">{{__('message.details')}}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                <form id="admin_language_validations_update_form" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>{{__('message.language_title')}}</label>
                                <input type="hidden" name="language_id" value="{{ $language['language_id'] }}" />
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Enter Title" 
                                    name="language_title" 
                                    value="{{ $language['title'] }}" 
                                    readonly="readonly"
                                />
                            </div>
                        </div>
                        @php $count = 0; @endphp
                        @foreach($default as $key => $d)
                        <div class="col-md-{{$count > 847 ? '12' : '6'}}">
                            <div class="form-group">
                                <label>{!! $d !!} ({{ $key }})</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    placeholder="Enter Title" 
                                    name="{{ $key }}" 
                                    value="{!! $entries[$key] !!}"
                                />
                            </div>
                        </div>
                        @php $count++; @endphp
                        @endforeach
                    </div>
                    <!-- /.row -->
                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary" id="admin_language_validations_update_form_button">
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
            <!-- /.card -->
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('/a-assets')}}/js/cf/language.js"></script>
@endsection