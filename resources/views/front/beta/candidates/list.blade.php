@extends('front.beta.layouts.master')

@section('breadcrumb')
@include('front'.viewPrfx().'partials.breadcrumb')
@endsection

@section('content')

<!-- Account Candidate List Section Starts -->
<div class="section-sidebar-alpha candidates-list-page">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-12 col-sm-12">
				@include('front'.viewPrfx().'partials.candidates-sidebar')
			</div>
			<div class="col-lg-9 col-md-12 col-sm-12">
				<div class="section-candidates-beta">
					<div class="container">

						<!-- Account Controls Starts -->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="section-candidates-beta-controls">
									<div class="row">
										<div class="col-lg-3 col-md-12 col-sm-12">
											<p>{{$pagination_overview}}</p>
										</div>
										<div class="col-lg-3 col-md-12 col-sm-12">
											<div class="btn-group section-candidates-alpha-controls-btn-group">
												<button type="button" class="btn btn-left active candidates-view-type" title="Rows" data-type="list">
													<i class="fa-solid fa-table-columns"></i>
												</button>
												<button type="button" class="btn btn-right candidates-view-type" title="Boxes" data-type="box">
													<i class="fa-solid fa-table-list"></i>
												</button>
											</div>											
										</div>
										<div class="col-lg-3 col-md-12 col-sm-12">
										</div>
										<div class="col-lg-3 col-md-12 col-sm-12">
											<select class="candidates-list-select-sort">
												<option value="">
													{{__('message.sort_by')}}
												</option>
												<option value="sort_newer" {{$sort == 'sort_newer' ? 'selected' : ''}}>
													{{__('message.most').' '.__('message.recent')}}
												</option>
												<option value="sort_older" {{$sort == 'sort_older' ? 'selected' : ''}}>
													{{__('message.older')}}
												</option>
											</select>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- Account Controls Ends -->

						@if($candidates)
						<div class="row">
							@foreach($candidates as $candidate)
							<!-- Candidate List Item Starts -->
							<div class="col-lg-6 col-md-12 col-sm-12">
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
												<a href="{{route('front-candidate-detail', ($candidate['slug'] ? $candidate['slug'] : encode($candidate['candidate_id'])))}}">
													<h2>{{ $candidate['first_name'].' '.$candidate['last_name'] }}</h2>
												</a>
											</div>
											<div class="section-profile-boxes-alpha-item-content">
												<p title="{{$candidate['designation']}} @if(candidateShow($candidate, 'show_location')) | {{$candidate['city'] ? $candidate['city'] : ''}}, {{$candidate['country'] ? ' ,'.$candidate['country'] : ''}} @endif">
													<i class="fa-solid fa-award"></i> {{trimString($candidate['designation'], 15)}}
													@if(candidateShow($candidate, 'show_location'))
													 | 
													<i class="fa-solid fa-location-dot"></i> 
													{{$candidate['city'] ? $candidate['city'] : ''}}, {{$candidate['country'] ? ' ,'.$candidate['country'] : ''}}
													@endif
												</p>
											</div>
											<div class="section-profile-boxes-alpha-item-icon ">
												@php $thumb = candidateThumb($candidate['image']); @endphp
												<img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
											</div>							
											<div class="section-profile-boxes-alpha-item-skills">
												@if($candidate['skill_titles'] && candidateShow($candidate, 'show_skills'))
												@if(is_array($candidate['skill_titles']))
												@foreach($candidate['skill_titles'] as $st)
												<span>{{$st}}</span>
												@endforeach
												@else
												<span>{{$candidate['skill_titles']}}</span>
												@endif
												@endif
											</div>
											<div class="section-profile-boxes-alpha-item-resume">
												@if(candidateShow($candidate, 'show_experiences'))
												<span title="{{$candidate['experiences_count']}} {{__('message.job_experiences')}}">
													<i class="fa-solid fa-user-tie"></i> {{$candidate['experiences_count']}}
												</span>
												@endif
												@if(candidateShow($candidate, 'show_qualifications'))
												<span title="{{$candidate['qualifications_count']}} {{__('message.qualifications')}}">
													<i class="fa-solid fa-graduation-cap"></i> {{$candidate['qualifications_count']}}
												</span>
												@endif
												@if(candidateShow($candidate, 'show_skills'))
												<span title="{{$candidate['skills_count']}} {{__('message.skills')}}">
													<i class="fa-solid fa-screwdriver-wrench"></i> {{$candidate['skills_count']}}
												</span>
												@endif
												@if(candidateShow($candidate, 'show_languages'))
												<span title="{{$candidate['languages_count']}} {{__('message.languages')}}">
													<i class="fa-solid fa-language"></i> {{$candidate['languages_count']}}
												</span>
												@endif
												@if(candidateShow($candidate, 'show_achievements'))
												<span title="{{$candidate['achievements_count']}} {{__('message.achievements')}}">
													<i class="fa-solid fa-trophy"></i> {{$candidate['achievements_count']}}
												</span>
												@endif
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
							<!-- Candidate List Item Ends -->
							@endforeach
						</div>
						@else
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="section-jobs-alpha-item">
									{{__('message.no_candidates_found')}}
								</div>
							</div>
						</div>
						@endif
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="section-pagination-alpha">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12">
											{!!$pagination!!}
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
<!-- Account Candidate List Section Ends -->

@endsection
