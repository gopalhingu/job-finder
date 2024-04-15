@extends('candidate'.viewPrfx().'layouts.master')
@section('page-title'){{$page ?? ''}}@endsection
@section('content')
<!--==========================
    Intro Section
============================-->
<section id="intro" class="clearfix front-intro-section">
    <div class="container">
        <div class="intro-img">
        </div>
        <div class="intro-info">
            <h2><span>{{__('message.error')}}</span></h2>
        </div>
    </div>
</section>
<!-- #intro -->
<main id="main">
    <!--==========================
        Account Area Setion
    ============================-->
    <section class="main-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    {{__('message.user_exist_with_this_email')}}
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <br />
                </div>
            </div>
        </div>
    </section>
    <!-- #account area section ends -->
</main>
@endsection