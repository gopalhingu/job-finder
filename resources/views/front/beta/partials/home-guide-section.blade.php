<!-- Home Account Guide Section Starts -->
<div class="section-make-account-alpha">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="section-heading-style-alpha">
					<div class="section-heading">
						<h2>{{__('message.home_guide_title_msg')}}</h2>
					</div>
					<div class="section-intro-text">
						<p>{{__('message.home_guide_msg')}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="section-make-account-alpha-item">
					<div class="section-make-account-alpha-item-separator-icon">
						<span class="section-make-account-alpha-item-separator-icon-text">{{__('message.or')}}</span>
					</div>
					<div class="row">
						<div class="col-lg-6 col-md-12 col-sm-12">
							<div class="section-make-account-alpha-item-left">
								<div class="section-make-account-alpha-item-left-image">
									@php $error = url('/g-assets').'/essentials/images/general-not-found.png'; @endphp
									<img src="{{route('uploads-view', setting('general_employers'))}}" onerror="this.src='{{$error}}'" />
								</div>
								<div class="section-make-account-alpha-item-left-heading">
									<h2>{{__('message.home_guide_employer_title_msg')}}</h2>
								</div>
								<div class="section-make-account-alpha-item-left-content">
									<p>{{__('message.home_guide_employer_desc_msg')}}</p>
								</div>
								<div class="section-make-account-alpha-item-left-button">
									@if(candidateSession())
										<a class="btn" href="#">{{__('message.sign_up_as_employer')}}</a>
									@elseif(employerSession())
										<a class="btn" href="{{route('employer-dashboard')}}">{{__('message.account')}}</a>
									@else
										<a class="btn" href="{{route('pricing')}}">{{__('message.see_pricing')}}</a>
									@endif
								</div>
							</div>
						</div>
						<div class="col-lg-6 col-md-12 col-sm-12">
							<div class="section-make-account-alpha-item-right">
								<div class="section-make-account-alpha-item-right-image">
									@php $error = url('/g-assets').'/essentials/images/general-not-found.png'; @endphp
									<img src="{{route('uploads-view', setting('general_candidates'))}}" onerror="this.src='{{$error}}'" />
								</div>
								<div class="section-make-account-alpha-item-right-heading">
									<h2>{{__('message.home_guide_candidate_title_msg')}}</h2>
								</div>
								<div class="section-make-account-alpha-item-right-content">
									<p>{{__('message.home_guide_candidate_desc_msg')}}</p>
								</div>
								<div class="section-make-account-alpha-item-right-button">
									@if(candidateSession())
										<a class="btn" href="{{route('can-acc-main', '')}}">{{__('message.account')}}</a>
									@elseif(employerSession())
										<a class="btn" href="#">{{__('message.sign_up_as_candidate')}}</a>
									@else
										<a class="btn global-login-btn">{{__('message.signup_to_apply')}}</a>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Home Account Guide Section Ends -->