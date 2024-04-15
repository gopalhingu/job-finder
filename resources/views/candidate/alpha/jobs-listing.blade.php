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
            <h2><span>{{ __('message.browse_jobs') }}</span></h2>
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
                        <div class="job-detail account-no-content-box">
                            {{ __('message.login_to_view_jobs') }}
                        </div>
                    </div>
                    @else
                    @if($jobs)
                    @foreach ($jobs as $job)
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="job-listing">
                                <p class="job-listing-heading">
                                    <span class="job-listing-heading-text">
                                    <a href="{{ empUrl() }}job/{{ $job['slug'] ? $job['slug'] : $job['job_id'] }}">
                                    {{ $job['title'] }}
                                    </a>
                                    </span>
                                    <span class="job-listing-heading-line"></span>
                                </p>
                                <p class="job-listing-job-info">
                                    <span class="job-listing-job-info-date"><i class="fa fa-clock-o"></i> 
                                    {{ __('message.posted_on') }} : {{ date('d M, Y', strtotime($job['created_at'])) }}
                                    </span>
                                    @if($job['department'])
                                    <span class="job-listing-job-info-date">
                                    <i class="fa fa-bookmark"></i> {{ $job['department'] }}
                                    </span>
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
                                    @php $favorite = in_array($job['job_id'], $jobFavorites) ? 'favorited' : ''; @endphp
                                    <span class="job-listing-job-info-item mark-favorite {{ $favorite }}"
                                        title="{{ $favorite ? __('message.unmark_favorite') : __('message.mark_favorite') }}"
                                        data-id="{{ encode($job['job_id']) }}">
                                    <i class="fa fa-heart"></i></span>
                                    <span class="job-listing-job-info-item refer-job" title="{{ __('message.refer_this_job') }}"
                                        data-id="{{ encode($job['job_id']) }}">
                                    <i class="fa fa-user-plus"></i></span>
                                </p>
                                <div class="job-listing-job-description">
                                    {!! trimString($job['description'], 280) !!}
                                    @if(isset($job['job_filters']))
                                    @foreach ($job['job_filters'] as $jf)
                                    <div classs="job-filter-title-value-wrap">
                                        <span class="job-filter-title">{{ $jf['title'] }}</span>
                                        <span class="job-filter-separator"> : </span>
                                        <span class="job-filter-value">{{ implode(', ',$jf['values']) }}</span>
                                    </div>
                                    @endforeach
                                    @endif
                                    <a href="{{ empUrl() }}job/{{ $job['slug'] ? $job['slug'] : $job['job_id'] }}">{{ __('message.read_more') }}</a>
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
                    @endforeach
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            {!! $pagination !!}
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="job-detail account-no-content-box">
                            {{ __('message.no_jobs_found') }}
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