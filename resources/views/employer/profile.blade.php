@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper Starts -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fas fa-cube"></i> {{ __('message.update_profile') }}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fas fa-cube"></i> {{ __('message.update_profile') }}</li>
        </ol>
    </section>
    <!-- Main content Starts-->
    <section class="content">
        <!-- Main row Starts-->
        <div class="row">
            <section class="col-lg-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">{{ __('message.profile') }}</h3>
                    </div>
                    <form id="employer_profile_form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.first_name') }}</label>
                                        <input type="text" class="form-control" name="first_name" value="{{ $profile['first_name'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.last_name') }}</label>
                                        <input type="text" class="form-control" name="last_name" value="{{ $profile['last_name'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.email') }}</label>
                                        <input type="text" class="form-control" name="email" value="{{ $profile['email'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.phone1') }}</label>
                                        <input type="text" class="form-control" name="phone1" value="{{ $profile['phone1'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.phone2') }}</label>
                                        <input type="text" class="form-control" name="phone2" value="{{ $profile['phone2'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.city') }}</label>
                                        <input type="text" class="form-control" name="city" value="{{ $profile['city'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.state') }}</label>
                                        <input type="text" class="form-control" name="state" value="{{ $profile['state'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.country') }}</label>
                                        <input type="text" class="form-control" name="country" value="{{ $profile['country'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.address') }}</label>
                                        <input type="text" class="form-control" name="address" value="{{ $profile['address'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.date_of_birth') }}</label>
                                        <input type="date" class="form-control" name="dob" value="{{ date('Y-m-d', strtotime($profile['dob'])) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.image') }}</label>
                                        @php $thumb = employerThumb($profile['image']); @endphp
                                        <input type="file" class="form-control dropify" name="image" 
                                            data-default-file="{{$thumb['image']}}" />
                                    </div>
                                </div>
                                @if(employerSession('type') == 'main')
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.logo') }}</label>
                                        @php $thumb = employerThumb($profile['logo'], true); @endphp
                                        <input type="file" class="form-control dropify" name="logo" 
                                            data-default-file="{{$thumb['image']}}" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.no_of_employees') }}</label>
                                        <input type="text" class="form-control" name="no_of_employees" value="{{ $profile['no_of_employees'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.industry') }}</label>
                                        <input type="text" class="form-control" name="industry" value="{{ $profile['industry'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.founded_in') }}</label>
                                        <input type="text" class="form-control" name="founded_in" value="{{ $profile['founded_in'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.url') }}</label>
                                        <input type="url" class="form-control" name="url" value="{{ $profile['url'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.twitter') }}</label>
                                        <input type="url" class="form-control" name="twitter_link" value="{{ $profile['twitter_link'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.facebook') }}</label>
                                        <input type="url" class="form-control" name="facebook_link" value="{{ $profile['facebook_link'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.instagram') }}</label>
                                        <input type="url" class="form-control" name="instagram_link" value="{{ $profile['instagram_link'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.google') }}</label>
                                        <input type="url" class="form-control" name="google_link" value="{{ $profile['google_link'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.linkedin') }}</label>
                                        <input type="url" class="form-control" name="linkedin_link" value="{{ $profile['linkedin_link'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>{{ __('message.youtube') }}</label>
                                        <input type="url" class="form-control" name="youtube_link" value="{{ $profile['youtube_link'] }}">
                                    </div>
                                </div>
                                @endif
                            </div>
                            <!-- /.form group -->
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="employer_profile_form_button">{{ __('message.save') }}</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
        <!-- Main row Ends-->
    </section>
    <!-- Main content Ends-->
</div>
<!-- Content Wrapper Ends -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/team.js"></script>
@endsection
