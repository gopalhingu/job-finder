@extends('candidate.beta.layouts.master')

@section('page-title'){{$page}}@endsection

@section('content')

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
                            <li><a href="{{empUrl()}}">{{__('message.home')}}</a></li>
                            <li>></li>
                            <li><a href="{{empUrl()}}jobs">Jobs</a></li>
                            <li>></li>
                            <li class="active"><a href="{{empUrl()}}job/{{$job['slug']}}">{{$job['title']}}</a></li>
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
<div class="section-job-detail-alpha-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-job-detail-alpha-filters-container">
                    <div class="row">
                        @if(isset($job['job_filters']))
                        @foreach($job['job_filters'] as $jf)                        
                        <div class="col-lg-3 col-md-12 col-sm-12">
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
                @endif


                <div class="section-job-detail-alpha-apply-container">
                    <div class="container">
                        @if(setting('enable_multiple_resume') == 'yes')
                        <div class="row">
                            <div class="col-lg-6 col-md-12 col-sm-12 p-0">
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
                            <div class="col-lg-6 col-md-12 col-sm-12 p-0">
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
                            <div class="col-lg-6 col-md-12 col-sm-12 p-0">
                                @if(candidateSession() && in_array($job['job_id'], $applied))
                                <a href="{{ empUrl() }}account/job-applications" class="btn">
                                    {{__('message.you_have_already_applied')}}
                                </a>
                                @else
                                <a href="{{empUrl()}}account" class="btn global-login-btn">
                                    <i class="fa-solid fa-hand-pointer"></i> {{__('message.signup_to_apply')}}
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                </form>
                @endif

            </div>

        </div>
    </div>
</div>

@endsection