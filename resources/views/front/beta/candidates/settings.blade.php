@extends('front'.viewPrfx().'layouts.master')

@section('breadcrumb')
@include('front'.viewPrfx().'partials.breadcrumb')
@endsection

@section('content')

<!-- Account Section Starts -->
<div class="section-account-alpha-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="section-account-alpha-navigation">
                    @include('front'.viewPrfx().'partials.account-sidebar')
                </div>
            </div>
            <div class="col-md-9">
                <div class="section-account-alpha-profile">
                    <!-- Account Profile Form Starts -->
                    <form class="form" id="settings_update_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_profile') }}</label>
                                    <select name="show_profile" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_profile'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_profile'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_profile') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_bio') }}</label>
                                    <select name="show_bio" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_bio'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_bio'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_bio') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_location') }}</label>
                                    <select name="show_location" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_location'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_location'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_location') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_email') }}</label>
                                    <select name="show_email" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_email'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_email'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_email') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_phone') }}</label>
                                    <select name="show_phone" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_phone'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_phone'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_phone') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_social_links') }}</label>
                                    <select name="show_social_links" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_social_links'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_social_links'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_social_links') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_experiences') }}</label>
                                    <select name="show_experiences" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_experiences'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_experiences'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_experiences') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_qualifications') }}</label>
                                    <select name="show_qualifications" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_qualifications'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_qualifications'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_qualifications') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_achievements') }}</label>
                                    <select name="show_achievements" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_achievements'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_achievements'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_achievements') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_skills') }}</label>
                                    <select name="show_skills" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_skills'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_skills'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_skills') }}</small>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.show_languages') }}</label>
                                    <select name="show_languages" class="form-control shadow-none border-none">
                                        <option value="yes" {{ $candidate['show_languages'] == 'yes' ? 'selected' : ''; }}>
                                            {{ __('message.yes') }}
                                        </option>
                                        <option value="no" {{ $candidate['show_languages'] == 'no' ? 'selected' : ''; }}>
                                            {{ __('message.no') }}
                                        </option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.show_languages') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 text-center">
                                <div class="form-group form-group-account">
                                    <button type="submit" class="btn btn-general" title="Save" id="settings_update_form_button">
                                        <i class="fas fa-save"></i> {{ __('message.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Account Profile Form Ends -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Account Section ends -->

@endsection
