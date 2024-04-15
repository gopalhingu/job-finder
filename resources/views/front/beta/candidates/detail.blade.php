@extends('front.beta.layouts.master')

@section('content')

<!-- Candidate Profile Detail Breadcrumb Starts -->
<div class="section-profile-detail-alpha-breadcrumb">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-12">
                <div class="row">
                    <div class="col-md-12">
                        <h1>{{$candidate['first_name'].' '.$candidate['last_name']}} : {{$candidate['designation']}}</h1>
                    </div>
                    <div class="col-md-12">
                        <ul>
                            <li><a href="/">{{__('message.home')}}</a></li>
                            <li>></li>
                            <li><a href="/candidates">{{__('message.candidates')}}</a></li>
                            <li>></li>
                            <li class="active">
                                <a href="{{route('front-candidate-detail', ($candidate['slug'] ? $candidate['slug'] : encode($candidate['candidate_id'])))}}">
                                    {{$candidate['first_name'].' '.$candidate['last_name']}}
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-12">
                        <div class="section-profile-detail-alpha-breadcrumb-att-container">
                            @if($candidate['skill_titles'] && candidateShow($candidate, 'show_skills'))
                            @if(is_array($candidate['skill_titles']))
                            @foreach($candidate['skill_titles'] as $st)
                            <div class="section-profile-detail-alpha-breadcrumb-att">{{$st}}</div>
                            @endforeach
                            @else
                            <div class="section-profile-detail-alpha-breadcrumb-att">{{$candidate['skill_titles']}}</div>
                            @endif
                            @endif
                        </div>                
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class="section-profile-detail-alpha-breadcrumb-btns">
                    @if(employerSession())
                    <button class="btn">
                        @if(in_array($candidate['candidate_id'], $favorites))
                        <i class="fa-solid fa-heart mark-candidate-favorite favorited" data-id="{{encode($candidate['candidate_id'])}}"></i>
                        {{__('message.unmark_favorite')}}
                        @else
                        <i class="fa-regular fa-heart mark-candidate-favorite" data-id="{{encode($candidate['candidate_id'])}}"></i>
                        {{__('message.mark_favorite')}}
                        @endif                      
                    </button>
                    @endif                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Candidate Profile Detail Breadcrumb Ends -->

