@extends('front'.viewPrfx().'layouts.master')

@section('content')

        <!-- Breadcrumb Section Starts -->
        <div class="breadcrumbs">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <h2>{{__('message.jobs')}}</h2>
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

        <div class="jobs-list-container">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="jobs-list-left-bar">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12">
                                <div class="jobs-list-filter">
                                    <h3>{{__('message.keywords')}}</h3>
                                    <ul>
                                        <li>
                                            <input type="text" name="search" value="{{$search}}" 
                                                id="jobs-search-input" class="job-search-value" />
                                            <input type="hidden" id="jobs-page" value="{{$page}}">
                                            <input type="hidden" id="selected-company" value="{{$selected_company}}">
                                        </li>
                                    </ul>
                                    @if(count($companies) > 0 && setting('display_employers_front') == 'yes')
                                    <h3>{{__('message.companies')}}</h3>
                                    <ul>
                                        @foreach($companies as $k => $c)
                                        <li>
                                            <input 
                                                type="checkbox" 
                                                class="filter-radio-checkbox jobs-company-check" 
                                                value="{{encode($c['employer_id'], false)}}" 
                                                id="test{{$k}}" 
                                                {{ jobsCheckboxSel($companiesSel, $c['employer_id']) }} 
                                            />
                                            <label class="" for="test{{$k}}"> {{$c->company}} <span class="label-count"></span></label>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                    @if(count($departments) > 0 && setting('display_departments_front') == 'yes')
                                    <h3>{{__('message.departments')}}</h3>
                                    <ul>
                                        @foreach($departments as $k => $c)
                                        <li>
                                            <input 
                                                type="checkbox" 
                                                class="filter-radio-checkbox jobs-department-check" 
                                                id="test{{$k}}" 
                                                value="{{ encode($c['department_id']) }}" 
                                                {{ jobsCheckboxSel($departmentsSel, $c['department_id']) }} 
                                            />
                                            <label for="test{{$k}}"> {{$c['title']}}<span class="label-count"></span></label>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @endif
                                    @if($job_filters)
                                    <input type="hidden" id="job_filters_sel" value='{{ $filtersEncoded }}' />
                                    @foreach($job_filters as $k => $jf)
                                    <h3>{{$jf['title']}}</h3>
                                    <ul class="job-filter" data-id="{{ encode($jf['job_filter_id'], false) }}">
                                        <li>
                                        @if($jf['type'] != 'dropdown')
                                            @foreach($jf['values'] as $key => $value)
                                            @php 
                                                $job_filter_value_ids = isset($filtersSel[$jf['job_filter_id']]) ? $filtersSel[$jf['job_filter_id']] : array(); 
                                                $selCb = sel3($jf['job_filter_id'], $value['id'], $filtersSel);
                                            @endphp                                            
                                                <input 
                                                    type="checkbox" 
                                                    class="job-filter-check filter-radio-checkbox" 
                                                    value="{{ encode($value['id'], false) }}" 
                                                    data-id="{{ encode($jf['job_filter_id'], false) }}"
                                                    {{ $selCb }}
                                                />
                                                <label for="test{{$k}}"> 
                                                    {{ trimString($value['title']) }}<span class="label-count"></span>
                                                </label>
                                            @endforeach
                                        @else
                                            <select class="jobs-filter-dropdown job-filter-dd" data-id="{{ encode($jf['job_filter_id'], false) }}">
                                                <option value="">{{__('message.none')}}</option> 
                                                @foreach($jf['values'] as $key => $value)
                                                @php 
                                                    $selDd = sel3($jf['job_filter_id'], $value['id'], $filtersSel);
                                                @endphp                                                
                                                    <option value="{{encode($value['id'], false)}}" {{ $selDd ? 'selected' : ''; }}>{{$value['title']}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                        </li>
                                    </ul>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="jobs-list-right-bar">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="jobs-list-right-top">
                                    <div class="row">
                                        <div class="col-md-3">
                                            {{$pagination_overview}} {{__('message.jobs')}}
                                        </div>
                                        <div class="col-md-9 text-lg-end">
                                            <select class="jobs-list-select-sort">
                                                <option value="sort_newer" {{sel(app('request')->input('sort'), 'sort_newer')}}>Sort by Newer</option>
                                                <option value="sort_older" {{sel(app('request')->input('sort'), 'sort_older')}}>Sort by Older</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($jobs)
                            @foreach($jobs as $j)
                            <div class="col-md-12">
                                <div class="jobs-list-single">
                                    <div class="job-list-ribbon-container" title="{{__('message.refer_this_job')}}">
                                        <div class="job-list-ribbon job-list-ribbon-1 up" data-id="job-list-ribbon-1">
                                          <div class="content">
                                            <span class="job-listing-job-info-item refer-job" data-id="{{ encode($j['job_id']) }}">
                                                <i class="fa fa-user-plus"></i>
                                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    @php $favorite = in_array($j['job_id'], $favorites) ? 'favorited' : ''; @endphp
                                    <div class="job-list-ribbon-container" 
                                        title="{{ $favorite ? __('message.unmark_favorite') : __('message.mark_favorite') }}">
                                        <div class="job-list-ribbon job-list-ribbon-2 up" data-id="job-list-ribbon-2">
                                            <div class="content">
                                                <span class="job-list-heart mark-favorite {{ $favorite }}" 
                                                    data-id="{{ encode($j['job_id']) }}">
                                                    <i class="fa fa-heart"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-2 justify-content-center h-100">
                                            @php $thumb = employerThumb($j['employer_logo'], true); @endphp
                                            <img 
                                                src="{{$thumb['image']}}" 
                                                onerror="this.src='{{$thumb['error']}}'"
                                                alt="{{$j['company']}}" 
                                                class="jobs-list-single-image"
                                            />                                            
                                        </div>
                                        <div class="col-md-10">
                                            <div class="row align-items-center">                                             
                                                <div class="col-md-9">
                                                    <h2>
                                                        <a href="{{frontJobLink($j['employer_slug'], $j['separate_site'])}}{{$j['slug'] ? $j['slug'] : encode($j['job_id'])}}"
                                                            target="_blank">
                                                        {{$j['title']}}
                                                        </a>
                                                        <a class="btn btn-job-detail" href="{{frontJobLink($j['employer_slug'], $j['separate_site'])}}{{ $j['slug'] ? $j['slug'] : encode($j['job_id']) }}">
                                                        {{__('message.apply')}}
                                                    </a>
                                                    </h2>
                                                </div>
                                                <div class="col-md-12">
                                                    @if($j['department'])
                                                    <span class="jobs-list-department">
                                                        <i class="fa-solid fa-building"></i>
                                                        {{$j['department']}}
                                                    </span>
                                                    @endif
                                                    <span class="jobs-list-department">
                                                        <i class="fa-regular fa-clock"></i>
                                                        {{__('message.posted_on')}} : {{date('d M, Y', strtotime($j['created_at']))}}
                                                    </span>
                                                </div>
                                                <div class="col-md-12">
                                                    @if(isset($j['job_filters']))
                                                    @foreach ($j['job_filters'] as $jf)
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
                            @endforeach
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="jobs-pagination">
                                    <nav aria-label="Page navigation example">
                                        {!!$pagination!!}
                                    </nav>
                                </div>
                            </div>
                            @else
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="jobs-pagination">
                                    {{__('message.no_results')}}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>

@endsection
