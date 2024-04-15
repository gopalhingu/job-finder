@if($packages)
<!-- Home Pricing Section Starts -->
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
			<!-- Home Package Single Item Starts -->
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
			<!-- Home Package Single Item Ends -->
			@endforeach
		</div>
	</div>
</div>
<!-- Home Pricing Section Starts -->
@endif