<form action="" method="" id="both_forgot_password_form" class="both-forgot-password-form">
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
	<div class="form-group text-center">
		<button type="submit" class="btn btn-primary btn" id="both_forgot_password_form_button">
			{{__('message.submit')}}
		</button>
	</div>
</form>
<div class="text-center mb-3">
	{{__('message.back_to_login')}}
	<a class="login modal-back-to-login-btn" href="#">
		{{__('message.login')}}
	</a>
</div>
