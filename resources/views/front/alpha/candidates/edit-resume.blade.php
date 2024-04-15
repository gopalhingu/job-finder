@extends('front'.viewPrfx().'layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>{{__('message.update').' '.__('message.resume')}} : {{$resume['title']}}</h2>
                </div>
                <div class="col-md-3">
                    <div class="breadcrumbs-text-right">
                        <p class="text-lg-end">
                            <a href="{{route('home')}}">{{__('message.home')}}</a> > 
                            <a href="{{route('front-profile')}}">{{__('message.account')}}</a> > 
                            <a href="{{route('front-acc-resume-listing')}}">{{__('message.resumes')}}</a>
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
                    <div class="account-detail-right-1 front-resume-section front-resume-general-section">
                        <h5>{{__('message.general')}}</h5>
                        <a class="account-open-close-icon" data-bs-toggle="collapse" href="#generalSection" 
                            role="button" aria-expanded="false" aria-controls="generalSection" data-state="closed">
                            <i class="fa-solid fa-circle-plus"></i>
                        </a>
                        <div class="collapse" id="generalSection">
                            <div class="card card-body collapsed-card">
                                @include('front'.viewPrfx().'partials.account-edit-resume-general')
                            </div>
                        </div>
                    </div>
                    <div class="account-detail-right-1 front-resume-section front-resume-experiences-section">
                        <h5 id="experiences_heading">{{__('message.experiences')}} ({{count($resume['experiences'])}})</h5>
                        <a class="account-open-close-icon" data-bs-toggle="collapse" href="#experiences_section" 
                            role="button" aria-expanded="false" aria-controls="experiences_section" data-state="closed">
                            <i class="fa-solid fa-circle-plus"></i>
                        </a>
                        <div class="collapse" id="experiences_section">
                            <div class="card card-body collapsed-card">
                                <form class="form" id="resume_edit_experiences_form">
                                    <div class="section-container">
                                        @foreach ($resume['experiences'] as $experience)
                                        @include('front'.viewPrfx().'partials.account-edit-resume-experiences')
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <a class="btn btn-cf-general add-section add-section-experience" title="{{__('message.add_more')}}"
                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                    data-type="experience">
                                                    <i class="fa fa-plus"></i>
                                                </a>
                                                @if (count($resume['experiences']) == 0)
                                                <input type="hidden" id="no_experience_found" value="1" />
                                                @endif
                                                <button type="submit" class="btn btn-cf-general" title="Save" 
                                                    id="resume_edit_experiences_form_button">
                                                <i class="fa fa-save"></i> {{ __('message.save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="account-detail-right-1 front-resume-section front-resume-qualifications-section">
                        <h5 id="qualifications_heading">{{__('message.qualifications')}} ({{count($resume['qualifications'])}})</h5>
                        <a class="account-open-close-icon" data-bs-toggle="collapse" href="#qualifications_section" 
                            role="button" aria-expanded="false" aria-controls="qualifications_section" data-state="closed">
                            <i class="fa-solid fa-circle-plus"></i>
                        </a>
                        <div class="collapse" id="qualifications_section">
                            <div class="card card-body collapsed-card">
                                <form class="form" id="resume_edit_qualifications_form">
                                    <div class="section-container">
                                        @foreach ($resume['qualifications'] as $qualification)
                                        @include('front'.viewPrfx().'partials.account-edit-resume-qualifications')
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <a class="btn btn-cf-general add-section add-section-qualification" title="{{__('message.add_more')}}"
                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                    data-type="qualification">
                                                <i class="fa fa-plus"></i>
                                                </a>
                                                @if (count($resume['qualifications']) == 0)
                                                <input type="hidden" id="no_qualification_found" value="1" />
                                                @endif
                                                <button type="submit" class="btn btn-cf-general" title="Save"
                                                    id="resume_edit_qualifications_form_button">
                                                <i class="fa-regular fa-save"></i> {{ __('message.save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="account-detail-right-1 front-resume-section front-resume-achievements-section">
                        <h5 id="achievements_heading">{{__('message.achievements')}} ({{count($resume['achievements'])}})</h5>
                        <a class="account-open-close-icon" data-bs-toggle="collapse" href="#achievements_section" 
                            role="button" aria-expanded="false" aria-controls="achievements_section" data-state="closed">
                            <i class="fa-solid fa-circle-plus"></i>
                        </a>
                        <div class="collapse" id="achievements_section">
                            <div class="card card-body collapsed-card">
                                <form class="form" id="resume_edit_achievements_form">
                                    <div class="section-container">
                                        @foreach ($resume['achievements'] as $achievement)
                                        @include('front'.viewPrfx().'partials.account-edit-resume-achievements')
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <a class="btn btn-cf-general add-section add-section-achievement" title="{{__('message.add_more')}}"
                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                    data-type="achievement">
                                                <i class="fa fa-plus"></i>
                                                </a>
                                                @if (count($resume['achievements']) == 0)
                                                <input type="hidden" id="no_achievement_found" value="1" />
                                                @endif
                                                <button type="submit" class="btn btn-cf-general" title="Save"
                                                    id="resume_edit_achievements_form_button">
                                                <i class="fa-regular fa-save"></i> {{ __('message.save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="account-detail-right-1 front-resume-section front-resume-languages-section">
                        <h5 id="languages_heading">{{__('message.languages')}} ({{count($resume['languages'])}})</h5>
                        <a class="account-open-close-icon" data-bs-toggle="collapse" href="#languages_section" 
                            role="button" aria-expanded="false" aria-controls="languages_section" data-state="closed">
                            <i class="fa-solid fa-circle-plus"></i>
                        </a>
                        <div class="collapse" id="languages_section">
                            <div class="card card-body collapsed-card">
                                <form class="form" id="resume_edit_languages_form">
                                    <div class="section-container">
                                        @foreach ($resume['languages'] as $language)
                                        @include('front'.viewPrfx().'partials.account-edit-resume-languages')
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <a class="btn btn-cf-general add-section add-section-language" title="{{__('message.add_more')}}"
                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                    data-type="language">
                                                <i class="fa fa-plus"></i>
                                                </a>
                                                @if (count($resume['languages']) == 0)
                                                <input type="hidden" id="no_language_found" value="1" />
                                                @endif
                                                <button type="submit" class="btn btn-cf-general" title="Save"
                                                    id="resume_edit_languages_form_button">
                                                <i class="fa-regular fa-save"></i> {{ __('message.save') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>                                
                            </div>
                        </div>
                    </div>
                    <div class="account-detail-right-1 front-resume-section front-resume-references-section">
                        <h5 id="references_heading">{{__('message.references')}} ({{count($resume['references'])}})</h5>
                        <a class="account-open-close-icon" data-bs-toggle="collapse" href="#references_section" 
                            role="button" aria-expanded="false" aria-controls="references_section" data-state="closed">
                            <i class="fa-solid fa-circle-plus"></i>
                        </a>
                        <div class="collapse" id="references_section">
                            <div class="card card-body collapsed-card">
                                <form class="form" id="resume_edit_references_form">
                                    <div class="section-container">
                                        @foreach ($resume['references'] as $reference)
                                        @include('front'.viewPrfx().'partials.account-edit-resume-references')
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="form-group form-group-account">
                                                <a class="btn btn-cf-general add-section add-section-reference" 
                                                    title="{{__('message.add_more')}}"
                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                    data-type="reference">
                                                <i class="fa fa-plus"></i>
                                                </a>
                                                @if (count($resume['references']) == 0)
                                                <input type="hidden" id="no_reference_found" value="1" />
                                                @endif
                                                <button type="submit" class="btn btn-cf-general" title="Save"
                                                    id="resume_edit_references_form_button">
                                                <i class="fa-regular fa-save"></i> {{ __('message.save') }}
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

@endsection
