@extends('front'.viewPrfx().'layouts.master')

@section('content')

        <!-- Breadcrumb Section Starts -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <h2>{{ $job['title'] }}</h2>
                    </div>
                    <div class="col-md-3">
                        <div class="breadcrumbs-text-right">
                            <p class="text-lg-end">
                                <a href="{{route('home')}}">{{__('message.home')}}</a> > <a href="{{route('home').'/jobs'}}">{{__('message.jobs')}}</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Breadcrumb Section Ends -->

        <div class="jobs-detail-container">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <div class="jobs-detail-left-1">
                        {!! $job['description'] !!}
                    </div>
                    @if(candidateSession())
                        @if(!in_array($job['job_id'], $applied))
                            @if($job['traites'])
                            <div class="jobs-detail-left-2">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="job-detail">
                                            <h3>{{__('message.job_traites')}}</h3>
                                            <p class="job-detail-job-description">
                                                {{ __('message.please_rate_yourself') }}
                                            </p>
                                            <div class="job-detail-traits-row-container">
                                            <div class="row">
                                                @foreach ($job['traites'] as $traite)
                                                <div class="col-md-6 col-lg-6 col-sm-12">
                                                    <p class="job-detail-job-traite">
                                                        <span class="job-detail-job-traite-title">{{ $traite['title'] }}</span>
                                                        <input type="hidden" name="traite_titles[{{ encode($traite['id']) }}]" value="{{ $traite['title'] }}">
                                                        <select name="traites[{{ encode($traite['id']) }}]" autocomplete="off" class="form-control">
                                                            <option value="0">{{ __('message.n_a') }}</option>
                                                            <option value="1">{{ __('message.poor') }}</option>
                                                            <option value="2">{{ __('message.bad') }}</option>
                                                            <option value="3">{{ __('message.average') }}</option>
                                                            <option value="4">{{ __('message.good') }}</option>
                                                            <option value="5">{{ __('message.excellent') }}</option>
                                                        </select>
                                                    </p>
                                                </div>
                                                @endforeach
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <form id="job_apply_form">
                            @if(setting('enable_multiple_resume') == 'yes')
                            <div class="jobs-detail-left-2">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="job-detail">
                                            <h3>{{ __('message.apply_for_this_job') }}</h3>
                                            <div class="row">
                                                <div class="col-md-12 col-lg-12 col-sm-12">
                                                    <p class="job-detail-job-traite">
                                                        <label>{{ __('message.please_select_one_of_your_resume') }}</label>
                                                        <input type="hidden" name="job_id" value="{{ encode($job['job_id']) }}">
                                                        <select class="form-control" name="resume" autocomplete="off">
                                                            @foreach ($resumes as $resume)
                                                            <option value="{{ encode($resume['resume_id']) }}">{{ $resume['title'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    </p>
                                                </div>
                                                <div class="col-md-12 col-lg-12 col-sm-12">
                                                    <p class="job-detail-job-traite">
                                                        <button 
                                                            href="#" 
                                                            type="submit" 
                                                            class="btn front-btn-apply" 
                                                            title="Apply" 
                                                            id="job_apply_form_button">
                                                            {{ __('message.apply') }}
                                                        </button>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <br />
                                    <input type="hidden" name="resume" value="{{ encode($resume_id) }}">
                                    <input type="hidden" name="job_id" value="{{ encode($job['job_id']) }}">
                                    <button href="#" type="submit" class="btn front-btn-apply" 
                                        title="Apply" id="job_apply_form_button">{{ __('message.apply') }}</button>
                                    <br /><br />
                                </div>
                            </div>
                            @endif
                            </form>
                        @else
                            <div class="jobs-detail-left-2">
                                <div class="row">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <div class="job-detail account-no-content-box">
                                            {{ __('message.you_have_already_applied') }}<br />
                                            <a href="{{ url('') }}/account/job-applications">
                                            {{ __('message.go_to_job_applications') }}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <div class="jobs-detail-left-2">
                            <div class="row">
                                <div class="col-md-12 col-lg-12 col-sm-12">
                                    <div class="job-detail account-no-content-box">
                                        {{ __('message.you_need_to_be_logged_in_as_candidate') }}<br />
                                        @if(employerSession())
                                        <a href="{{route('employer-logout')}}" class="btn btn-cf-general">
                                            {{__('message.logout')}}
                                        </a>
                                        @else
                                        <a href="#" class="header-btn-login btn btn-cf-general">{{ __('message.login') }}</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="col-md-3">
                    <div class="jobs-detail-right-1">
                        <h3>{{__('message.job')}} {{__('message.details')}}</h3>
                        <ul class="filters">
                            <li>
                                <i class="fa-regular fa-clock"></i> &nbsp;<strong>{{__('message.posted_on')}}</strong><br />
                                <ul class="filter-values">
                                    <li>{{date('d M, Y', strtotime($job['created_at']))}}</li>
                                </ul>                     
                            </li>
                            @if($job['department'])
                            <li>
                                <i class="fa-regular fa-building"></i> &nbsp;<strong>{{__('message.department')}}</strong><br />
                                <ul class="filter-values">
                                    <li>{{ $job['department'] }}</li>
                                </ul>                                
                            </li>
                            @endif
                            @if($job['quizes_count'] > 0)
                            <li>
                                <i class="far fa-list-alt"></i> 
                                <strong>
                                    {{__('message.quizes')}} <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="{{__('message.quizes_front_msg')}}"></i>
                                </strong><br />
                                <ul class="filter-values">
                                    <li>{{ $job['quizes_count'] }} {{ __('message.quizes_to_be_attempted') }}</li>
                                </ul>                                
                            </li>
                            @endif
                            @if($job['traites_count'] > 0)
                            <li>
                                <i class="fas fa-star-half-alt"></i> 
                                <strong>
                                    {{__('message.traites')}} <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="{{__('message.traites_front_msg')}}"></i>
                                </strong><br />
                                <ul class="filter-values">
                                    <li>{{ $job['traites_count'] }} {{ __('message.traites').' '.__('message.required') }}</li>
                                </ul>                                
                            </li>
                            @endif
                        </ul>
                        @if(issetVal($job, 'job_filters'))
                        <ul class="filters">
                            @foreach($job['job_filters'] as $filter)
                            <li>
                                <i class="fa fa-paperclip" aria-hidden="true"></i> <strong>{{ $filter['title'] }}</strong><br />
                                <ul class="filter-values">
                                @foreach($filter['values'] as $val)
                                    <li>{{$val}}</li>
                                @endforeach
                                </ul>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    <div class="jobs-detail-right-2">
                        <h3>{{__('message.employer')}} {{__('message.details')}}</h3>
                        <ul class="filters">
                            <li>
                                @php $thumb = employerThumb($job['employer_logo']); @endphp
                                <img 
                                    src="{{$thumb['image']}}" 
                                    onerror="this.src='{{$thumb['error']}}'"
                                    alt="{{$job['company']}}" 
                                    class="jobs-list-single-image"
                                />                                            
                                <strong>{{$job['company']}}</strong>
                            </li>
                            <li>
                                {{$job['short_description']}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>

@endsection
