@extends('candidate.beta.layouts.master')

@section('page-title'){{$page}}@endsection

@section('breadcrumb')
@include('candidate.beta.partials.breadcrumb')
@endsection

@section('content')

<div class="section-sidebar-beta jobs-list-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12 col-sm-12">
                @include('candidate'.viewPrfx().'partials.job-sidebar')
            </div>
            <div class="col-lg-9 col-md-12 col-sm-12">
                <div class="section-jobs-alpha">
                    <div class="container">
                        @if($jobs)
                        <div class="row">
                            @foreach($jobs as $job)
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="section-jobs-alpha-item">
                                    <div class="row h-100 align-items-center">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="section-jobs-alpha-item-right">
                                                <div class="section-jobs-alpha-item-right-controls">
                                                    @if(in_array($job['job_id'], $favorites))
                                                    <i class="fa-solid fa-heart mark-favorite favorited" 
                                                        title="{{ __('message.unmark_favorite') }}"
                                                        data-id="{{encode($job['job_id'])}}"></i>
                                                    @else
                                                    <i class="fa-regular fa-heart mark-favorite" 
                                                        title="{{ __('message.mark_favorite') }}" 
                                                        data-id="{{encode($job['job_id'])}}"></i>
                                                    @endif
                                                    <i class="fa-regular fa-paper-plane refer-job" data-id="{{encode($job['job_id'])}}"></i>
                                                </div>
                                                <div class="section-jobs-alpha-item-right-heading">
                                                    <a href="{{ empUrl() }}job/{{ $job['slug'] ? $job['slug'] : $job['job_id'] }}">
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

@endsection
