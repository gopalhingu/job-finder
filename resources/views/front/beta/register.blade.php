<form action="" method="" id="both_register_form">
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
    <input type="hidden" id="login-type" value="both"/>
    @elseif(setting('front_login_type') == 'only_candidates')
    <input type="hidden" id="login-type" name="type" value="candidate"/>
    @else
    <input type="hidden" id="login-type" name="type" value="employer"/>
    @endif
	
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
			<input type="text" class="form-control shadow-none border-none" name="first_name" 
				placeholder="{{__('message.enter_first_name')}}" required="required">
		</div>
	</div>
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><i class="fa fa-user"></i></span>
			<input type="text" class="form-control shadow-none border-none" name="last_name" 
				placeholder="{{__('message.enter_last_name')}}" required="required">
		</div>
	</div>
	<div class="form-group" id="register-company-field-container">
		<div class="input-group">
			<span class="input-group-addon"><i class="fa-solid fa-user-tie"></i></span>
			<input type="text" class="form-control shadow-none border-none" name="company" id="register-company-field"
				placeholder="{{__('message.enter_company')}}">
		</div>
	</div>
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
	<div class="form-group">
		<div class="input-group">
			<span class="input-group-addon"><i class="fa fa-eye-slash"></i></span>
			<input type="password" class="form-control shadow-none border-none" name="retype_password" 
				placeholder="{{__('message.enter_password_again')}}" required="required" autocomplete="on">
		</div>
	</div>
	<div class="form-group text-center">
		<button type="submit" class="btn btn-primary btn" id="both_register_form_button">
			{{__('message.register')}}
		</button>
	</div>
</form>
<div class="text-center mb-3">
	{{__('message.already_have_account')}}
	<a class="login modal-back-to-login-btn" href="#">
		{{__('message.login')}}
	</a>
</div>
