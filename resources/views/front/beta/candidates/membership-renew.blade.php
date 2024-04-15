@php $colors = array('success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary'); @endphp
<div class="modal-header p-0">
    <h4 class="modal-title">{{ __('message.renew_membership') }}</h4>
    <button type="button" class="close close-modal" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<div class="modal-body">
    <strong>{{__('message.notes')}} : {{__('message.renew_will_deactivate_any_existing_membership')}}</strong><br /><br />
    <div class="accordion" id="accordionExample">
        @foreach($packages as $key => $package)

        <div class="accordion-item">
            <h2 class="accordion-header" id="heading{{$key}}">
                <button class="accordion-button" type="button">
                    @php
                    $monthly = $package['currency'].$package['monthly_price'].'/'.__('message.month');
                    $yearly = $package['currency'].$package['yearly_price'].'/'.__('message.year');
                    $se = $key == 0 ? 'checked="checked"' : '';
                    $mp = encode(encode($package['candidate_package_id']).'-monthly');
                    $yp = encode(encode($package['candidate_package_id']).'-yearly');
                    @endphp
                    <span class="em-both-title em-title {{$key}}-month renew-package" data-key="{{$key}}-month-key">
                    {{$monthly}}
                    </span> 
                    <span class="em-both-title em-title {{$key}}-year renew-package" data-key="{{$key}}-year-key">
                    {{$yearly}}
                    </span>
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}" 
                        aria-expanded="false" class="collapsed" data-bs-toggle="collapse" data-bs-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}" title="{{__('message.explore')}}">
                    {{$package['title']}} 
                    </a>
                </button>
            </h2>
            <div id="collapse{{$key}}" class="accordion-collapse collapse" aria-labelledby="heading{{$key}}" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <ul class="nav nav-stacked">
                        <li>
                            {{packageItem(__('message.allowed_job_applications'), $package['allowed_job_applications'])}} 
                            <i class="pull-right apb {{packageItemBullet($package['allowed_job_applications'])}}
                                "></i> 
                        </li>
                        <li>
                            {{packageItem(__('message.allowed_resumes'), $package['allowed_resumes'])}} 
                            <i class="pull-right apb {{packageItemBullet($package['allowed_resumes'])}}
                                "></i> 
                        </li>
                        <li>
                            {{ __('message.show_hide_personal_info') }} 
                            <i class="pull-right apb {{packageItemBullet($package['show_hide_personal_info'], true)}}
                                "></i> 
                        </li>
                    </ul>
                    <input type="radio" 
                    id="{{$key}}-month-key"
                    class="membership-radio {{$key}}-month-key" 
                    data-key="{{$key}}-month"
                    data-price="{{$package['monthly_price']}}"
                    data-title="{{$package['title']}}"
                    name="selected_package" 
                    value="{{$mp}}" {!! $se !!} /> {{__('message.monthly')}}
                    &nbsp;&nbsp;
                    <input type="radio" 
                        id="{{$key}}-year-key"
                        class="membership-radio {{$key}}-year-key" 
                        data-key="{{$key}}-year"
                        data-price="{{$package['yearly_price']}}"
                        data-title="{{$package['title']}}"
                        name="selected_package" 
                        value="{{$yp}}" /> {{__('message.yearly')}}
                </div>
            </div>
        </div>
        @endforeach
        <hr />
        @if(setting('enable_offline_payment') == 'yes')
        <div class="row">
            <div class="col-sm-12 text-center offline-payment-form-container">
                <form id="offline_payment_form" enctype="multipart/form-data">
                <span class="payment-form-values">{{ setting('offline_payment_title') }} </span><br /><br />
                <p align="left"><strong>{!! setting('offline_payment_text') !!}</strong></p>
                <div class="row">
                    <div class="col-sm-12 text-left">
                        <div class="form-group">
                            <label class="control-label">{{ __('message.message') }} </label>
                            <textarea name="message" class="form-control"></textarea>
                        </div>
                    </div>
                    @if(setting('enable_offline_payment_attachment') != 'no')
                    <div class="col-sm-12">
                        <div class="form-group text-left">
                            <label class="control-label">{{ __('message.file') }} </label>
                            <input class="form-control" type="file" name="file">
                        </div>
                    </div>
                    @endif
                    <div class="col-md-12 text-left">
                        <div class="">
                            <button class="btn btn-primary" id="offline_payment_form_button">{{ __('message.submit') }} </button>
                        </div>
                    </div>
                </div>
                <div class="razorpay-success-container"></div>
                </form>
            </div>
        </div>
        <hr />
        @endif        
        @if(setting('enable_stripe') == 'yes')
        <Br />
        <div class="row">
        <div class="col-sm-12 text-center">
        <span class="payment-form-values">{{ __('message.pay_with_card') }}</span><br /><br />
        </div>
        </div>
        <form id="stripe_payment_form" enctype="multipart/form-data" class="stripe-payment-form-container">
            <input type="hidden" id="stripe_key" name="stripe_key" value="{{setting('stripe_publisher_key')}}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label">{{ __('message.card_number') }}</label>
                        <input type="text" id="card_number" name="card_number" class="form-control">
                    </div>
                </div>
            </div>
            <div></div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">{{ __('message.expiry_month') }} </label>
                        <select name="month" id="month" class="form-control">
                            <option value="01">01</option>
                            <option value="02">02</option>
                            <option value="03">03</option>
                            <option value="04">04</option>
                            <option value="05">05</option>
                            <option value="06">06</option>
                            <option value="07">07</option>
                            <option value="08">08</option>
                            <option value="09">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">{{ __('message.expiry_year') }} </label>
                        <select name="year" id="year" class="form-control">
                            {!! getNextFiveYears() !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label">{{ __('message.cvc') }} </label>
                        <input type="text" id="cvc" name="cvc" class="demoInputBox form-control" value="123">
                    </div>
                </div>        
            </div>
            <div></div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button class="btn btn-primary" id="stripe_payment_button">{{ __('message.submit') }} </button>
                    </div>
                </div>
            </div>
        </form>
        <hr />
        @endif
        @if(setting('enable_razorpay') == 'yes')
        <div class="row">
            <div class="col-sm-12 text-center">
                <span class="payment-form-values">{{ __('message.pay_with') }} </span><br /><br />
                <input type="hidden" id="razorpay-token" value="{{token()}}">
                <a class="pay-with-razorpay" href="#">
                    <img src="{{ url('/e-assets') }}/img/razorpay.png" />
                </a>
                <div class="razorpay-success-container"></div>
            </div>
        </div>
        <hr />
        @endif
        @if(setting('enable_paystack') == 'yes')
        <div class="row">
            <div class="col-sm-12 text-center">
                <span class="payment-form-values">{{ __('message.pay_with') }} </span><br /><br />
                <input type="hidden" id="paystack-key" value="{{setting('paystack_public_key')}}">
                <input type="hidden" id="paystack-email" value="{{candidateSession('email')}}">
                <a class="pay-with-paystack" href="#">
                    <img src="{{ url('/e-assets') }}/img/paystack.png" />
                </a>
                <div class="paystack-success-container"></div>
            </div>
        </div>
        <hr />
        @endif
        @if(setting('enable_paypal') == 'yes')
        <div class="row">
            <div class="col-sm-12 text-center">
                <span class="payment-form-values">{{ __('message.pay_with') }} </span><br /><br />
                <a class="paypal-link" href="#">
                    <img src="{{ url('/e-assets') }}/img/paypal.png" />
                </a>
            </div>
        </div>
        <hr />
        @endif        
    </div>
</div>