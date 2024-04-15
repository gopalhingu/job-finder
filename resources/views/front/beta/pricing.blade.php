@extends('front.beta.layouts.master')

@section('breadcrumb')
@include('front.beta.partials.breadcrumb')
@endsection

@section('content')

@if($packages)
<!-- Page Pricing Section Starts -->
<div class="section-pricing">
    <div class="container">
        <div class="position-relative"><div class="section-pricing-pattern pattern-lines rounded-circle"></div></div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="section-heading-style-gamma">
                    <div class="section-heading">
                        <h2>{{__('message.pricing')}}</h2>
                    </div>
                    <div class="section-intro-text">
                        <p>{{__('message.home_pricing_msg')}}</p>
                    </div>
                    <div class="section-intro-button">
                        <div class="section-pricing-switch">
                            <div class="section-pricing-switch-labels">{{__('message.monthly')}}</div>
                            <label class="switch">
                                <input type="checkbox">
                                <span class="section-pricing-switch-handle slider round" data-value="monthly"></span>
                            </label>
                            <div class="section-pricing-switch-labels">{{__('message.yearly')}}</div>
                        </div>

                    </div>
                </div>              
            </div>
        </div>
        <div class="row">
            @foreach($packages as $package)
            <div class="col-lg-4 col-md-12 col-sm-12 p-0">
                <div class="section-pricing-item simple-shadow {{$package['is_top_sale'] == 1 ? 'special' : ''}}">
                    <div class="section-pricing-item-icon">
                        @php $thumb = generalThumb($package['image']); @endphp
                        <img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
                    </div>
                    <div class="section-pricing-item-title">
                        <h2>{{$package['title']}}</h2>
                    </div>
                    <div class="section-pricing-item-price">
                        <span class="section-pricing-item-price-amount section-pricing-item-price-amount-monthly">
                            {{$package['currency']}}{{$package['monthly_price']}}
                        </span>
                        <span class="section-pricing-item-price-amount section-pricing-item-price-amount-yearly">
                            {{$package['currency']}}{{$package['yearly_price']}}
                        </span>
                        <span class="section-pricing-item-price-duration">/ mo</span>
                    </div>
                    <div class="section-pricing-item-list">
                        <ul>
                            <li>
                                <i class="{{packageItemBullet($package['active_jobs'])}}"></i> {{packageItem(__('message.active_jobs'), $package['active_jobs'])}} 
                            </li>
                            <li>
                                <i class="{{packageItemBullet($package['active_users'])}}"></i> {{packageItem(__('message.active_users'), $package['active_users'])}} 
                            </li>
                            <li>
                                <i class="{{packageItemBullet($package['active_custom_filters'])}}"></i> {{packageItem(__('message.active_custom_filters'), $package['active_custom_filters'])}} 
                            </li>
                            <li>
                                <i class="{{packageItemBullet($package['active_quizes'])}}"></i> {{packageItem(__('message.active_quizes'), $package['active_quizes'])}} 
                            </li>
                            <li>
                                <i class="{{packageItemBullet($package['active_interviews'])}}"></i> {{packageItem(__('message.active_interviews'), $package['active_interviews'])}} 
                            </li>
                            <li>
                                <i class="{{packageItemBullet($package['active_traites'])}}"></i> {{packageItem(__('message.active_traites'), $package['active_traites'])}} 
                            </li>
                            @if(setting('enable_separate_employer_site') == 'only_for_employers_with_separate_site')
                            <li>
                                <i class="{{packageItemBullet($package['separate_site'], true)}}"></i> {{packageItem(__('message.separate_site'), $package['separate_site'], true)}} 
                            </li>
                            @endif
                            <li>
                                <i class="{{packageItemBullet($package['branding'], true)}}"></i> {{packageItem(__('message.branding'), $package['branding'], true)}} 
                            </li>
                            <li>
                                <i class="{{packageItemBullet($package['role_permissions'], true)}}"></i> {{packageItem(__('message.role_permissions'), $package['role_permissions'], true)}} 
                            </li>
                            <li>
                                <i class="{{packageItemBullet($package['custom_emails'], true)}}"></i> {{packageItem(__('message.custom_emails'), $package['custom_emails'], true)}} 
                            </li>
                        </ul>
                    </div>
                    <div class="section-pricing-item-btn">
                        @if(employerSession())
                        <a class="btn btn-primary" href="{{route('employer-dashboard')}}">
                            {{__('message.dashboard')}}
                        </a>
                        @elseif(candidateSession())
                        <a class="btn btn-primary global-register-btn" href="#">
                            {{__('message.sign_up_as_employer')}}
                        </a>
                        @else
                        <a class="btn btn-primary global-register-btn">
                            {{__('message.register')}}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Page Pricing Section Starts -->
@else
<div class="section-pricing">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <p>{{__('message.no_record_found')}}</p>
            </div>
        </div>
    </div>
</div>
@endif

@if($faqs)
<!-- Section FAQS Starts -->
<div class="section-faqs-alpha">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="section-heading-style-alpha">
                    <div class="section-heading">
                        <h2>{{__('message.faqs')}}</h2>
                    </div>
                    <div class="section-intro-text">
                        <p>{{__('message.faqs_msg')}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12">
                @foreach($faqs as $key => $faq)
                <div class="section-faqs-alpha-item">
                    <div class="accordion simple-shadow">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button shadow-none border-none collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#collapse{{$key}}" aria-expanded="false">
                                {{$faq['question']}}
                                </button>
                            </h2>
                            <div id="collapse{{$key}}" class="accordion-collapse collapse hide">
                                <div class="accordion-body">
                                    {{$faq['answer']}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- Section FAQS Ends -->
@endif

@endsection

