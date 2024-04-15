@extends('candidate'.viewPrfx().'layouts.master')
@section('page-title'){{$page}}@endsection
@section('content')
<!--==========================
    Intro Section
============================-->
<section id="intro" class="clearfix front-intro-section">
    <div class="container">
        <div class="intro-img">
        </div>
        <div class="intro-info">
            <h2>
                <span>
                <a href="{{ empUrl() }}jobs">
                {{ __('message.browse_jobs') }}</a> : {{ $job['title'] }}
                </span>
            </h2>
        </div>
    </div>
</section>
<!-- #intro -->
<main id="main">
    <!--==========================
        Account Area Setion
    ============================-->
    <section id="about">
        <div class="container">
            <div class="row mt-10">
                <div class="col-md-3 col-lg-3 col-sm-12">
                    @include('candidate'.viewPrfx().'partials.job-sidebar')
                </div>
                <div class="col-md-9 col-lg-9 col-sm-12">
                @if(settingEmpSlug('display_jobs_to_only_logged_in_users') == 'yes' && !candidateSession())
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="job-detail account-no-content-box">
                                {{ __('message.login_to_view_jobs') }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="job-detail">
                                <p class="job-listing-heading">
                                    <span class="job-listing-heading-text">
                                    <a href="{{ empUrl() }}job/{{ $job['slug'] ? $job['slug'] : encode($job['job_id']) }}">{{ $job['title'] }}</a>
                                    </span>
                                    <span class="job-listing-heading-line"></span>
                                </p>
                                <p class="job-listing-job-info">
                                    <span class="job-listing-job-info-date"><i class="fa fa-clock-o"></i> 
                                    {{ __('message.posted_on') }} : {{ date('d M, Y', strtotime($job['created_at'])) }}
                                    </span>
                                    @if($job['department'])
                                    <span class="job-listing-job-info-date"><i class="fa fa-bookmark"></i> {{ $job['department'] }}</span>
                                    @endif
                                    @if($job['quizes_count'] > 0)
                                    <span class="job-listing-job-info-item" 
                                        title="Requires {{ $job['quizes_count'] }} {{ __('message.quizes_to_be_attempted') }}">
                                    <i class="fa fa-list"></i> {{ $job['quizes_count'] }} {{ __('message.quizes') }}</span>
                                    @endif
                                    @if($job['traites_count'] > 0)
                                    <span class="job-listing-job-info-item" title="Requires {{ $job['traites_count'] }} {{ __('message.traites') }}">
                                    <i class="fa fa-star-half-o"></i> {{ $job['traites_count'] }} {{ __('message.traites') }}</span>
                                    @endif
                                    @php $favorite = in_array($job['job_id'], $favorites) ? 'favorite' : ''; @endphp
                                    <span class="job-listing-job-info-item mark-favorite {{ $favorite ? 'favorited' : ''; }}"
                                        title="{{ $favorite ? 'Unmark' : 'Mark'; }} {{ __('message.favorite') }}"
                                        data-id="{{ encode($job['job_id']) }}">
                                    <i class="fa fa-heart"></i></span>
                                    <span class="job-listing-job-info-item refer-job" title="{{ __('message.refer_this_job') }}"
                                        data-id="{{ encode($job['job_id']) }}">
                                    <i class="fa fa-user-plus"></i></span>
                                </p>
                                <div class="job-detail-job-description">
                                    {!! $job['description'] !!}
                                    @if(isset($job['job_filters']))
                                    @foreach($job['job_filters'] as $jf)
                                    <div classs="job-filter-title-value-wrap">
                                        <span class="job-filter-title">{{ $jf['title'] }}</span>
                                        <span class="job-filter-separator"> : </span>
                                        <span class="job-filter-value">{{ implode(', ',$jf['values']) }}</span>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                                <div class="container">
                                    <div class="row job-listing-items-container">
                                        @if($job['fields'])
                                        @foreach ($job['fields'] as $key => $value)
                                        @if($value['label'])
                                        <div class="col-md-4 col-sm-6 job-listing-items">
                                            <span class="job-listing-items-title" title="{{ $value['label'] }}">
                                            {{ trimString($value['label']) }}
                                            </span>
                                            <span class="job-listing-items-value" title="{{ $value['value'] }}">
                                            {{ trimString($value['value']) }}
                                            </span>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(candidateSession())
                    @if(!in_array($job['job_id'], $applied))
                    <form id="job_apply_form">
                        @if($job['traites'])
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="job-detail">
                                    <p class="job-detail-heading">
                                        <span class="job-detail-heading-text">{{__('message.job_traites')}}</span>
                                        <span class="job-detail-heading-line"></span>
                                    </p>
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
                                                <select class="pill-rating" name="traites[{{ encode($traite['id']) }}]" autocomplete="off">
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
                        @endif
                        @if(setting('enable_multiple_resume') == 'yes')
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <div class="job-detail">
                                    <p class="job-detail-heading">
                                        <span class="job-detail-heading-text">{{ __('message.apply_for_this_job') }}</span>
                                        <span class="job-detail-heading-line"></span>
                                    </p>
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
                                                <button href="#" type="submit" class="btn btn-primary" 
                                                    title="Apply" id="job_apply_form_button">{{ __('message.apply') }}</button>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                <input type="hidden" name="resume" value="{{ encode($resume_id) }}">
                                <input type="hidden" name="job_id" value="{{ encode($job['job_id']) }}">
                                <button href="#" type="submit" class="btn btn-primary" 
                                    title="Apply" id="job_apply_form_button">{{ __('message.apply') }}</button>
                                <br /><br />
                            </div>
                        </div>
                        @endif
                    </form>
                    @else
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="job-detail account-no-content-box">
                                {{ __('message.you_have_already_applied') }}<br />
                                <a href="{{ empUrl() }}account/job-applications">{{ __('message.go_to_job_applications') }}</a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @else
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="job-detail account-no-content-box">
                                {{ __('message.you_need_to_be_logged_in') }}<br />
                                <a href="{{ empUrl() }}login">{{ __('message.login') }}</a>
                            </div>
                        </div>
                    </div>
                    @endif
                @endif
                </div>
            </div>
        </div>
    </section>
    <!-- #account area section ends -->
</main>
@endsection