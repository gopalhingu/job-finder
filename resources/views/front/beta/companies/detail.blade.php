@extends('front.beta.layouts.master')

@section('content')

<!-- Breadcrumb Starts -->
<div class="section-breadcrumb-beta my-0">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>{{$employer['company']}}</h1>
            </div>
            <div class="col-md-12">
                <ul>
                    <li><a href="/">{{__('message.home')}}</a></li>
                    <li>></li>
                    <li class="active"><a href="/companies">{{__('message.companies')}}</a></li>
                    <li>></li>
                    <li class="active"><a href="/company/{{$employer['slug']}}">{{$employer['company']}}</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Ends -->

<!-- Company Detail Page Starts -->
<div class="section-company-detail-alpha-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-12">
            	<div class="section-company-detail-alpha-right">
					@if($jobs)
					<div class="row">
						@foreach($jobs as $job)
                        <!-- Company Detail Page Job Item Starts -->
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
												<a href="{{route('front-jobs-detail', $job['slug'])}}">
													<h2>{{$job['title']}}</h2>
												</a>
											</div>
											<div class="section-jobs-alpha-item-right-content">
												<span><i class="fa-solid fa-calendar"></i> {{__('message.posted')}} : {{timeAgoByTimeStamp($job['created_at'])}}</span>
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
                        <!-- Company Detail Page Job Item Ends -->
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
				</div>	
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class="section-company-detail-alpha-company-detail">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="section-heading-style-zeta">
                                <div class="section-heading">
                                    <h2>{{__('message.employer_overview')}}</h2>
                                </div>
                            </div>                
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="section-company-detail-alpha-company-detail-image">
                                @php $thumb = employerThumb($employer['logo']); @endphp
                                <img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />                            	
                            </div>                
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="section-company-detail-alpha-company-detail-description">
                                <p>{{$employer['short_description']}}</p>
                            </div>                
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="section-job-detail-alpha-company-detail-items">
                                <div class="section-job-detail-alpha-company-detail-item">
                                    <i class="fa-solid fa-location-dot"></i> 
                                    <strong>{{__('message.location')}}</strong> : {{$employer['country']}}, {{$employer['city']}}
                                </div>
                                <div class="section-job-detail-alpha-company-detail-item">
                                    <i class="fa-solid fa-phone"></i> 
                                    <strong>{{__('message.phone')}}</strong> : {{$employer['phone1']}}
                                </div>
                                <div class="section-job-detail-alpha-company-detail-item">
                                    <i class="fa-solid fa-at"></i> 
                                    <strong>{{__('message.email')}}</strong> : {{$employer['email']}}
                                </div>
                                <div class="section-job-detail-alpha-company-detail-item">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> 
                                    <strong>{{__('message.url')}}</strong> : {{$employer['url']}}
                                </div>
                                <div class="section-job-detail-alpha-company-detail-item">
                                    <i class="fa-solid fa-users"></i> 
                                    <strong>{{__('message.no_of_employees')}}</strong> : {{$employer['no_of_employees']}}
                                </div>
                                <div class="section-job-detail-alpha-company-detail-item">
                                    <i class="fa-solid fa-industry"></i> 
                                    <strong>{{__('message.industry')}}</strong> : {{$employer['industry']}}
                                </div>
                                <div class="section-job-detail-alpha-company-detail-item">
                                    <i class="fa-solid fa-hourglass"></i> 
                                    <strong>{{__('message.founded_in')}}</strong> : {{$employer['founded_in']}}
                                </div>
                            </div>                
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="section-job-detail-alpha-company-detail-social-icons">
                                @if($employer['twitter_link'])
                                <a class="twitter" href="{{$employer['twitter_link']}}" target="_blank"><i class="fab fa-twitter"></i></a> 
                                @endif
                                @if($employer['facebook_link'])
                                <a class="facebook" href="{{$employer['facebook_link']}}" target="_blank"><i class="fab fa-facebook"></i></a> 
                                @endif
                                @if($employer['instagram_link'])
                                <a class="instagram" href="{{$employer['instagram_link']}}" target="_blank"><i class="fab fa-instagram"></i></a> 
                                @endif
                                @if($employer['google_link'])
                                <a class="google-plus" href="{{$employer['google_link']}}" target="_blank"><i class="fab fa-google-plus"></i></a> 
                                @endif
                                @if($employer['linkedin_link'])
                                <a class="linkedin" href="{{$employer['linkedin_link']}}" target="_blank"><i class="fab fa-linkedin"></i></a>
                                @endif
                                @if($employer['youtube_link'])
                                <a class="youtube" href="{{$employer['youtube_link']}}" target="_blank"><i class="fab fa-youtube"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Company Detail Page Ends -->

@endsection