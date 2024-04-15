@extends('candidate'.viewPrfx().'layouts.master')
@section('page-title'){{$page}}@endsection
@section('content')


@if(settingEmpSlug('enable_home_banner') == 'yes')
@include('candidate.beta.partials.home-banner-absolute')
@endif

@if(settingEmpSlug('before_how_text'))
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            {!! settingEmpSlug('before_how_text') !!}
        </div>
    </div>
</div>
@endif

@if(settingEmpSlug('home_how_it_works') == 'yes')
@include('candidate.beta.partials.home-steps')
@endif

@if(settingEmpSlug('after_how_text'))
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            {!! settingEmpSlug('after_how_text') !!}
        </div>
    </div>
</div>
@endif

@if(settingEmpSlug('home_department_section') == 'yes' && $departments)
@include('candidate.beta.partials.home-departments-section')
@endif

@if(settingEmpSlug('before_blogs_text'))
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            {!! settingEmpSlug('before_blogs_text') !!}
        </div>
    </div>
</div>
@endif

@if(settingEmpSlug('home_blogs_section') == 'yes' && $blogs)
@include('candidate.beta.partials.home-blogs-section')
@endif

@if(settingEmpSlug('after_blogs_text'))
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            {!! settingEmpSlug('after_blogs_text') !!}
        </div>
    </div>
</div>
@endif

@endsection
