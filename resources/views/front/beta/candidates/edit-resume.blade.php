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

                <!-- Candidate Account General Starts -->
                <div class="section-incremental-form-alpha front-resume-general-section">
                    <h5><i class="fa-solid fa-user"></i> {{__('message.general')}}</h5>
                    <a class="box-open-close collapsed" data-bs-toggle="collapse" href="#generalSection" 
                        role="button" aria-expanded="false" aria-controls="generalSection" data-state="closed">
                        <i class="fa fa-plus"></i>
                    </a>
                    <div class="collapse" id="generalSection" style="">
                        <div class="card card-body collapsed-card">
                            @include('front'.viewPrfx().'partials.account-edit-resume-general')
                        </div>
                    </div>
                </div>
                <!-- Candidate Account General Ends -->

                <!-- Candidate Account Experiences Starts -->
                <div class="section-incremental-form-alpha front-resume-experiences-section">
                    <h5 id="experiences_heading">
                        <i class="fa-solid fa-user-tie"></i> {{__('message.experiences')}} ({{count($resume['experiences'])}})
                    </h5>
                    <a class="box-open-close" data-bs-toggle="collapse" href="#experiences_section" role="button" 
                        aria-expanded="true" aria-controls="experiences_section" data-state="closed">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                    <div class="collapse" id="experiences_section" style="">
                        <div class="card card-body collapsed-card">
                            <form class="form" id="resume_edit_experiences_form">
                                @csrf
                                <div class="section-container">
                                    @foreach ($resume['experiences'] as $experience)
                                    @include('front'.viewPrfx().'partials.account-edit-resume-experiences')
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group form-group-account">
                                            <a class="btn btn-general add-section add-section-experience" title="{{__('message.add_more')}}"
                                                data-id="{{ encode($resume['resume_id']) }}"
                                                data-type="experience">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                            @if (count($resume['experiences']) == 0)
                                            <input type="hidden" id="no_experience_found" value="1" />
                                            @endif
                                            <button type="submit" class="btn btn-general" title="{{ __('message.save') }}" id="resume_edit_experiences_form_button">
                                                <i class="fa fa-save"></i> {{ __('message.save') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Candidate Account Experiences Ends -->

                <!-- Candidate Account Qualifications Starts -->
                <div class="section-incremental-form-alpha front-resume-qualifications-section">
                    <h5 id="qualifications_heading">
                        <i class="fa-solid fa-graduation-cap"></i> {{__('message.qualifications')}} ({{count($resume['qualifications'])}})
                    </h5>
                    <a class="box-open-close" data-bs-toggle="collapse" href="#qualifications_section" role="button" 
                        aria-expanded="false" aria-controls="qualifications_section" data-state="closed">
                        <i class="fa fa-plus"></i>
                    </a>
                    <div class="collapse" id="qualifications_section">
                        <div class="card card-body collapsed-card">
                            <form class="form" id="resume_edit_qualifications_form">
                                @csrf
                                <div class="section-container">
                                    @foreach ($resume['qualifications'] as $qualification)
                                    @include('front'.viewPrfx().'partials.account-edit-resume-qualifications')
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group form-group-account">
                                            <a class="btn btn-general add-section add-section-qualification" title="{{__('message.add_more')}}"
                                                data-id="{{ encode($resume['resume_id']) }}"
                                                data-type="qualification">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                            @if (count($resume['qualifications']) == 0)
                                            <input type="hidden" id="no_qualification_found" value="1" />
                                            @endif
                                            <button type="submit" class="btn btn-general" title="{{ __('message.save') }}" id="resume_edit_qualifications_form_button">
                                                <i class="fa fa-save"></i> {{ __('message.save') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Candidate Account Qualifications Ends -->

                <!-- Candidate Account Achivements Starts -->
                <div class="section-incremental-form-alpha front-resume-achievements-section">
                    <h5 id="achievements_heading"><i class="fa-solid fa-trophy"></i> {{__('message.achievements')}} ({{count($resume['achievements'])}})</h5>
                    <a class="box-open-close" data-bs-toggle="collapse" href="#achievements_section" role="button" 
                        aria-expanded="false" aria-controls="achievements_section" data-state="closed">
                        <i class="fa fa-plus"></i>
                    </a>
                    <div class="collapse" id="achievements_section">
                        <div class="card card-body collapsed-card">
                            <form class="form" id="resume_edit_achievements_form">
                                @csrf
                                <div class="section-container">
                                    @foreach ($resume['achievements'] as $achievement)
                                    @include('front'.viewPrfx().'partials.account-edit-resume-achievements')
                                    @endforeach                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group form-group-account">
                                            <a class="btn btn-general add-section add-section-achievement" title="{{__('message.add_more')}}"
                                                data-id="{{ encode($resume['resume_id']) }}"
                                                data-type="achievement">
                                                <i class="fa fa-plus"></i>
                                            </a>
                                            @if (count($resume['achievements']) == 0)
                                            <input type="hidden" id="no_achievement_found" value="1" />
                                            @endif
                                            <button type="submit" class="btn btn-general" title="{{ __('message.save') }}" id="resume_edit_achievements_form_button">
                                                <i class="fa fa-save"></i> {{ __('message.save') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Candidate Account Achivements Ends -->

                <!-- Candidate Account Skills Starts -->
                <div class="section-incremental-form-alpha front-resume-skills-section">
                    <h5 id="skills_heading"><i class="fa-solid fa-screwdriver-wrench"></i> {{__('message.skills')}} ({{count($resume['skills'])}})</h5>
                    <a class="box-open-close" data-bs-toggle="collapse" href="#skills_section" role="button" 
                        aria-expanded="false" aria-controls="skills_section" data-state="closed">
                        <i class="fa fa-plus"></i>
                    </a>
                    <div class="collapse" id="skills_section">
                        <div class="card card-body collapsed-card">
                            <form class="form" id="resume_edit_skills_form">
                                @csrf
                                <div class="section-container">
                                    @foreach ($resume['skills'] as $skill)
                                    @include('front'.viewPrfx().'partials.account-edit-resume-skills')
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group form-group-account">
                                            <a class="btn btn-general add-section add-section-skill" title="{{__('message.add_more')}}"
                                                data-id="{{ encode($resume['resume_id']) }}"
                                                data-type="skill">
                                            <i class="fa fa-plus"></i>
                                            </a>
                                            @if (count($resume['skills']) == 0)
                                            <input type="hidden" id="no_skill_found" value="1" />
                                            @endif
                                            <button type="submit" class="btn btn-general" title="Save"
                                                id="resume_edit_skills_form_button">
                                            <i class="fa-regular fa-save"></i> {{ __('message.save') }}
                                            </button>                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- Candidate Account Skills Ends -->

                <!-- Candidate Account Languages Starts -->
                <div class="section-incremental-form-alpha front-resume-languages-section">
                    <h5 id="languages_heading"><i class="fa-solid fa-language"></i> {{__('message.languages')}} ({{count($resume['languages'])}})</h5>
                    <a class="box-open-close" data-bs-toggle="collapse" href="#languages_section" role="button" 
                        aria-expanded="false" aria-controls="languages_section" data-state="closed">
                        <i class="fa fa-plus"></i>
                    </a>
                    <div class="collapse" id="languages_section">
                        <div class="card card-body collapsed-card">
                            <form class="form" id="resume_edit_languages_form">
                                @csrf
                                <div class="section-container">
                                    @foreach ($resume['languages'] as $language)
                                    @include('front'.viewPrfx().'partials.account-edit-resume-languages')
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group form-group-account">
                                            <a class="btn btn-general add-section add-section-language" title="{{__('message.add_more')}}"
                                                data-id="{{ encode($resume['resume_id']) }}"
                                                data-type="language">
                                            <i class="fa fa-plus"></i>
                                            </a>
                                            @if (count($resume['languages']) == 0)
                                            <input type="hidden" id="no_language_found" value="1" />
                                            @endif
                                            <button type="submit" class="btn btn-general" title="Save"
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
                <!-- Candidate Account Skills Ends -->

                <!-- Candidate Account References Starts -->
                <div class="section-incremental-form-alpha front-resume-references-section">
                    <h5 id="references_heading"><i class="fa-solid fa-link"></i> References (2)</h5>
                    <a class="box-open-close" data-bs-toggle="collapse" href="#references_section" role="button" 
                        aria-expanded="false" aria-controls="references_section" data-state="closed">
                        <i class="fa fa-plus"></i>
                    </a>
                    <div class="collapse" id="references_section">
                        <div class="card card-body collapsed-card">
                            <form class="form" id="resume_edit_references_form">
                                @csrf
                                <div class="section-container">
                                    @foreach ($resume['references'] as $reference)
                                    @include('front'.viewPrfx().'partials.account-edit-resume-references')
                                    @endforeach                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group form-group-account">
                                            <a class="btn btn-general add-section add-section-reference" title="{{__('message.add_more')}}"
                                                data-id="{{ encode($resume['resume_id']) }}"
                                                data-type="reference">
                                            <i class="fa fa-plus"></i>
                                            </a>
                                            @if (count($resume['references']) == 0)
                                            <input type="hidden" id="no_reference_found" value="1" />
                                            @endif
                                            <button type="submit" class="btn btn-general" title="Save"
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
                <!-- Candidate Account References Ends -->

            </div>
        </div>
    </div>
</div>
<!-- Account Section Ends -->

@endsection
