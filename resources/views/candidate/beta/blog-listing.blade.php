@extends('candidate.beta.layouts.master')

@section('page-title'){{$page}}@endsection

@section('breadcrumb')
@include('candidate'.viewPrfx().'partials.blogs-search')
@endsection

@section('content')

<div class="section-blogs-alpha">
    <div class="container">
        @if(count($blogs) > 0)
        <div class="row">
            @foreach($blogs as $blog)
            <div class="col-lg-4 col-md-12 col-sm-12">
                <div class="section-blogs-alpha-item">
                    <div class="row align-items-center">
                        <div class="col-md-12 col-sm-12">
                            <div class="section-blogs-alpha-item-image">
                                <div class="section-blogs-alpha-item-date">
                                    <i class="fa-regular fa-calendar"></i> {{timeAgoByTimeStamp($blog['created_at'])}}
                                </div>
                                @php $thumb = newsThumb(''); @endphp
                                <img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
                            </div>
                            <div class="section-blogs-alpha-item-heading">
                                <a href="{{ empUrl() }}blog/{{ encode($blog['blog_id']) }}"><div class="section-blogs-alpha-item-more" title="{{__('message.read_more')}}">&#62;</div></a>
                                <a href="{{ empUrl() }}blog/{{ encode($blog['blog_id']) }}"><h2>{{$blog['title']}}</h2></a>
                            </div>
                            <div class="section-blogs-alpha-item-content">
                                <p>{!! trimString($blog['description'], 200) !!}</p>
                            </div>
                            <div class="section-blogs-alpha-item-bottom">
                                <div class="section-blogs-alpha-item-bottom-right">
                                    <span>{{$blog['category']}}</span>
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
                <p>{{__('message.no_results')}}</p>
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

@endsection
