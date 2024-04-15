@extends('front'.viewPrfx().'layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.update').' '.__('message.profile')}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > 
                            <a href="{{route('front-profile')}}">{{__('message.account')}}</a> > 
                            <a href="{{route('front-profile')}}">{{__('message.profile')}}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Section Ends -->

    <div class="account-detail-container">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="account-detail-left-1">
                        @include('front'.viewPrfx().'partials.account-sidebar')
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="account-detail-right-1">
                        <form class="form" id="profile_update_form">
                            <div class="row">
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.first_name') }}</label>
                                        <input type="text" class="form-control" placeholder="Adam" name="first_name" 
                                            value="{{ $candidate['first_name'] }}">
                                        <small class="form-text text-muted">{{ __('message.enter_first_name') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.last_name') }}</label>
                                        <input type="text" class="form-control" placeholder="Smith" name="last_name" 
                                            value="{{ $candidate['last_name'] }}">
                                        <small class="form-text text-muted">{{ __('message.enter_last_name') }}.</small>
                                    </div>                 
                                </div>                   
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.phone') }} 1</label>
                                        <input type="text" class="form-control" placeholder="12345 67891011" name="phone1" 
                                            value="{{ $candidate['phone1'] }}">
                                        <small class="form-text text-muted">{{ __('message.enter_phone') }}.</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.email') }}</label>
                                        <input type="text" class="form-control" placeholder="email" name="email" 
                                            value="{{ $candidate['email'] }}">
                                        <small class="form-text text-muted">{{ __('message.enter_email') }}</small>
                                    </div>                     
                                </div>               
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.city') }}</label>
                                        <input type="text" class="form-control" placeholder="New York" name="city" 
                                            value="{{ $candidate['city'] }}">
                                        <small class="form-text text-muted">{{ __('message.enter_city') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.phone') }} 2</label>
                                        <input type="text" class="form-control" placeholder="67891011" name="phone2" 
                                            value="{{ $candidate['phone2'] }}">
                                        <small class="form-text text-muted">{{ __('message.enter_phone') }} 2.</small>
                                    </div>                     
                                </div>               
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.country') }}</label>
                                        <input type="text" class="form-control" placeholder="Australia" name="country" 
                                            value="{{ $candidate['country'] }}">
                                        <small class="form-text text-muted">{{ __('message.enter_country') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.state') }}</label>
                                        <input type="text" class="form-control" placeholder="New York" name="state" 
                                            value="{{ $candidate['state'] }}">
                                        <small class="form-text text-muted">{{ __('message.enter_state') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
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
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.address') }}</label>
                                        <input type="text" class="form-control" placeholder="House # 30, Street 32" name="address" 
                                            value="{{ $candidate['address'] }}">
                                        <small class="form-text text-muted">{{ __('message.enter_address') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-6">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.date_of_birth') }}</label>
                                        <input type="date" class="form-control datepicker" placeholder="1990-10-10" name="dob" 
                                            value="{{ date('Y-m-d', strtotime($candidate['dob'])) }}">
                                        <small class="form-text text-muted">{{ __('message.select_date_of_birth') }}</small>
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
                                        <button type="submit" class="btn btn-cf-general" title="Save" id="profile_update_form_button">
                                        <i class="fas fa-save"></i> {{ __('message.save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
