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

						<!-- Jobs List Page Controls Starts -->
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="section-controls-alpha">
									<div class="row">
										<div class="col-lg-3 col-md-12 col-sm-12">
											<p>{{$pagination_overview}}</p>
										</div>
										<div class="col-lg-3 col-md-12 col-sm-12">
											<div class="btn-group section-controls-alpha-btn-group">
												<button type="button" class="btn btn-left active jobs-view-type" title="Rows" data-type="list">
													<i class="fa-solid fa-table-list"></i>
												</button>
												<button type="button" class="btn btn-right jobs-view-type" title="Boxes" data-type="box">
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
							</div>
							<!-- Jobs List Page Controls Ends -->

						</div>
						@if($jobs)
						<div class="row">
							@foreach($jobs as $job)
							<!-- Jobs List Page Single Item Starts -->
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="section-jobs-alpha-item">
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
												<div class="section-jobs-alpha-item-right-bottom">
													@if($job['department'])
													<div class="section-jobs-alpha-item-right-bottom-att" title="{{__('message.department')}}">
														<i class="fa-icon fa fa-briefcase"></i> {{$job['department']}}
													</div>
													@endif
													@if(isset($job['job_filters']))
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
							<!-- Jobs List Page Single Item Ends -->
							@endforeach
						</div>
						@else
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="section-jobs-alpha-item">
									{{__('message.no_jobs_found')}}
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
<!-- Jobs List Page Ends -->

@endsection
