@extends('front.beta.layouts.master')

@section('breadcrumb')
@include('front.beta.partials.breadcrumb')
@endsection

@section('content')

<!-- Jobs List Page Starts -->
<div class="section-sidebar-beta jobs-list-page">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-12 col-sm-12">
				@include('front'.viewPrfx().'partials.jobs-sidebar')
			</div>
			<div class="col-lg-9 col-md-12 col-sm-12">
				<div class="section-jobs-alpha">
					<div class="container">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<!-- Jobs List Controls Starts -->
								<div class="section-controls-alpha">
									<div class="row">
										<div class="col-lg-3 col-md-12 col-sm-12">
											<p>{{$pagination_overview}}</p>
										</div>
										<div class="col-lg-3 col-md-12 col-sm-12">
											<div class="btn-group section-controls-alpha-btn-group">
												<button type="button" class="btn btn-left jobs-view-type" title="Rows" data-type="list">
													<i class="fa-solid fa-table-list"></i>
												</button>
												<button type="button" class="btn btn-right active jobs-view-type" title="Boxes" data-type="box">
													<i class="fa-solid fa-table-columns"></i>
												</button>
											</div>											
										</div>
										<div class="col-lg-3 col-md-12 col-sm-12">
										</div>
										<div class="col-lg-3 col-md-12 col-sm-12">
											<select class="jobs-list-select-sort">
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
								<!-- Jobs List Controls Ends -->
							</div>
							@if($jobs)
							@foreach($jobs as $job)	
							<!-- Jobs List Single Item Starts -->
							<div class="col-lg-6 col-md-12 col-sm-12">
								<div class="section-jobs-gamma-item">
									<div class="section-jobs-gamma-item-right-controls">
										@if(in_array($job['job_id'], $favorites))
										<i class="fa-solid fa-heart mark-favorite favorited" data-id="{{encode($job['job_id'])}}"></i>
										@else
										<i class="fa-regular fa-heart mark-favorite" data-id="{{encode($job['job_id'])}}"></i>
										@endif										
									</div>
									<div class="section-jobs-gamma-item-right-controls section-jobs-gamma-item-right-controls-2">
										<i class="fa-regular fa-paper-plane refer-job" data-id="{{encode($job['job_id'])}}"></i>
									</div>
									<div class="row align-items-center">
										<div class="col-md-12 col-sm-12">
											<div class="section-jobs-gamma-item-icon ">
												@php $thumb = employerThumb($job['employer_logo']); @endphp
												<img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
											</div>
											<div class="section-jobs-gamma-item-heading">
												<a href="{{frontJobLink($job['employer_slug'], $job['separate_site'])}}{{$job['slug'] ? $job['slug'] : encode($job['job_id'])}}">
													<h2>{{$job['title']}}</h2>
												</a>
											</div>
											<div class="section-jobs-gamma-item-content">
												<span><i class="far fa-calendar"></i> {{__('message.posted')}} : {{timeAgoByTimeStamp($job['created_at'])}}</span>
												@if(validDate($job['last_date']) && $job['display_jobs_last_date'] == 'yes')
												<span><i class="far fa-clock"></i> {{__('message.last_date')}} : {{validDate($job['last_date'])}}</span>
												@endif
												@if(issetVal($job, 'quizes_count'))
												<span><i class="fa-solid fa-list"></i> {{$job['quizes_count']}} {{__('message.quizes')}}</span>
												@endif
												@if(issetVal($job, 'traites_count'))
												<span><i class="fa-solid fa-star-half-stroke"></i> {{$job['traites_count']}} {{__('message.traites').' '.__('message.required')}}</span>
												@endif												
											</div>
											<div class="section-jobs-gamma-item-bottom">
												@if($job['department'])
												<div class="section-jobs-gamma-item-bottom-att" title="{{__('message.department').' : '.$job['department']}}">
													<i class="fa-icon fa fa-briefcase"></i> {{trimString($job['department'], 15)}}
												</div>
												@endif
												@if(issetVal($job, 'job_filters'))
												@foreach($job['job_filters'] as $jf)
												<div class="section-jobs-gamma-item-bottom-att" title="{{$jf['title']}}">
													<i class="{{$jf['icon'] ? $jf['icon'] : 'fa-solid fa-paperclip'}}"></i> 
													@foreach($jf['values'] as $jfval)
														{{trimString($jfval, 15)}}
													@endforeach
												</div>
												@endforeach
												@endif
											</div>
											<div class="section-jobs-gamma-item-button">
												<a class="btn" href="{{frontJobLink($job['employer_slug'], $job['separate_site'])}}{{$job['slug'] ? $job['slug'] : encode($job['job_id'])}}">
													{{__('message.view')}}
												</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- Jobs List Single Item Ends -->
							@endforeach
							@else
							@endif		
						</div>
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
<!-- Jobs List Page Ends -->

@endsection
