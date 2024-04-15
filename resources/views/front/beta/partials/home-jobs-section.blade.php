@if($jobs)
<!-- Home Jobs Section Starts -->
<div class="section-jobs-alpha">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="section-heading-style-gamma">
					<div class="section-heading">
						<h2>{{__('message.latest_jobs')}}</h2>
					</div>
					<div class="section-intro-text">
						<p>{{__('message.home_jobs_msg')}}</p>
					</div>
					<div class="section-intro-button">
						<a class="btn" href="{{route('front-jobs-list')}}">{{__('message.view_all')}}</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			@foreach($jobs as $job)
			<!-- Home Job Single Item Starts -->
			<div class="col-lg-6 col-md-12 col-sm-12">
				<div class="section-jobs-alpha-item simple-shadow">
					<div class="row h-100 align-items-center">
						<div class="col-md-2 col-sm-12">
							<div class="section-jobs-alpha-item-left">
								<div class="section-jobs-alpha-item-left-image">
									@php $thumb = employerThumb($job['employer_logo']); @endphp
									<img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
								</div>
							</div>
						</div>
						<div class="col-md-10 col-sm-12">
							<div class="section-jobs-alpha-item-right">
								<div class="section-jobs-alpha-item-right-controls">
									@if(in_array($job['job_id'], $favorites))
									<i class="fa-solid fa-heart mark-favorite favorited" data-id="{{encode($job['job_id'])}}"></i>
									@else
									<i class="fa-regular fa-heart mark-favorite" data-id="{{encode($job['job_id'])}}"></i>
									@endif
									<i class="fa-regular fa-paper-plane refer-job" data-id="{{encode($job['job_id'])}}"></i>
								</div>
								<div class="section-jobs-alpha-item-right-heading">
									<a href="{{frontJobLink($job['employer_slug'], $job['separate_site'])}}{{$job['slug'] ? $job['slug'] : encode($job['job_id'])}}">		
										<h2>{{$job['title']}}</h2>
									</a>
								</div>
								<div class="section-jobs-alpha-item-right-content">
									<span><i class="fa-regular fa-calendar"></i> Posted : {{timeAgoByTimeStamp($job['created_at'])}}</span>
									@if($job['quizes_count'] > 0)
									<span><i class="fa fa-list"></i> {{ $job['quizes_count'] }} {{__('message.quizes')}}</span>
									@endif
									@if($job['traites_count'] > 0)
									<span><i class="fa fa-star-half-stroke"></i> {{$job['traites_count']}} {{__('message.traites').' '.__('message.required')}}</span>
									@endif
								</div>
								<div class="section-jobs-alpha-item-right-bottom">
									@if($job['department'])
									<div class="section-jobs-alpha-item-right-bottom-att" title="{{__('message.department')}}">
										<i class="fa-icon fa fa-briefcase"></i> {{$job['department']}}
									</div>
									@endif
									@if(issetVal($job, 'job_filters'))
									@foreach($job['job_filters'] as $jf)
									<div class="section-jobs-alpha-item-right-bottom-att" title="{{$jf['title']}}">
										<i class="{{$jf['icon'] ? $jf['icon'] : 'fa-solid fa-paperclip'}}"></i> 
										@foreach($jf['values'] as $jfval)
											{{$jfval}}
										@endforeach
									</div>
									@endforeach
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Home Job Single Item Ends -->
			@endforeach
		</div>
	</div>
</div>
<!-- Home Jobs Section Ends -->
@endif