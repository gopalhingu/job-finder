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
            <h2><span>{{ __('message.blog_posts') }}</span></h2>
        </div>
    </div>
</section>
<!-- #intro -->
<main id="main">
    <!--==========================
        Content Section
    ============================-->
    <section id="about">
        <div class="container">
            <div class="row mt-10">
                <div class="col-md-9 col-lg-9 col-sm-12">
                    <div class="row">
                        @if($blogs)
                        @foreach($blogs as $blog)
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="blog-listing">
                                <p class="blog-listing-heading">
                                    <span class="blog-listing-heading-text">
                                    <a href="{{ empUrl() }}blog/{{ encode($blog['blog_id']) }}">
                                    {{ trimString($blog['title'], 50) }}
                                    </a>
                                    </span>
                                    <span class="blog-listing-heading-line"></span>
                                </p>
                                <div class="blog-listing-blog-description">
                                    {!! trimString($blog['description'], 500) !!}
                                    <a href="{{ empUrl() }}blog/{{ encode($blog['blog_id']) }}">{{ __('message.read_more') }}</a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @else
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="blog-listing">
                                <p class="blog-listing-heading">
                                    {{ __('message.no_post_found') }}!
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="col-md-12 col-lg-12 col-sm-12">
                        {!! $pagination !!}
                    </div>
                </div>
                <div class="col-lg-3">
                    @include('candidate.alpha.partials.blog-sidebar')
                </div>
            </div>
        </div>
    </section>
    <!-- #account area section ends -->
</main>
@endsection