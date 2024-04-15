@if($candidates)
<!-- Home Profiles Section Starts -->
<div class="section-profile-boxes-alpha">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="section-heading-style-gamma">
					<div class="section-heading">
						<h2>{{__('message.candidates')}}</h2>
					</div>
					<div class="section-intro-text">
						<p>{{__('message.home_candidates_msg')}}</p>
					</div>
					<div class="section-intro-button">
						<a class="btn" href="{{route('front-candidates')}}">{{__('message.view_all')}}</a>
					</div>
				</div>				
			</div>
		</div>
		<div class="row">
			@foreach($candidates as $candidate)
			<!-- Home Profiles Single Item Starts -->
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="section-profile-boxes-alpha-item">
					@if(employerSession())
					<div class="section-profile-boxes-alpha-item-right-controls">
						@if(in_array($candidate['candidate_id'], $favorites))
						<i class="fa-solid fa-heart mark-candidate-favorite favorited" data-id="{{encode($candidate['candidate_id'])}}"></i>
						@else
						<i class="fa-regular fa-heart mark-candidate-favorite" data-id="{{encode($candidate['candidate_id'])}}"></i>
						@endif						
					</div>					
					@endif
					<div class="row align-items-center">
						<div class="col-md-12 col-sm-12">
							<div class="section-profile-boxes-alpha-item-heading">
								<a href="{{route('front-candidate-detail', ($candidate['slug'] ? $candidate['slug'] : encode($candidate['candidate_id'])))}}" class="btn">
									<h2>{{$candidate['first_name'].' '.$candidate['last_name']}}</h2>
								</a>
							</div>
							<div class="section-profile-boxes-alpha-item-content">
								<p title="{{$candidate['designation']}} | {{$candidate['city'] ? $candidate['city'] : ''}}, {{$candidate['country'] ? ' ,'.$candidate['country'] : ''}}">
									<i class="fa-solid fa-award"></i> {{trimString($candidate['designation'], 15)}} | 
									<i class="fa-solid fa-location-dot"></i> 
									{{$candidate['city'] ? $candidate['city'] : ''}}, {{$candidate['country'] ? ' ,'.$candidate['country'] : ''}}
								</p>
							</div>
							<div class="section-profile-boxes-alpha-item-icon">
								@php $thumb = candidateThumb($candidate['image']); @endphp
								<img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
							</div>							
							<div class="section-profile-boxes-alpha-item-skills">
								@if($candidate['skill_titles'] && is_array($candidate['skill_titles']))
									@foreach($candidate['skill_titles'] as $st)
									<span>{{$st}}</span>
									@endforeach
								@elseif ($candidate['skill_titles'])
									<span>{{$candidate['skill_titles']}}</span>
								@endif
							</div>
							<div class="section-profile-boxes-alpha-item-resume">
								<span title="{{$candidate['experiences_count']}} {{__('message.job_experiences')}}">
									<i class="fa-solid fa-user-tie"></i> {{$candidate['experiences_count']}}
								</span>
								<span title="{{$candidate['qualifications_count']}} {{__('message.qualifications')}}">
									<i class="fa-solid fa-graduation-cap"></i> {{$candidate['qualifications_count']}}
								</span>
								<span title="{{$candidate['skills_count']}} {{__('message.skills')}}">
									<i class="fa-solid fa-screwdriver-wrench"></i> {{$candidate['skills_count']}}
								</span>
								<span title="{{$candidate['languages_count']}} {{__('message.languages')}}">
									<i class="fa-solid fa-language"></i> {{$candidate['languages_count']}}
								</span>
								<span title="{{$candidate['achievements_count']}} {{__('message.achievements')}}">
									<i class="fa-solid fa-trophy"></i> {{$candidate['achievements_count']}}
								</span>
								<span title="{{$candidate['references_count']}} {{__('message.references')}}">
									<i class="fa-solid fa-link"></i> {{$candidate['references_count']}}
								</span>
							</div>
							<div class="section-profile-boxes-alpha-item-button">
								<a href="{{route('front-candidate-detail', ($candidate['slug'] ? $candidate['slug'] : encode($candidate['candidate_id'])))}}" class="btn">
									{{__('message.view_profile')}}
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Home Profiles Single Item ends -->
			@endforeach
		</div>
	</div>
</div>
<!-- Home Profiles Section Ends -->
@endif