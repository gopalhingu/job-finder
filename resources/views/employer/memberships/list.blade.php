@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-id-card-alt"></i> {{ __('message.memberships') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/employer/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fas fa-id-card-alt"></i> {{ __('message.memberships') }}</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="datatable-top-controls datatable-top-controls-filter">
                                    @if(empAllowedTo('create_memberships'))
                                    <button type="button" class="btn btn-primary btn-blue btn-flat renew-membership">
                                    <i class="fa fa-plus"></i> {{ __('message.renew_membership') }}
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(empAllowedTo('view_memberships'))
                        <table class="table table-bordered table-striped" id="memberships_datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('message.package_title') }}</th>
                                    <th>{{ __('message.title') }}</th>
                                    <th>{{ __('message.payment_type') }}</th>
                                    <th>{{ __('message.package_type') }}</th>
                                    <th>{{ __('message.price_paid') }}</th>
                                    <th>{{ __('message.status') }}</th>
                                    <th>{{ __('message.expiry') }}</th>
                                    <th>{{ __('message.renewd_on') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- page script -->
@endsection
@section('page-scripts')
<script src="https://js.stripe.com/v2/"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="{{url('e-assets')}}/js/cf/membership.js"></script>
@endsection