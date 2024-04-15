@extends('front.beta.layouts.master')

@section('breadcrumb')
@include('front.beta.partials.breadcrumb')
@endsection

@section('content')

<!-- Account Section Starts -->
<div class="section-account-alpha-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="section-account-alpha-navigation">
                    @include('front.beta.partials.account-sidebar')
                </div>
            </div>
            <div class="col-md-9">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <!-- Membership List Table Starts -->
                        <div class="table-responsive">
                            <table class="table section-account-alpha-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
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
                                    @if($memberships)
                                    @foreach ($memberships as $key => $membership)
                                    @php $id = encode($membership['candidate_membership_id']); @endphp
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td title="{{ $membership['title'] }}">{{ trimString($membership['title'], 23) }}</td>
                                        <td>{{ $membership['title'] }}</td>
                                        <td>{{ $membership['payment_type'] }}</td>
                                        <td>{{ $membership['package_type'] }}</td>
                                        <td>{{ $membership['payment_currency'].$membership['price_paid'] }}</td>
                                        <td>{{ $membership['status'] == '1' ? __('message.active') : __('message.inactive') }}</td>
                                        <td>{{ dateFormat($membership['expiry']) }}</td>
                                        <td>{{ dateFormat($membership['created_at']) }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="9">
                                            <p>{{ __('message.no_record_found') }}</p>
                                        </td>
                                    </tr>
                                    @endif                                
                                </tbody>
                            </table>
                        </div>
                        <!-- Membership List Table Ends -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm renew-membership" title="{{__('message.add_new')}}">
                        <i class="fa fa-plus"></i>
                        </button>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Account Section Ends -->

<div id="modal-beta" class="modal-beta modal-beta-large modal fade modal-resume-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-body-container">
        </div>
    </div>
</div>

<form id="paystack_payment_form"></form>
<form id="razorpay_payment_form"></form>

@endsection
@section('page-scripts')
<script src="https://js.stripe.com/v2/"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
@endsection