<form action="" method="" id="front_login_form">
	@csrf
	@if(setting('front_login_type') == 'both')
	<div class="form-group">
		<div class="btn-group btn-login-type" role="group" aria-label="Basic radio toggle button group">
			<input type="radio" class="btn-check" name="type" id="btn-login-type1" value="candidate" autocomplete="off" checked>
			<label class="btn btn-login-type-label active" for="btn-login-type1">
				<i class="fa-solid fa-user-graduate"></i> {{__('message.as_candidate')}}
			</label>
			<input type="radio" class="btn-check" name="type" id="btn-login-type2" value="employer" autocomplete="off">
			<label class="btn btn-login-type-label" for="btn-login-type2">
				<i class="fa-solid fa-user-tie"></i> {{__('message.as_employer')}}
			</label>
			<input type="radio" class="btn-check" name="type" id="btn-login-type3" value="company" autocomplete="off">
			<label class="btn btn-login-type-label" for="btn-login-type3">
				<i class="fa-solid fa-building"></i> {{__('message.as_company')}}
			</label>
		</div>
	</div>
    @elseif(setting('front_login_type') == 'only_candidates')
    <input type="hidden" name="type" value="candidate"/>
    @else
    <input type="hidden" name="type" value="employer"/>
    @endif
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
			<input type="email" class="form-control shadow-none border-none" name="email" 
				placeholder="{{__('message.enter_email')}}" required="required">
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><i class="fa fa-lock"></i></span>
			<input type="password" class="form-control shadow-none border-none" name="password" 
				placeholder="{{__('message.enter_password')}}" required="required" autocomplete="on">
		</div>
	</div>
	<div class="row">
        <div class="col-md-6 text-left">
            <label>
                <input type="checkbox" class="" id="item_checkbox" name="item_checkbox" value="option1">
                <span class="remember-me">&nbsp;{{__('message.remember_me')}}</span>
            </label>
        </div>
        <div class="col-md-6 text-right">
            <a href="" class="forgot-password modal-forgot-password-btn">
            	{{__('message.forgot_password')}}?
            </a>
        </div>
        <div class="col-md-12">
			<button type="submit" class="btn btn-primary btn" id="front_login_form_button">
				{{__('message.login')}}
			</button>
        </div>
    </div>
    <div class="row">
    	<div class="col-md-12 or-login"><p>{{__('message.or_login_with')}}</p></div>
    </div>
    <div class="row">
    	<div class="col-md-12">
			<div class="social-login text-center">
				<input type="hidden" id="linkedin-link-candidate" value="{{$linkedin_login_link_candidate}}">
				<input type="hidden" id="linkedin-link-employer" value="{{$linkedin_login_link_employer}}">
				<input type="hidden" id="google-link-candidate" value="{{$google_login_link_candidate}}">
				<input type="hidden" id="google-link-employer" value="{{$google_login_link_employer}}">
				@php
					if (setting('front_login_type') == 'only_candidates' || setting('front_login_type') == 'both') {
						$google_link = $google_login_link_candidate;
						$linkedin_link = $linkedin_login_link_candidate;
					} else {
						$google_link = $google_login_link_employer;
						$linkedin_link = $linkedin_login_link_employer;
					}
				@endphp
				@if(setting('enable_linkedin_login') == 'yes')
				<a class="btn-google text-uppercase" id="google-link" href="{{$google_link}}">
					<i class="fab fa-google"></i> Google
				</a>
				@endif
				@if(setting('enable_google_login') == 'yes')
				<a class="btn-facebook text-uppercase" id="linkedin-link" href="{{$linkedin_link}}">
					<i class="fab fa-linkedin"></i> Linkedin
				</a>
				@endif
			</div>
    	</div>
    </div>
</form>
<div class="text-center mb-3">
	{{__('message.dont_have_account')}}
	<a class="register modal-back-to-register-btn" href="#">
		{{__('message.register')}}
	</a>
</div>