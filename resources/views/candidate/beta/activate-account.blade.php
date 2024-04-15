@extends('candidate.beta.layouts.master')

@section('page-title'){{$page}}@endsection

@section('breadcrumb')
@include('candidate.beta.partials.breadcrumb')
@endsection

@section('content')

    <div class="front-forgot-password-container">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="section-headline text-center register-page-hading">
                        <br /><br /><br />
                        <p>{{__('message.account_activated')}}. <br /><a href="{{ empUrl() }}login">{{__('message.login')}}</a> {{__('message.with_your_cred')}}.</p >
                        <br /><br /><br /><br /><br />
                    </div>
                </div>
            </div>
        </div>
    </div>  

@endsection
