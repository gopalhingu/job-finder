@extends('candidate.alpha.layouts.master')
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
            <h2><span>{{ __('message.account') }} > {{ __('message.profile') }}</span></h2>
        </div>
    </div>
</section>
<!-- #intro -->
<main id="main">
    <!--==========================
        Account Area Setion
    ============================-->
    <section id="about">
        <div class="container">
            <div class="row mt-10">
                <div class="col-lg-3">
                    <div class="account-area-left">
                        <ul>
                            @include('candidate.alpha.partials.account-sidebar')
                        </ul>
                    </div>
                </div>
                <div class="col-md-9 col-lg-9 col-sm-12">
                    <div class="row">
                        <div class="col-md-12 col-lg-12 col-sm-12">
                            <div class="account-box">
                                <p class="account-box-heading">
                                    <span class="account-box-heading-text">{{ __('message.profile') }}</span>
                                    <span class="account-box-heading-line"></span>
                                </p>
                                <div class="container">
                                    <form class="form" id="profile_update_form">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.first_name') }}</label>
                                                    <input type="text" class="form-control" placeholder="Adam" name="first_name" 
                                                        value="{{ $candidate['first_name'] }}">
                                                    <small class="form-text text-muted">{{ __('message.enter_first_name') }}</small>
                                                </div>
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.phone1') }} 1</label>
                                                    <input type="text" class="form-control" placeholder="12345 67891011" name="phone1" v
                                                        alue="{{ $candidate['phone1'] }}">
                                                    <small class="form-text text-muted">{{ __('message.enter_phone') }} 1.</small>
                                                </div>
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.city') }}</label>
                                                    <input type="text" class="form-control" placeholder="New York" name="city" 
                                                        value="{{ $candidate['city'] }}">
                                                    <small class="form-text text-muted">{{ __('message.enter_city') }}</small>
                                                </div>
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.country') }}</label>
                                                    <input type="text" class="form-control" placeholder="Australia" name="country" 
                                                        value="{{ $candidate['country'] }}">
                                                    <small class="form-text text-muted">{{ __('message.enter_country') }}</small>
                                                </div>
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.gender') }}</label>
                                                    <select name="gender" class="form-control">
                                                        <option value="male" {{ $candidate['gender'] == 'male' ? 'selected' : ''; }}>
                                                            {{ __('message.male') }}
                                                        </option>
                                                        <option value="female" {{ $candidate['gender'] == 'female' ? 'selected' : ''; }}>
                                                            {{ __('message.female') }}
                                                        </option>
                                                        <option value="other" {{ $candidate['gender'] == 'other' ? 'selected' : ''; }}>
                                                            {{ __('message.other') }}
                                                        </option>
                                                    </select>
                                                    <small class="form-text text-muted">{{ __('message.select_gender') }}</small>
                                                </div>
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.date_of_birth') }}</label>
                                                    <input type="text" class="form-control datepicker" placeholder="1990-10-10" name="dob" 
                                                        value="{{ $candidate['dob'] }}">
                                                    <small class="form-text text-muted">{{ __('message.select_date_of_birth') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.last_name') }}</label>
                                                    <input type="text" class="form-control" placeholder="Smith" name="last_name" 
                                                        value="{{ $candidate['last_name'] }}">
                                                    <small class="form-text text-muted">{{ __('message.enter_last_name') }}.</small>
                                                </div>
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.email') }}</label>
                                                    <input type="text" class="form-control" placeholder="email" name="email" 
                                                        value="{{ $candidate['email'] }}">
                                                    <small class="form-text text-muted">{{ __('message.enter_email') }}</small>
                                                </div>
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.phone') }} 2</label>
                                                    <input type="text" class="form-control" placeholder="67891011" name="phone2" 
                                                        value="{{ $candidate['phone2'] }}">
                                                    <small class="form-text text-muted">{{ __('message.enter_phone') }} 2.</small>
                                                </div>
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.state') }}</label>
                                                    <input type="text" class="form-control" placeholder="New York" name="state" 
                                                        value="{{ $candidate['state'] }}">
                                                    <small class="form-text text-muted">{{ __('message.enter_state') }}</small>
                                                </div>
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.address') }}</label>
                                                    <input type="text" class="form-control" placeholder="House # 30, Street 32" name="address" 
                                                        value="{{ $candidate['address'] }}">
                                                    <small class="form-text text-muted">{{ __('message.enter_address') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-group-account">
                                                    <label for="">{{ __('message.short_biography') }}</label>
                                                    <textarea class="form-control" placeholder="Short Bio" name="bio">{{ $candidate['bio'] }}</textarea>
                                                    <small class="form-text text-muted">{{ __('message.enter_short_biography') }}</small>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-group-account">
                                                    <label for="input-file-now-custom-1">{{ __('message.image_file') }}</label>
                                                    @php $thumb = candidateThumb($candidate['image']); @endphp
                                                    <input type="file" id="input-file-now-custom-1" class="dropify" 
                                                        data-default-file="{{$thumb['image']}}" name="image" />
                                                    <small class="form-text text-muted">{{ __('message.only_jpg_or_png_allowed') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group form-group-account">
                                                    <button type="submit" class="btn btn-success" title="Save" id="profile_update_form_button">
                                                    <i class="fa fa-floppy-o"></i> {{ __('message.save') }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- #account area section ends -->
</main>
@endsection