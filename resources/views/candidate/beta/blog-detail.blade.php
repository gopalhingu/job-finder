@extends('candidate.beta.layouts.master')

@section('page-title'){{$page}}@endsection

@section('breadcrumb')
@include('candidate'.viewPrfx().'partials.blogs-search')
@endsection

@section('content')

<style type="text/css">
:root {
--blog-banner:url({{$image;}});
}   
</style>

<!-- <div class="container-fluid section-blogs-detail-alpha-item-image-spreaded"></div> -->
<div class="section-blogs-detail-alpha">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-blogs-detail-alpha-item">
                    <div class="row align-items-center">
                        <div class="col-md-12 col-sm-12">
                            <div class="section-blogs-detail-alpha-item-heading">
                                <h2>{{$blog['title']}}</h2>
                            </div>
                            @if($image)
                            <div class="section-blogs-detail-alpha-item-image"></div>
                            @endif
                            <div class="section-blogs-detail-alpha-item-detail-info">
                                <div class="section-blogs-detail-alpha-item-date">
                                    <i class="fa fa-calendar"></i> {{timeAgoByTimeStamp($blog['created_at'])}}
                                </div>
                                <div class="section-blogs-detail-alpha-item-detail-info-right">
                                    {{__('message.in')}} <span>{{$blog['category']}}</span>
                                </div>
                            </div>

                            <div class="section-blogs-detail-alpha-item-content">
                                {!!$blog['description']!!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
