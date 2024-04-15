@extends('front'.viewPrfx().'layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.news')}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > 
                            <a href="{{route('home').'/news'}}">{{__('message.news')}}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Ends -->

    <div class="news-list-container">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="news-list-left-bar">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="news-list-filter">
                                    <h3>{{__('message.keywords')}}</h3>
                                    <input type="hidden" id="news-page" value="{{$page}}">
                                    <input type="hidden" id="selected-category" value="{{$selected_category}}">
                                    <input type="text" name="search" id="news-search-input" value="{{$search}}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="news-list-filter">
                                    <h3>{{__('message.categories')}}</h3>
                                    <ul>
                                        @foreach($categories as $key => $category)
                                        <li>
                                            <input 
                                                type="radio" 
                                                class="filter-radio-checkbox news-category-check" 
                                                id="test{{$key}}" 
                                                value="{{encode($category['category_id'])}}" 
                                                {{selCb($category['category_id'], $selected_category)}} 
                                            />
                                            <label for="test{{$key}}">{{$category['title']}}</label>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="news-list-right-bar">
                        <div class="row">
                            @if(count($news) > 0)
                            @foreach($news as $n)
                            <div class="col-md-12">
                                <div class="news-list-single">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row align-items-center">
                                                <div class="col-md-9">
                                                    <h2><a href="{{route('front-news-detail', $n->slug)}}">{{$n->title}}</a></h2>
                                                </div>
                                                <div class="col-md-3 text-lg-end">
                                                    <a class="btn btn-job-detail" href="{{route('front-news-detail', $n->slug)}}">
                                                        {{__('message.more_detail')}}
                                                    </a>
                                                </div>
                                                <div class="col-md-12">
                                                    <span class="news-list-time">
                                                        <i class="fa-regular fa-clock"></i>
                                                        {{__('message.posted_on')}} : {{date('d M, Y', strtotime($n->created_at))}}
                                                    </span>
                                                </div>
                                                <div class="col-md-12">
                                                    <p>
                                                        {{$n->summary}}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="news-list-single">
                                    {{__('message.no_results')}}
                                </div>
                            </div>
                            @endif
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="news-pagination">
                                    {!!$pagination!!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
