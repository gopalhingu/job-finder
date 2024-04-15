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
                    <h1 class="m-0">{{__('message.candidate').' '.__('message.memberships')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item active">{{__('message.candidate').' '.__('message.memberships')}}</li>
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
                            <h3 class="card-title">{{__('message.candidate').' '.__('message.memberships').' '.__('message.list')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            {{__('message.notes')}} : <Br />
                            <ol>
                                <li>{{__('message.will_deactivate_all_previos_creation_and_activation_2')}}</li>
                                <li>{{__('message.transaction_ids_will_be_auto_generated')}}</li>
                            </ol>
                            <div class="row dt-controls-row">
                                <div class="col-md-12">
                                    <div class="datatable-top-controls datatable-top-controls-filter">
                                        @if(allowedTo('create_candidate_membership'))
                                        <button type="button" class="btn btn-info create-or-edit-candidate-membership">
                                        <i class="fa fa-plus"></i> {{ __('message.create_candidate_membership') }}
                                        </button>
                                        @endif
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default">{{ __('message.actions') }}</button>
                                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item bulk-action" href="#" data-action="download-excel">{{ __('message.download_candidate_memberships_excel') }}</a>
                                            </div>
                                        </div>                                        
                                    </div>
                                    <br /><br />
                                    <div class="datatable-top-controls datatable-top-controls-dd">
                                        <div class="input-group">
                                            <select class="form-control select2" id="candidate_package_id">
                                                <option value="">{{ __('message.all') }}</option>
                                                @foreach ($candidate_packages as $key => $value)
                                                <option value="{{ esc_output($value['candidate_package_id']) }}">{{ esc_output($value['title']) }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-info">
                                                    <i class="fa fa-filter"></i> 
                                                    {{ __('message.candidate_packages') }}
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="datatable-top-controls datatable-top-controls-dd">
                                        <div class="input-group">
                                            <select class="form-control select2" id="candidate_id">
                                                <option value="">{{ __('message.all') }}</option>
                                                @foreach ($candidates as $key => $value)
                                                <option value="{{ esc_output($value['candidate_id']) }}">{{ esc_output($value['first_name'].' '.$value['last_name']) }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-info">
                                                    <i class="fa fa-filter"></i> 
                                                    {{ __('message.candidates') }}
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="datatable-top-controls datatable-top-controls-dd">
                                        <div class="input-group">
                                            <select class="form-control select2" id="payment_type">
                                                <option value="">{{ __('message.all') }}</option>
                                                @foreach ($payment_types as $key => $value)
                                                <option value="{{ $value }}">{{ ucwords($value) }}</option>
                                                @endforeach
                                            </select>
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-info">
                                                    <i class="fa fa-filter"></i> 
                                                    {{ __('message.payment_type') }}
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="datatable-top-controls datatable-top-controls-dd">
                                        <div class="input-group">
                                            <select class="form-control select2" id="package_type">
                                                <option value="">{{ __('message.all') }}</option>
                                                @foreach ($package_types as $key => $value)
                                                <option value="{{ $value }}">{{ ucwords($value) }}</option>
                                                @endforeach                                                
                                            </select>
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-info">
                                                    <i class="fa fa-filter"></i> 
                                                    {{ __('message.package_type') }}
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="datatable-top-controls datatable-top-controls-dd">
                                        <div class="input-group">
                                            <select class="form-control select2" id="status">
                                                <option value="">{{ __('message.all') }}</option>
                                                <option value="1">{{ __('message.active') }}</option>
                                                <option value="0">{{ __('message.inactive') }}</option>
                                            </select>
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-info">
                                                    <i class="fa fa-filter"></i> 
                                                    {{ __('message.status') }}
                                                </button>
                                            </span>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            @if(allowedTo('view_candidate_memberships'))
                            <table class="table table-bordered table-striped" id="candidate_memberships_datatable">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" class="minimal all-check"></th>
                                        <th>{{ __('message.candidate') }}</th>
                                        <th>{{ __('message.package_title') }}</th>
                                        <th>{{ __('message.title') }}</th>
                                        <th>{{ __('message.payment_type') }}</th>
                                        <th>{{ __('message.package_type') }}</th>
                                        <th>{{ __('message.price_paid') }}</th>
                                        <th>{{ __('message.expiry') }}</th>
                                        <th>{{ __('message.created_on') }}</th>
                                        <th>{{ __('message.status') }}</th>
                                        <th>{{ __('message.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            @endif
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
<form id="candidate-memberships-form" method="POST" action="{{url(route('admin-candidate-memberships-excel'))}}" target='_blank'></form>

@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/candidate_membership.js"></script>
@endsection