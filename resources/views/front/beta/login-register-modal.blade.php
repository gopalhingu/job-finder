<div class="container">
	<div class="row h-100 align-items-center">
	    @php 
	        $col_size = setting('display_info_in_login_modal') == 'yes' ? '6' : '12';
	    @endphp		
		@if(setting('display_info_in_login_modal') == 'yes')
		<div class="col-md-6 h-100 align-items-center">
			<div class="left-top">
				@php $error = url('/g-assets').'/essentials/images/general-not-found.png'; @endphp
				<img src="{{route('uploads-view', setting('general_modal'))}}" onerror="this.src='{{$error}}'" />
			</div>
			<div class="left-bottom">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<p>{{__('message.take_your_first_step_for_community')}}</p>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="section-modal-alpha-item">
							<div class="section-modal-alpha-item-heading">
								<h5><i class="fa-solid fa-briefcase"></i> {{$jobs_count}} {{__('message.jobs')}}</h5>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="section-modal-alpha-item">
							<div class="section-modal-alpha-item-heading">
								<h5><i class="fa-solid fa-user-tie"></i> {{$employers_count}} {{__('message.employers')}}</h5>
							</div>
						</div>
					</div>
					<div class="col-lg-4 col-md-12 col-sm-12">
						<div class="section-modal-alpha-item">
							<div class="section-modal-alpha-item-heading">
								<h5><i class="fa-solid fa-user-graduate"></i> {{$candidates_count}} {{__('message.candidates')}}</h5>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
		<div class="col-md-{{$col_size}} h-100 align-items-center">
			<div class="right-container">
				<div class="right-shadow-div">
					@php $error = url('/g-assets').'/essentials/images/general-not-found.png'; @endphp
					<img src="{{route('uploads-view', 'general/loader-1.gif')}}" onerror="this.src='{{$error}}'" />
				</div>
				<div class="modal-header">
					<h4 class="modal-title modal-title-login-register">{{$type}}</h4>
	                <button type="button" class="close close-login" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
				</div>
				<div class="modal-body modal-body-login-register">
					@if($type == 'login')
						@include('front.beta.login')
					@else
						@include('front.beta.register')
					@endif
				</div>
			</div>
		</div>
	</div>
</div>
