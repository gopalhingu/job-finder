@extends('candidate'.viewPrfx().'layouts.master')
@section('page-title'){{$page}}@endsection
@section('content')
<!--==========================
    Intro Section
============================-->
@if (settingEmpSlug('enable_home_banner') == 'yes')
<section id="intro" class="clearfix">
    <div class="container">
        <div class="intro-img">
        </div>
        <div class="intro-info">
            {!! settingEmpSlug('banner_text') !!}
        </div>
    </div>
</section>
<!-- #intro -->
@endif
<main id="main">
    @if (settingEmpSlug('before_how_text'))
    <section id="home-custom-content">
        <div class="container">
            <div class="row row-eq-height justify-content-center">
                <div class="col-lg-12 mb-4">
                    {!! settingEmpSlug('before_how_text') !!}
                </div>
            </div>
        </div>
    </section>
    @endif
    @if (settingEmpSlug('home_how_it_works') == 'yes')
    <!--==========================
        How It Works Section
    ============================-->
    <section id="how-it-works">
        <div class="container">
            <header class="section-header">
                <h3>{{ __('message.how_it_works') }}</h3>
                <p>{{ __('message.follow_three_simple_steps') }}</p>
            </header>
            <div class="row row-eq-height justify-content-center">
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <i class="fa fa-plus"></i>
                        <div class="card-body">
                            <h5 class="card-title">{{ __('message.create_account') }}</h5>
                            <p class="card-text">{{ __('message.simply_login_with_existing') }}.</p>
                            <a href="{{ empUrl() }}login" class="readmore">{{ __('message.more') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <i class="fa fa-search"></i>
                        <div class="card-body">
                            <h5 class="card-title">{{ __('message.find_job') }}</h5>
                            <p class="card-text">{{ __('message.find_job_that_best_matches') }}</p>
                            <a href="{{ empUrl() }}jobs" class="readmore">{{ __('message.more') }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <i class="fa fa-check"></i>
                        <div class="card-body">
                            <h5 class="card-title">{{ __('message.apply') }}</h5>
                            <p class="card-text">{{ __('message.fulfill_the_requirements') }}.</p>
                            <a href="{{ empUrl() }}blogs" class="readmore">{{ __('message.more') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    @if (settingEmpSlug('after_how_text'))
    <section id="home-custom-content">
        <div class="container">
            <div class="row row-eq-height justify-content-center">
                <div class="col-lg-12 mb-4">
                    {!! settingEmpSlug('after_how_text') !!}
                </div>
            </div>
        </div>
    </section>
    @endif
    @if(settingEmpSlug('home_department_section') == 'yes' && $departments)
    <!--==========================
        Departments Section
    ============================-->
    <section id="departments" class="section-bg">
        <div class="container">
            <header class="section-header">
                <h3>{{ __('message.search_jobs_by_department') }}</h3>
                <p>{{ __('message.select_any_department_to_view') }}.</p>
            </header>
            <div class="row">
                @foreach($departments as $department)
                <div class="col-md-4 col-lg-4 sol-sm-12">
                    <div class="box">
                        <a href="{{ empUrl() }}jobs?search=&departments={{ encode($department['department_id']) }}">
                            <h4 class="title">{{ $department['title'] }}</h4>
                        </a>
                        @if($department['image'])
                        @php $thumb = departmentThumb($department['image']); @endphp
                        <a href="{{ empUrl() }}jobs?search=&departments={{ encode($department['department_id']) }}">
                            <img class="department-image" src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'"/>
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- #departments -->
    @endif
    @if(settingEmpSlug('before_blogs_text'))
    <section id="home-custom-content">
        <div class="container">
            <div class="row row-eq-height justify-content-center">
                <div class="col-lg-12 mb-4">
                    {!! settingEmpSlug('before_blogs_text') !!}
                </div>
            </div>
        </div>
    </section>
    @endif
    @if(settingEmpSlug('home_blogs_section') == 'yes' && $blogs)
    <!--==========================
        Blogs Section
    ============================-->
    <section id="news-section">
        <div class="container">
            <header class="section-header">
                <h3>{{ __('message.blogs') }}</h3>
            </header>
            <div class="row row-eq-height justify-content-center">
                @foreach($blogs as $blog)
                <div class="col-lg-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $blog['title'] }}</h5>
                            <p class="card-text">{!! trimString($blog['description'], 100) !!}.</p>
                            <a href="{{ empUrl() }}blog/{{ encode($blog['blog_id']) }}" class="readmore">
                                {{ __('message.more') }}
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @if(settingEmpSlug('after_blogs_text'))
    <section id="home-custom-content">
        <div class="container">
            <div class="row row-eq-height justify-content-center">
                <div class="col-lg-12 mb-4">
                    {!! settingEmpSlug('after_blogs_text') !!}
                </div>
            </div>
        </div>
    </section>
    @endif
</main>
@endsection