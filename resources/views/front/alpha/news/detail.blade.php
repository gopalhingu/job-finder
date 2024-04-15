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
                            <a href="{{route('home').'/news'}}">{{__('message.news')}}</a> > 
                            {{$page_title}}
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
                                    <input type="text" name="search" id="news-search-input" value="" />
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
                            <div class="col-md-12">
                                <div class="news-list-single news-list-detail">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="row align-items-center">
                                                <div class="col-md-9">
                                                    <h2>{{$news['title']}}</h2>
                                                </div>
                                                <div class="col-md-3 text-lg-end">
                                                </div>
                                                <div class="col-md-12">
                                                    <span class="news-list-time">
                                                        <i class="fa-regular fa-clock"></i>
                                                        {{__('message.posted_on')}} : {{date('d M, Y', strtotime($news['created_at']))}}
                                                    </span>
                                                </div>
                                                <div class="col-md-12">
                                                    {!! removeUselessLineBreaks($news['description']) !!}
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
    </div>

@endsection
