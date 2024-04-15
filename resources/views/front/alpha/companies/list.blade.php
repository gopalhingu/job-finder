@extends('front'.viewPrfx().'layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.companies')}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > <a href="{{route('home').'/companies'}}">{{__('message.companies')}}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Ends -->

    <div class="employers-list-container">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="employers-list-right-top">
                    <div class="row">
                        <div class="col-md-3">
                            {{$pagination_overview}}
                        </div>
                        <div class="col-md-9 text-lg-end">
                            <input type="hidden" id="employers-page" value="{{$page}}">
                            <select class="employers-list-select-sort">
                                <option value="sort_newer" {{sel(app('request')->input('sort'), 'sort_newer')}}>
                                    {{__('message.sort_by')}} {{__('message.newer')}}
                                </option>
                                <option value="sort_older" {{sel(app('request')->input('sort'), 'sort_older')}}>
                                    {{__('message.sort_by')}} {{__('message.older')}}
                                </option>
                                <option value="sort_recent" {{sel(app('request')->input('sort'), 'sort_recent')}}>
                                    {{__('message.sort_by').' '.__('message.recent').' '.__('message.jobs')}}
                                </option>
                                <option value="sort_most" {{sel(app('request')->input('sort'), 'sort_most')}}>
                                    {{__('message.sort_by').' '.__('message.most').' '.__('message.jobs')}}
                                </option>
                            </select>                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="employers-list-right-bar">
                    <div class="row">
                        @if(count($companies) > 0)
                        @foreach($companies as $company)                        
                        <div class="col-md-6">
                            <div class="employers-list-single">
                                <div class="row">
                                    <div class="col-md-2">
                                        @php $thumb = employerThumb($company->logo, true); @endphp
                                        <img 
                                            src="{{$thumb['image']}}" 
                                            onerror="this.src='{{$thumb['error']}}'"
                                            alt="{{$company->company}}" 
                                            class="employers-list-single-image" />
                                    </div>
                                    <div class="col-md-10">
                                        <div class="row align-items-center">
                                            <div class="col-md-7">
                                                <h2>
                                                    <a target="_blank" href="{{frontEmpUrl($company->slug, $company->separate_site)}}">
                                                        {{$company->company}}
                                                    </a>                                                    
                                                </h2>
                                            </div>
                                            <div class="col-md-5 text-lg-end">
                                                <a class="btn btn-job-detail" 
                                                    target="_blank" 
                                                    href="{{frontEmpUrl($company->slug, $company->separate_site)}}jobs">
                                                    <i class="fa-solid fa-suitcase"></i> {{__('message.view').' '.__('message.jobs')}} ({{$company['jobs_count']}})
                                                </a>
                                            </div>
                                            <div class="col-md-12">
                                                <span class="employers-list-department">
                                                    <i class="fa-regular fa-clock"></i>
                                                    {{__('message.since')}} : {{date('d M, Y', strtotime($company['created_at']))}}
                                                </span>
                                            </div>
                                            <div class="col-md-12">
                                                <p>{{$company->short_description ? $company->short_description : __('message.no_description') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {{__('message.no_results')}}
                        </div>
                        @endif
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <nav aria-label="Page navigation example" class="employers-pagination">
                                {!!$pagination!!}
                            </nav>
                        </div>                                                   
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

@endsection
