@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-briefcase"></i> {{ __('message.company') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/employer/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fa fa-briefcase"></i> {{ __('message.company') }}</li>
            <li class="active">{{ __('message.import') }}</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            @if(empAllowedTo('create_companys') || empAllowedTo('edit_companys'))
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('message.details') }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="employer_company_import_form" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.import') }} {{ __('message.file') }}</label>
                                        <input type="file" class="form-control"  name="import_file" accept=".csv, .xls, .xlsx, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                                        <a href="{{url('e-assets')}}/excel/sample_companys.csv" download><b>{{ __('message.download_sample_import_excel_file') }}</b></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="employer_company_import_form_button">
                            {{ __('message.save') }}
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            @endif
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/company.js"></script>
@endsection