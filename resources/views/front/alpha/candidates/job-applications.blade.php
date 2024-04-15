@extends('front'.viewPrfx().'layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.job_applications')}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > 
                            <a href="{{route('front-profile')}}">{{__('message.account')}}</a> > 
                            <a href="{{route('front-acc-job-apps')}}">{{__('message.job_applications')}}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Ends -->

    <div class="account-detail-container">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="account-detail-left-1">
                        @include('front'.viewPrfx().'partials.account-sidebar')
                    </div>
                </div>
                <div class="col-md-9">
                    @if ($jobs)
                    @foreach ($jobs as $job)
                    <div class="row">
                        <div class="col-md-12">
                            <div class="jobs-list-single">
                                <div class="job-list-ribbon-container" title="{{__('message.refer_this_job')}}">
                                    <div class="job-list-ribbon job-list-ribbon-1 up" data-id="job-list-ribbon-1">
                                      <div class="content">
                                        <span class="job-listing-job-info-item refer-job" data-id="{{ encode($job['job_id']) }}">
                                            <i class="fa fa-user-plus"></i>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row align-items-center">
                                    <div class="col-md-2 justify-content-center h-100">
                                        @php $thumb = employerThumb($job['employer_logo'], true); @endphp
                                        <img 
                                            src="{{$thumb['image']}}" 
                                            onerror="this.src='{{$thumb['error']}}'"
                                            alt="{{$job['company']}}" 
                                            class="jobs-list-single-image"
                                        />                                            
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row align-items-center">
                                            <div class="col-md-9">
                                                <h2>
                                                    <a href="{{frontJobLink($job['employer_slug'], $job['separate_site'])}}{{$job['slug']}}"
                                                        target="_blank">
                                                    {{$job['title']}}
                                                    </a>
                                                </h2>
                                            </div>
                                            <div class="col-md-12">
                                                @if($job['department'])
                                                <span class="jobs-list-department">
                                                    <i class="fa-solid fa-building"></i>
                                                    {{$job['department']}}
                                                </span>
                                                @endif
                                                <span class="jobs-list-department">
                                                    <i class="fa-regular fa-clock"></i>
                                                    {{__('message.posted_on')}} : {{date('d M, Y', strtotime($job['created_at']))}}
                                                </span>
                                            </div>
                                            <div class="col-md-12">
                                                @if(isset($job['job_filters']))
                                                @foreach ($job['job_filters'] as $jf)
                                                <span class="jobs-list-attachment">
                                                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                                                    {{ $jf['title'] }} : {{ implode(', ',$jf['values']) }}
                                                </span>
                                                @endforeach
                                                @endif                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="account-detail-left-1">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-sm-12">
                                {{ __('message.no_jobs_found') }}
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            {!! $pagination !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
