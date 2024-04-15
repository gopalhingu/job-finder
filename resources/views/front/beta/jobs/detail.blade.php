@extends('front.beta.layouts.master')

@section('content')

<!-- Jobs Detail Page Breadcrumb Starts -->
<div class="section-job-detail-alpha-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-12">
                        <h1>{{$job['title']}}</h1>
                    </div>
                    <div class="col-md-12">
                        <ul>
                            <li><a href="/">{{__('message.home')}}</a></li>
                            <li>></li>
                            <li><a href="/jobs">Jobs</a></li>
                            <li>></li>
                            <li class="active"><a href="/jobs/{{$job['slug']}}">{{$job['title']}}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-12">
                        <div class="section-job-detail-alpha-breadcrumb-att-container">
                            <div class="section-job-detail-alpha-breadcrumb-att">
                                <i class="fa-regular fa-calendar"></i> {{__('message.posted')}} : {{timeAgoByTimeStamp($job['created_at'])}}
                            </div>
                            @if(validDate($job['last_date']) && $job['display_jobs_last_date'] == 'yes')
                            <div class="section-job-detail-alpha-breadcrumb-att">
                                <i class="far fa-clock"></i> {{__('message.last_date')}} : {{validDate($job['last_date'])}}
                            </div>
                            @endif
                            @if($job['department'])
                            <div class="section-job-detail-alpha-breadcrumb-att">
                                <i class="fa-icon fa fa-briefcase"></i> {{__('message.department')}} : {{$job['department']}}
                            </div>
                            @endif
                        </div>                
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class="section-job-detail-alpha-breadcrumb-btns">
                    <button class="btn">
                        @if(in_array($job['job_id'], $favorites))
                        <i class="fa-solid fa-heart mark-favorite favorited" data-id="{{encode($job['job_id'])}}"></i>
                        {{__('message.unmark_favorite')}}
                        @else
                        <i class="fa-regular fa-heart mark-favorite" data-id="{{encode($job['job_id'])}}"></i>
                        {{__('message.mark_favorite')}}
                        @endif                        
                    </button>
                    <button class="btn refer-job" data-id="{{encode($job['job_id'])}}">
                        <i class="fa-regular fa-paper-plane"></i> {{__('message.refer_this_job')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Jobs Detail Page Breadcrumb Ends -->

<div class="section-job-detail-alpha-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-12">
                <!-- Jobs Detail Page Filters Starts -->
                <div class="section-job-detail-alpha-filters-container">
                    <div class="row">
                        @if(isset($job['job_filters']))
                        @foreach($job['job_filters'] as $jf)                        
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-job-detail-alpha-filters-item" title="{{$jf['title']}}">
                                <div class="section-job-detail-alpha-filters-item-icon">
                                    <i class="{{$jf['icon'] ? $jf['icon'] : 'fa-solid fa-paperclip'}}"></i> 
                                </div>
                                <div class="section-job-detail-alpha-filters-item-title">
                                    {{$jf['title']}}
                                </div> 
                                <div class="section-job-detail-alpha-filters-item-value">
                                    @foreach($jf['values'] as $jfval)
                                        {{$jfval}}
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
                <!-- Jobs Detail Page Filters Ends -->
                @if($job['quizes_count'] > 0)
                <div class="section-job-detail-alpha-quizes-container">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="section-job-detail-alpha-quizes-item">
                                <i class="fa-solid fa-list"></i> 
                                <strong>{{$job['quizes_count']}} {{__('message.quizes')}}</strong> : 
                                {{__('message.quizes_to_be_attempted_detail')}}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="section-job-detail-alpha-job-description">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            {!! $job['description'] !!}
                        </div>
                    </div>
                </div>

                @if(candidateSession() && !in_array($job['job_id'], $applied))
                <form id="job_apply_form">
                <input type="hidden" name="job_id" value="{{ encode($job['job_id']) }}">
                @csrf

                @if($job['traites_count'] > 0)
                <!-- Jobs Detail Page Traites Starts -->
                <div class="section-job-detail-alpha-traites-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 p-0">
                                <div class="section-heading-style-alpha">
                                    <div class="section-heading">
                                        <h2>{{__('message.self_assesment')}}</h2>
                                    </div>
                                    <div class="section-intro-text">
                                        <p>{{__('message.please_rate_yourself')}}</p>
                                    </div>                  
                                    <div class="section-intro-button"></div>
                                </div>
                            </div>
                        </div>                    
                        <div class="row">
                            @foreach($job['traites'] as $traite)
                            <div class="col-lg-4 col-md-12 col-sm-12">
                                <div class="section-job-detail-alpha-traites-item">
                                    <div class="section-job-detail-alpha-traites-item-heading">
                                        <h4>{{$traite['title']}}</h4>
                                    </div>
                                    <input type="hidden" name="traite_titles[{{ encode($traite['id']) }}]" value="{{ $traite['title'] }}">
                                    <select class="form-control" name="traites[{{ encode($traite['id']) }}]">
                                        <option value="1">{{__('message.poor')}}</option>
                                        <option value="2">{{__('message.bad')}}</option>
                                        <option value="3" selected="">{{__('message.average')}}</option>
                                        <option value="4">{{__('message.good')}}</option>
                                        <option value="5">{{__('message.excellent')}}</option>
                                    </select>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Jobs Detail Page Traites Ends -->
                @endif

                <div class="section-job-detail-alpha-apply-container">
                    <div class="container">
                        @if(setting('enable_multiple_resume') == 'yes')
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                <label>{{__('message.please_select_one_of_your_resume')}}</label>
                                <select class="form-control" name="resume" autocomplete="off">
                                    @foreach ($resumes as $resume)
                                    <option value="{{ encode($resume['resume_id']) }}">{{ $resume['title'] }}</option>
                                    @endforeach
                                </select>
                                <br />
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="resume" value="{{ encode($resume_id) }}">
                        @endif
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                <button class="btn" id="job_apply_form_button">
                                    <i class="fa-solid fa-hand-pointer"></i> {{__('message.apply_for_this_job')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                @else
                
                <div class="section-job-detail-alpha-apply-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                @if(in_array($job['job_id'], $applied))
                                <button class="btn">{{__('message.you_have_already_applied')}}</button>
                                @elseif(employerSession())
                                <button class="btn">
                                    <i class="fa-solid fa-hand-pointer"></i> {{__('message.login_as_candidate')}} asdfa
                                </button>
                                @else
                                <button class="btn global-login-btn">
                                    <i class="fa-solid fa-hand-pointer"></i> {{__('message.signup_to_apply')}}
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                </form>
                @endif

                @if($similar)
                <!-- Jobs Detail Page Similar Jobs Starts -->
                <div class="section-carousel-beta">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 p-0">
                                <div class="section-heading-style-gamma">
                                    <div class="section-heading">
                                        <h2>{{__('message.similar_jobs')}}</h2>
                                    </div>
                                    <div class="section-intro-text">
                                        <p>{{__('message.similar_jobs_msg')}}.</p>
                                    </div>                  
                                    <div class="section-intro-button">
                                        <button class="btn customPrevBtn"><</button>
                                        <button class="btn customNextBtn">></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 p-0">
                                <div class="item section-carousel-beta-container">
                                    <div class="owl-carousel owl-theme similar-jobs-carousel">
                                        @foreach($similar as $sim)
                                        <div class="section-carousel-beta-item">
                                            <div class="section-carousel-beta-item-right-controls">
                                                @if(in_array($sim['job_id'], $favorites))
                                                <i class="fa-solid fa-heart mark-favorite favorited" data-id="{{encode($sim['job_id'])}}"></i>
                                                @else
                                                <i class="fa-regular fa-heart mark-favorite" data-id="{{encode($sim['job_id'])}}"></i>
                                                @endif
                                            </div>
                                            <div class="section-carousel-beta-item-right-controls section-carousel-beta-item-right-controls-2">
                                                <i class="fa-regular fa-paper-plane refer-job" data-id="{{encode($job['job_id'])}}"></i>
                                            </div>
                                            <div class="row align-items-center">
                                                <div class="col-md-12 col-sm-12">
                                                    <div class="section-carousel-beta-item-icon ">
                                                        @php $thumb = employerThumb($sim['employer_logo']); @endphp
                                                        <img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
                                                    </div>
                                                    <div class="section-carousel-beta-item-heading">
                                                        <a href="{{frontJobLink($sim['employer_slug'], $sim['separate_site'])}}{{$sim['slug'] ? $sim['slug'] : encode($sim['job_id'])}}">
                                                            <h2>{{$sim['title']}}</h2>
                                                        </a>
                                                    </div>
                                                    <div class="section-carousel-beta-item-content">
                                                        <span>
                                                            <i class="fa-regular fa-calendar"></i> 
                                                            {{__('message.posted')}} : {{timeAgoByTimeStamp($sim['created_at'])}}, 
                                                        </span>
                                                        <span><i class="fa-solid fa-list"></i> 2 Quizes,</span>
                                                        @if(issetVal($job, 'quizes_count'))
                                                        <span>
                                                            <i class="fa-solid fa-list"></i> {{$job['quizes_count']}} {{__('message.quizes')}}
                                                        </span>
                                                        @endif
                                                        @if(issetVal($job, 'traites_count'))
                                                        <span>
                                                            <i class="fa-solid fa-star-half-stroke"></i> {{$job['traites_count']}} {{__('message.traites').' '.__('message.required')}}
                                                        </span>
                                                        @endif
                                                    </div>
                                                    <div class="section-carousel-beta-item-bottom">
                                                        @if($job['department'])
                                                        <div class="section-carousel-beta-item-bottom-att" title="{{__('message.department')}}">
                                                            <i class="fa-icon fa fa-briefcase"></i> {{$job['department']}}
                                                        </div>
                                                        @endif
                                                        @if(isset($job['job_filters']))
                                                        @foreach($job['job_filters'] as $jf)
                                                        <div class="section-carousel-beta-item-bottom-att" title="{{$jf['title']}}">
                                                            <i class="{{$jf['icon'] ? $jf['icon'] : 'fa-solid fa-paperclip'}}"></i> 
                                                            @foreach($jf['values'] as $jfval)
                                                                {{$jfval}}
                                                            @endforeach
                                                        </div>
                                                        @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="section-carousel-beta-item-button">
                                                        <a href="{{route('front-jobs-detail', $sim['slug'])}}" class="btn">
                                                            {{__('message.view_details')}}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Jobs Detail Page Similar Jobs Ends -->
                @endif
            </div>

            <!-- Jobs Detail Page Employer Detail Starts -->
            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class="section-job-detail-alpha-company-detail">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="section-heading-style-zeta">
                                <div class="section-heading">
                                    <h2>{{__('message.employer_overview')}}</h2>
                                </div>
                            </div>                
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="section-job-detail-alpha-company-detail-image">
                                @php $thumb = employerThumb($employer['logo']); @endphp
                                <img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
                            </div>                
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="section-job-detail-alpha-company-detail-description">
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
            <!-- Jobs Detail Page Employer Detail Ends -->

        </div>
    </div>
</div>

@endsection