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
            @if (setting('enable_multiple_resume') == 'yes')
            <h2><span>{{ __('message.account') }} > {{ __('message.resumes') }} > {{ $resume['title'] }}</span></h2>
            @else
            <h2><span>{{ __('message.account') }} > {{ substr(__('message.resumes'), 0,-1) }}</span></h2>
            @endif
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
                            <section class="edit-resume-section" id="process-tab">
                                <div class="col-xs-12">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs resume-process-edit more-icon-resume-edit-process" role="tablist">
                                        <li role="presentation" class="active" title="General" id="general-tab">
                                            <a href="#resume-general" aria-controls="resume-general" role="tab" data-toggle="tab">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                        <li role="presentation" title="Job History" id="experience-tab">
                                            <a href="#resume-history" aria-controls="resume-history" role="tab" data-toggle="tab">
                                            <i class="fa fa-history" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                        <li role="presentation" title="Qualifications" id="qualification-tab">
                                            <a href="#resume-qualification" aria-controls="resume-qualification" role="tab" data-toggle="tab">
                                            <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                        <li role="presentation" title="Languages" id="language-tab">
                                            <a href="#resume-language" aria-controls="resume-language" role="tab" data-toggle="tab">
                                            <i class="fa fa-language" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                        <li role="presentation" title="Achievements" id="achievement-tab">
                                            <a href="#resume-achievement" aria-controls="resume-achievement" role="tab" data-toggle="tab">
                                            <i class="fa fa-trophy" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                        <li role="presentation" title="References" id="reference-tab">
                                            <a href="#resume-references" aria-controls="resume-references" role="tab" data-toggle="tab">
                                            <i class="fa fa-globe" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" id="resume-general">
                                            @include('candidate.alpha.partials.account-edit-resume-general')
                                        </div>
                                        <div role="tabpanel" class="tab-pane" id="resume-history">
                                            <div class="edit-resume-content">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                                        <div class="account-box">
                                                            <p class="account-box-heading">
                                                                <span class="account-box-heading-text">{{ __('message.job_experiences') }}</span>
                                                                <span class="account-box-heading-line"></span>
                                                            </p>
                                                            <div class="container">
                                                                <form class="form" id="resume_edit_experiences_form">
                                                                    <div class="section-container">
                                                                        @foreach ($resume['experiences'] as $experience)
                                                                        @include('candidate.alpha.partials.account-edit-resume-experiences')
                                                                        @endforeach
                                                                        <div class="row resume-item-edit-box-section no-experience-box">
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <p>{{ __('message.there_are_no_experiences') }}.</p>
                                                                                <p> {{__('message.add_from')}} <strong>+</strong> {{__('message.button_below')}}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group form-group-account">
                                                                                <a class="btn btn-primary add-section add-section-experience" title="{{__('message.add_more')}}"
                                                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                                                    data-type="experience">
                                                                                <i class="fa fa-plus"></i>
                                                                                </a>
                                                                                @if (count($resume['experiences']) == 0)
                                                                                <input type="hidden" id="no_experience_found" value="1" />
                                                                                @endif
                                                                                <button type="submit" class="btn btn-success" title="Save" 
                                                                                    id="resume_edit_experiences_form_button">
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
                                        <div role="tabpanel" class="tab-pane" id="resume-qualification">
                                            <div class="edit-resume-content">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                                        <div class="account-box">
                                                            <p class="account-box-heading">
                                                                <span class="account-box-heading-text">{{ __('message.qualifications') }}</span>
                                                                <span class="account-box-heading-line"></span>
                                                            </p>
                                                            <div class="container">
                                                                <form class="form" id="resume_edit_qualifications_form">
                                                                    <div class="section-container">
                                                                        @foreach ($resume['qualifications'] as $qualification)
                                                                        @include('candidate.alpha.partials.account-edit-resume-qualifications')
                                                                        @endforeach
                                                                        <div class="row resume-item-edit-box-section no-qualification-box">
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <p>{{ __('message.there_are_no_qualifications') }}.</p>
                                                                                <p> {{__('message.add_from')}} <strong>+</strong> {{__('message.button_below')}}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group form-group-account">
                                                                                <a class="btn btn-primary add-section add-section-qualification" title="{{__('message.add_more')}}"
                                                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                                                    data-type="qualification">
                                                                                <i class="fa fa-plus"></i>
                                                                                </a>
                                                                                @if (count($resume['qualifications']) == 0)
                                                                                <input type="hidden" id="no_qualification_found" value="1" />
                                                                                @endif
                                                                                <button type="submit" class="btn btn-success" title="Save"
                                                                                    id="resume_edit_qualifications_form_button">
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
                                        <div role="tabpanel" class="tab-pane" id="resume-language">
                                            <div class="edit-resume-content">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                                        <div class="account-box">
                                                            <p class="account-box-heading">
                                                                <span class="account-box-heading-text">{{ __('message.languages') }}</span>
                                                                <span class="account-box-heading-line"></span>
                                                            </p>
                                                            <div class="container">
                                                                <form class="form" id="resume_edit_languages_form">
                                                                    <div class="section-container">
                                                                        @foreach ($resume['languages'] as $language)
                                                                        @include('candidate.alpha.partials.account-edit-resume-languages')
                                                                        @endforeach
                                                                        <div class="row resume-item-edit-box-section no-language-box">
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <p>{{ __('message.there_are_no_languages') }}.</p>
                                                                                <p> {{__('message.add_from')}} <strong>+</strong> {{__('message.button_below')}}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group form-group-account">
                                                                                <a class="btn btn-primary add-section add-section-language" title="{{__('message.add_more')}}"
                                                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                                                    data-type="language">
                                                                                <i class="fa fa-plus"></i>
                                                                                </a>
                                                                                @if (count($resume['languages']) == 0)
                                                                                <input type="hidden" id="no_language_found" value="1" />
                                                                                @endif
                                                                                <button type="submit" class="btn btn-success" title="Save"
                                                                                    id="resume_edit_languages_form_button">
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
                                        <div role="tabpanel" class="tab-pane" id="resume-achievement">
                                            <div class="edit-resume-content">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                                        <div class="account-box">
                                                            <p class="account-box-heading">
                                                                <span class="account-box-heading-text">{{ __('message.achievements') }}</span>
                                                                <span class="account-box-heading-line"></span>
                                                            </p>
                                                            <div class="container">
                                                                <form class="form" id="resume_edit_achievements_form">
                                                                    <div class="section-container">
                                                                        @foreach ($resume['achievements'] as $achievement)
                                                                        @include('candidate.alpha.partials.account-edit-resume-achievements')
                                                                        @endforeach
                                                                        <div class="row resume-item-edit-box-section no-achievement-box">
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <p>{{ __('message.there_are_no_achievements') }}.</p>
                                                                                <p> {{__('message.add_from')}} <strong>+</strong> {{__('message.button_below')}}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group form-group-account">
                                                                                <a class="btn btn-primary add-section add-section-achievement" title="{{__('message.add_more')}}"
                                                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                                                    data-type="achievement">
                                                                                <i class="fa fa-plus"></i>
                                                                                </a>
                                                                                @if (count($resume['achievements']) == 0)
                                                                                <input type="hidden" id="no_achievement_found" value="1" />
                                                                                @endif
                                                                                <button type="submit" class="btn btn-success" title="Save"
                                                                                    id="resume_edit_achievements_form_button">
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
                                        <div role="tabpanel" class="tab-pane" id="resume-references">
                                            <div class="edit-resume-content">
                                                <div class="row">
                                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                                        <div class="account-box">
                                                            <p class="account-box-heading">
                                                                <span class="account-box-heading-text">{{ __('message.references') }}</span>
                                                                <span class="account-box-heading-line"></span>
                                                            </p>
                                                            <div class="container">
                                                                <form class="form" id="resume_edit_references_form">
                                                                    <div class="section-container">
                                                                        @foreach ($resume['references'] as $reference)
                                                                        @include('candidate.alpha.partials.account-edit-resume-references')
                                                                        @endforeach
                                                                        <div class="row resume-item-edit-box-section no-reference-box">
                                                                            <div class="col-md-12 col-lg-12">
                                                                                <p>{{ __('message.there_are_no_references') }}</p>
                                                                                <p> {{__('message.add_from')}} <strong>+</strong> {{__('message.button_below')}}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="row">
                                                                        <div class="col-md-12 col-lg-12">
                                                                            <div class="form-group form-group-account">
                                                                                <a class="btn btn-primary add-section add-section-reference" title="{{__('message.add_more')}}"
                                                                                    data-id="{{ encode($resume['resume_id']) }}"
                                                                                    data-type="reference">
                                                                                <i class="fa fa-plus"></i>
                                                                                </a>
                                                                                @if (count($resume['references']) == 0)
                                                                                <input type="hidden" id="no_reference_found" value="1" />
                                                                                @endif
                                                                                <button type="submit" class="btn btn-success" title="Save"
                                                                                    id="resume_edit_references_form_button">
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
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- #account area section ends -->
</main>
@endsection