<!-- Home Features Guide Section Starts -->
<div class="section-make-account-beta">
	<div class="container-fluid">
		<div class="row h-100 align-items-center">
			<div class="col-lg-6 col-md-12 col-sm-12 p-0">
				<div class="section-make-account-beta-left">
					@php $error = url('/g-assets').'/essentials/images/general-not-found.png'; @endphp
					<img src="{{route('uploads-view', setting('general_benefits'))}}" onerror="this.src='{{$error}}'" />
				</div>
			</div>
			<div class="col-lg-6 col-md-12 col-sm-12 h-100 align-items-center">
				<div class="section-make-account-beta-right">
					<div class="section-make-account-beta-right-bg"></div>
					<h3>{{__('message.home_make_account_title_msg')}}</h3>
					<p>{{__('message.home_make_account_desc_msg_1')}}</p>
					<p>{{__('message.home_make_account_desc_msg_2')}}</p>
					@if(employerSession())
					<a class="btn btn-general" href="{{route('employer-dashboard')}}">
						{{__('message.dashboard')}}
					</a>
					@elseif(candidateSession())
					<a class="btn btn-general" href="#">
						{{__('message.sign_up_as_employer')}}
					</a>
					@else
					<a class="btn btn-general global-register-btn" href="#">
						{{__('message.home_make_account_button_msg')}}
					</a>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Home Features Guide Section Ends -->