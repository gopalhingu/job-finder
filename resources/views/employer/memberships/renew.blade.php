<div class="box-body">
    <div class="box-group" id="accordion">
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        @php $colors = array('success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary', 'success', 'info', 'warning', 'danger', 'primary'); @endphp
        <strong>{{__('message.notes')}} : {{__('message.renew_will_deactivate_any_existing_membership')}}</strong><br /><br />
       	@foreach($packages as $key => $package)
        <div class="panel box box-{{$colors[$key]}}">
            <div class="box-header with-border">
                <h4 class="box-title em-box-title">
                	@php
                		$monthly = $package['currency'].$package['monthly_price'].'/'.__('message.month');
                		$yearly = $package['currency'].$package['yearly_price'].'/'.__('message.year');
                        $se = $key == 0 ? 'checked' : '';
                        $mp = encode(employerId().'-'.encode($package['package_id']).'-monthly');
                        $yp = encode(employerId().'-'.encode($package['package_id']).'-yearly');
                	@endphp                    
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}" 
                    aria-expanded="false" class="collapsed">
                    {{$package['title']}} 
                    </a>
                </h4>
                <span class="em-both-title em-title {{$key}}-month renew-package" data-key="{{$key}}-month-key">
                    {{$monthly}}
                </span> 
                <span class="em-both-title em-title {{$key}}-year renew-package" data-key="{{$key}}-year-key">
                    {{$yearly}}
                </span>
            </div>
            <div id="collapse{{$key}}" class="panel-collapse collapse" aria-expanded="false">
                <div class="box-body">
                    <ul class="nav nav-stacked">
                        <li><a href="#">
                            {{packageItem(__('message.active_jobs'), $package['active_jobs'])}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['active_jobs'])}}
                            "></i> 
                        </a></li>
                        <li><a href="#">
                            {{packageItem(__('message.active_users'), $package['active_users'])}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['active_users'])}}
                            "></i> 
                        </a></li>
                        <li><a href="#">
                            {{packageItem(__('message.active_custom_filters'), $package['active_custom_filters'])}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['active_custom_filters'])}}
                            "></i> 
                        </a></li>
                        <li><a href="#">
                            {{packageItem(__('message.active_quizes'), $package['active_quizes'])}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['active_quizes'])}}
                            "></i> 
                        </a></li>
                        <li><a href="#">
                            {{packageItem(__('message.active_interviews'), $package['active_interviews'])}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['active_interviews'])}}
                            "></i> 
                        </a></li>
                        <li><a href="#">
                            {{packageItem(__('message.active_traites'), $package['active_traites'])}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['active_traites'])}}
                            "></i> 
                        </a></li>
                        @if(setting('enable_separate_employer_site') == 'only_for_employers_with_separate_site')
                        <li><a href="#">
                            {{packageItem(__('message.separate_site'), $package['separate_site'], true)}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['separate_site'], true)}}
                            "></i> 
                        </a></li>
                        @endif
                        <li><a href="#">
                            {{packageItem(__('message.branding'), $package['branding'], true)}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['branding'], true)}}
                            "></i> 
                        </a></li>
                        <li><a href="#">
                            {{packageItem(__('message.role_permissions'), $package['role_permissions'], true)}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['role_permissions'], true)}}
                            "></i> 
                        </a></li>
                        <li><a href="#">
                            {{packageItem(__('message.custom_emails'), $package['custom_emails'], true)}} 
                            <i class="pull-right apb {{packageItemBulletEmp($package['custom_emails'], true)}}
                            "></i> 
                        </a></li>
                    </ul>
                    <input type="radio" 
                        class="minimal membership-radio {{$key}}-year-key" 
                        data-key="{{$key}}-year"
                        data-price="{{$package['yearly_price']}}"
                        data-title="{{$package['title']}}"
                        name="selected_package" 
                        value="{{$yp}}" /> {{__('message.yearly')}}
                    &nbsp;&nbsp;
                    <input type="radio" 
                        class="minimal membership-radio {{$key}}-month-key" 
                        data-key="{{$key}}-month"
                        data-price="{{$package['monthly_price']}}"
                        data-title="{{$package['title']}}"
                        name="selected_package" 
                        value="{{$mp}}" {{$se}} /> {{__('message.monthly')}}
                </div>
            </div>
        </div>
        @endforeach
        <hr />
        @if(setting('enable_offline_payment') == 'yes')
        <div class="row">
            <div class="col-sm-12 text-center">
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
                            <input type="file" name="file">
                        </div>
                    </div>
                    @endif
                    <div class="col-md-12 text-left">
                        <div class="form-group">
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
        <form id="stripe_payment_form" enctype="multipart/form-data">
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
                <input type="hidden" id="paystack-email" value="{{employerSession('email')}}">
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