<!-- Candidate Profile Detail Content Starts -->
<div class="section-profile-detail-alpha-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12 col-sm-12">

                <!-- Candidate Profile Detail Filters Starts -->
                <div class="section-profile-detail-alpha-filters-container">
                    <div class="row">
                        @if(candidateShow($candidate, 'show_experiences'))
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-filters-item">
                                <div class="section-profile-detail-alpha-filters-item-icon">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div class="section-profile-detail-alpha-filters-item-value">
                                    {{$candidate['experiences_count']}} {{__('message.job_experiences')}}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(candidateShow($candidate, 'show_qualifications'))
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-filters-item">
                                <div class="section-profile-detail-alpha-filters-item-icon">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                </div>
                                <div class="section-profile-detail-alpha-filters-item-value">
                                    {{$candidate['qualifications_count']}} {{__('message.qualifications')}}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(candidateShow($candidate, 'show_achievements'))
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-filters-item">
                                <div class="section-profile-detail-alpha-filters-item-icon">
                                    <i class="fa-solid fa-trophy"></i>
                                </div>
                                <div class="section-profile-detail-alpha-filters-item-value">
                                    {{$candidate['achievements_count']}} {{__('message.achievements')}}
                                </div>
                            </div>
                        </div>                        
                        @endif
                        @if(candidateShow($candidate, 'show_skills'))
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-filters-item">
                                <div class="section-profile-detail-alpha-filters-item-icon">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </div>
                                <div class="section-profile-detail-alpha-filters-item-value">
                                    {{$candidate['skills_count']}} {{__('message.skills')}}
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(candidateShow($candidate, 'show_languages'))
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-filters-item">
                                <div class="section-profile-detail-alpha-filters-item-icon">
                                    <i class="fa-solid fa-language"></i>
                                </div>
                                <div class="section-profile-detail-alpha-filters-item-value">
                                    {{$candidate['languages_count']}} {{__('message.languages')}}
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-lg-4 col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-filters-item">
                                <div class="section-profile-detail-alpha-filters-item-icon">
                                    <i class="fa-solid fa-link"></i>
                                </div>
                                <div class="section-profile-detail-alpha-filters-item-value">
                                    {{$candidate['references_count']}} {{__('message.references')}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Candidate Profile Detail Filters ends -->

                @if($resume['experiences'] && candidateShow($candidate, 'show_experiences'))
                <!-- Candidate Experiences Section Starts -->
                <div class="section-profile-detail-alpha-res-pf">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="section-heading-style-beta">
                                <div class="section-heading">
                                    <h2>{{__('message.experiences')}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @foreach($resume['experiences'] as $exp)
                            <div class="section-profile-detail-alpha-res-pf-item">
                                <div class="section-profile-detail-alpha-res-pf-item-timelime">
                                    <div class="section-profile-detail-alpha-res-pf-item-timelime-dot">
                                        <div class="section-profile-detail-alpha-res-pf-item-timelime-dot-2"></div>
                                    </div>
                                    <p>
                                        {{dateFormat($exp['from'])}} - 
                                        @if ($exp['is_current'] == 1 ) 
                                            {{__('message.currently_working')}} 
                                        @else 
                                            {{dateFormat($exp['to'])}} 
                                        @endif
                                    </p>
                                </div>
                                <h4 class="section-profile-detail-alpha-res-pf-item-heading">
                                    {{$exp['title']}}
                                </h4>
                                <div class="section-profile-detail-alpha-res-pf-item-sub-heading">
                                    <p>{{$exp['company']}}</p>
                                </div>
                                <div class="section-profile-detail-alpha-res-pf-item-description">
                                    <p>{{$exp['description']}}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Candidate Experiences Section Ends -->
                @endif
                
                @if($resume['qualifications'] && candidateShow($candidate, 'show_qualifications'))
                <!-- Candidate Qualifications Section Starts -->
                <div class="section-profile-detail-alpha-res-pf">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="section-heading-style-beta">
                                <div class="section-heading">
                                    <h2>{{__('message.qualifications')}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @foreach($resume['qualifications'] as $qua)
                            <div class="section-profile-detail-alpha-res-pf-item">
                                <div class="section-profile-detail-alpha-res-pf-item-timelime">
                                    <div class="section-profile-detail-alpha-res-pf-item-timelime-dot">
                                        <div class="section-profile-detail-alpha-res-pf-item-timelime-dot-2"></div>
                                    </div>
                                    <p>{{dateFormat($qua['from'])}} - {{dateFormat($qua['to'])}}</p>
                                </div>
                                <h4 class="section-profile-detail-alpha-res-pf-item-heading">{{$qua['title']}}</h4>
                                <div class="section-profile-detail-alpha-res-pf-item-sub-heading"><p>{{$qua['institution']}}</p></div>
                                <div class="section-profile-detail-alpha-res-pf-item-description">
                                    <p></p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Candidate Qualifications Section Ends -->
                @endif   

                @if($resume['achievements'] && candidateShow($candidate, 'show_achievements'))
                <!-- Candidate Achievements Section Starts -->
                <div class="section-profile-detail-alpha-res-pf">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="section-heading-style-beta">
                                <div class="section-heading">
                                    <h2>{{__('message.achievements')}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>                    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            @foreach($resume['achievements'] as $ach)
                            <div class="section-profile-detail-alpha-res-pf-item">
                                <div class="section-profile-detail-alpha-res-pf-item-timelime">
                                    <div class="section-profile-detail-alpha-res-pf-item-timelime-dot">
                                        <div class="section-profile-detail-alpha-res-pf-item-timelime-dot-2"></div>
                                    </div>
                                    <p>{{dateFormat($ach['date'])}}</p>
                                </div>
                                <h4 class="section-profile-detail-alpha-res-pf-item-heading">{{$ach['title']}}</h4>
                                <div class="section-profile-detail-alpha-res-pf-item-sub-heading"><p>{{$ach['type']}}</p></div>
                                <div class="section-profile-detail-alpha-res-pf-item-description">
                                    <p>{{$ach['description']}}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Candidate Achievements Section Ends -->
                @endif

                @if($resume['skills'] && candidateShow($candidate, 'show_skills'))
                <!-- Candidate Skills Section Starts -->
                <div class="section-profile-detail-alpha-res-items-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 p-0">
                                <div class="section-heading-style-alpha">
                                    <div class="section-heading">
                                        <h2>{{__('message.skills')}}</h2>
                                    </div>
                                    <div class="section-intro-button"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($resume['skills'] as $sk)
                            <div class="col-lg-3 col-md-12 col-sm-12">
                                <div class="section-profile-detail-alpha-res-items-item">
                                    <div class="section-profile-detail-alpha-res-items-item-heading">
                                        <h4>{{$sk['title']}}</h4>
                                    </div>
                                    <div class="section-profile-detail-alpha-res-items-item-rating star-rating">
                                        <ul class="star-rating-beta">
                                            <li class="{{ratingValue(1, $sk['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                            <li class="{{ratingValue(2, $sk['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                            <li class="{{ratingValue(3, $sk['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                            <li class="{{ratingValue(4, $sk['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                            <li class="{{ratingValue(5, $sk['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Candidate Skills Section Ends -->
                @endif

                @if($resume['languages'] && candidateShow($candidate, 'show_languages'))
                <!-- Candidate Languages Section Starts -->
                <div class="section-profile-detail-alpha-res-items-container">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 p-0">
                                <div class="section-heading-style-alpha">
                                    <div class="section-heading">
                                        <h2>{{__('message.languages')}}</h2>
                                    </div>
                                    <div class="section-intro-button"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($resume['languages'] as $lan)
                            <div class="col-lg-3 col-md-12 col-sm-12">
                                <div class="section-profile-detail-alpha-res-items-item">
                                    <div class="section-profile-detail-alpha-res-items-item-heading">
                                        <h4>{{$lan['title']}}</h4>
                                    </div>
                                    <div class="section-profile-detail-alpha-res-items-item-rating star-rating">
                                        <ul class="star-rating-beta">
                                            <li class="{{ratingValue(1, $lan['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                            <li class="{{ratingValue(2, $lan['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                            <li class="{{ratingValue(3, $lan['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                            <li class="{{ratingValue(4, $lan['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                            <li class="{{ratingValue(5, $lan['proficiency'])}}">
                                                <a href="javascript:void(0);"><div class="star-icon"></div></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Candidate Languages Section Ends -->
                @endif

                @if($similar)
                <!-- Candidate Similar Jobs Section Starts -->
                <div class="section-carousel-beta">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 p-0">
                                <div class="section-heading-style-gamma">
                                    <div class="section-heading">
                                        <h2>{{__('message.similar_candidates')}}</h2>
                                    </div>
                                    <div class="section-intro-text">
                                        <p>{{__('message.similar_candidates_msg')}}.</p>
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
                                    <div class="owl-carousel owl-theme similar-candidates-carousel">

                                        @foreach($similar as $cand)
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
                                                        <h2>{{$cand['first_name'].' '.$cand['last_name']}}</h2>
                                                    </div>
                                                    <div class="section-profile-boxes-alpha-item-content">
                                                        <p title="{{$cand['designation']}} | {{$cand['city'] ? $cand['city'] : ''}}, {{$cand['country'] ? ' ,'.$cand['country'] : ''}}">
                                                            <i class="fa-solid fa-award"></i> {{trimString($cand['designation'], 15)}} | 
                                                            <i class="fa-solid fa-location-dot"></i> 
                                                            {{$cand['city'] ? $cand['city'] : ''}}, {{$cand['country'] ? ' ,'.$cand['country'] : ''}}
                                                        </p>
                                                    </div>
                                                    <div class="section-profile-boxes-alpha-item-icon ">
                                                        @php $thumb = candidateThumb($cand['image']); @endphp
                                                        <img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
                                                    </div>                          
                                                    <div class="section-profile-boxes-alpha-item-skills">
                                                        @if($cand['skill_titles'])
                                                        @if(is_array($cand['skill_titles']))
                                                        @foreach($cand['skill_titles'] as $st)
                                                        <span>{{$st}}</span>
                                                        @endforeach
                                                        @else
                                                        <span>{{$cand['skill_titles']}}</span>
                                                        @endif
                                                        @endif
                                                    </div>
                                                    <div class="section-profile-boxes-alpha-item-resume">
                                                        <span title="{{$cand['experiences_count']}} {{__('message.job_experiences')}}">
                                                            <i class="fa-solid fa-user-tie"></i> {{$cand['experiences_count']}}
                                                        </span>
                                                        <span title="{{$cand['qualifications_count']}} {{__('message.qualifications')}}">
                                                            <i class="fa-solid fa-graduation-cap"></i> {{$cand['qualifications_count']}}
                                                        </span>
                                                        <span title="{{$cand['skills_count']}} {{__('message.skills')}}">
                                                            <i class="fa-solid fa-screwdriver-wrench"></i> {{$cand['skills_count']}}
                                                        </span>
                                                        <span title="{{$cand['languages_count']}} {{__('message.languages')}}">
                                                            <i class="fa-solid fa-language"></i> {{$cand['languages_count']}}
                                                        </span>
                                                        <span title="{{$cand['achievements_count']}} {{__('message.achievements')}}">
                                                            <i class="fa-solid fa-trophy"></i> {{$cand['achievements_count']}}
                                                        </span>
                                                        <span title="{{$cand['references_count']}} {{__('message.references')}}">
                                                            <i class="fa-solid fa-link"></i> {{$cand['references_count']}}
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
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Candidate Similar Jobs Section Ends -->
                @endif
            </div>

            <div class="col-lg-3 col-md-12 col-sm-12">
                <!-- Candidate Overview Section Starts -->
                <div class="section-profile-detail-alpha-user-detail">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="section-heading-style-zeta">
                                <div class="section-heading">
                                    <h2>{{__('message.candidate_overview')}}</h2>
                                </div>
                            </div>                
                        </div>
                        <div class="col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-user-detail-image">
                                @php $thumb = candidateThumb($candidate['image']); @endphp
                                <img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />                                
                            </div>                
                        </div>
                        @if(candidateShow($candidate, 'show_bio'))
                        <div class="col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-user-detail-description">
                                <p>{{$candidate['bio'] ? $candidate['bio'] : '----'}}</p>
                            </div>                
                        </div>
                        @endif
                        <div class="col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-user-detail-items">
                                @if(candidateShow($candidate, 'show_location'))
                                <div class="section-profile-detail-alpha-user-detail-item">
                                    <i class="fa-solid fa-location-dot"></i> 
                                    <strong>{{__('message.location')}}</strong> : {{$candidate['city'] ? $candidate['city'] : ''}}{{$candidate['country'] ? ', '.$candidate['country'] : ''}}
                                </div>
                                @endif
                                @if(candidateShow($candidate, 'show_phone'))
                                <div class="section-profile-detail-alpha-user-detail-item">
                                    <i class="fa-solid fa-phone"></i> 
                                    <strong>{{__('message.phone')}}</strong> : {{$candidate['phone1'] ? $candidate['phone1'] : '---'}}
                                </div>
                                @endif
                                @if(candidateShow($candidate, 'show_email'))
                                <div class="section-profile-detail-alpha-user-detail-item">
                                    <i class="fa-solid fa-at"></i> 
                                    <strong>{{__('message.email')}}</strong> : {{$candidate['email'] ? $candidate['email'] : '---'}}
                                </div>
                                @endif
                                <div class="section-profile-detail-alpha-user-detail-item">
                                    <i class="fa-solid fa-hourglass"></i> 
                                    <strong>{{__('message.member_since')}}</strong> : {{dateFormat($candidate['created_at'])}}
                                </div>
                            </div>                
                        </div>
                        @if(candidateShow($candidate, 'show_social_links'))
                        <div class="col-md-12 col-sm-12">
                            <div class="section-profile-detail-alpha-user-detail-social-icons">
                                @if($candidate['twitter_link'])
                                <a class="twitter" href="{{$candidate['twitter_link']}}" target="_blank"><i class="fab fa-twitter"></i></a> 
                                @endif
                                @if($candidate['facebook_link'])
                                <a class="facebook" href="{{$candidate['facebook_link']}}" target="_blank"><i class="fab fa-facebook"></i></a> 
                                @endif
                                @if($candidate['instagram_link'])
                                <a class="instagram" href="{{$candidate['instagram_link']}}" target="_blank"><i class="fab fa-instagram"></i></a> 
                                @endif
                                @if($candidate['google_link'])
                                <a class="google-plus" href="{{$candidate['google_link']}}" target="_blank"><i class="fab fa-google-plus"></i></a> 
                                @endif
                                @if($candidate['linkedin_link'])
                                <a class="linkedin" href="{{$candidate['linkedin_link']}}" target="_blank"><i class="fab fa-linkedin"></i></a>
                                @endif
                                @if($candidate['youtube_link'])
                                <a class="youtube" href="{{$candidate['youtube_link']}}" target="_blank"><i class="fab fa-youtube"></i></a>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <!-- Candidate Overview Section Ends -->
            </div>
        </div>
    </div>
</div>
<!-- Candidate Profile Detail Content Ends -->

@endsection
