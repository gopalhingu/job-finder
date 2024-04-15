<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <form class="form login-form" id="front_login_form">
            <div class="row">
                @if(setting('front_login_type') == 'both')
                <div class="col-md-12 col-lg-12">
                    <div class="form-group front-form-group">
                        <label for=""><input type="radio" name="type" checked value="candidate" /> 
                            {{ __('message.login_as_candidate') }}</label>&nbsp;&nbsp;
                        <label for=""><input type="radio" name="type" value="employer" /> 
                            {{ __('message.login_as_employer') }}</label>
                    </div>
                </div>
                @elseif(setting('front_login_type') == 'only_candidates')
                <input type="hidden" name="type" value="candidate"/>
                @else
                <input type="hidden" name="type" value="employer"/>
                @endif
                <div class="col-md-12 col-lg-12">
                    <div class="form-group front-form-group">
                        <label>{{ __('message.email') }}  *</label>
                        <input type="text" class="form-control" placeholder="john.doe@example.com" name="email" id="email">
                    </div>
                </div>
                <div class="col-md-12 col-lg-12">
                    <div class="form-group front-form-group">
                        <label>{{ __('message.password') }}</label>
                        <input type="password" class="form-control" placeholder="@#anew213$" name="password" id="password">
                    </div>
                </div>
                <div class="col-md-12 col-lg-12">
                    <div class="form-group front-form-group">
                        <label for=""><input type="checkbox" name="remember" class="" /> {{ __('message.remember_me') }}</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="form-group front-form-group">
                        <button type="submit" class="btn front-login-btn" title="Save" id="front_login_form_button">
                        {{ __('message.login') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-12 text-center">
                    <a href="{{route('front-forgot-view')}}">{{__('message.forgot_password')}}</a> | 
                    <a href="{{route('front-register')}}">{{__('message.register')}}</a>
                </div>
            </div>
        </form>
    </div>
</div>