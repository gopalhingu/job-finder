@extends('front'.viewPrfx().'layouts.master')

@section('content')

    <!-- Breadcrumb Section Starts -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-9">
                    <h2>
                        {{__('message.update').' '. __('message.resumes') }}
                    <button type="submit" class="btn btn-primary btn-sm add-resume" title="Add New">
                    <i class="fa fa-plus"></i>
                    </button>
                    </h2>

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
                    <div class="row">
                        @if($resumes)
                        @foreach ($resumes as $resume)
                        @php $id = encode($resume['resume_id']); @endphp
                        @if($resume['type'] == 'detailed')
                        <div class="col-md-6 col-lg-4 col-sm-12">
                            <div class="resume-item-box">
                                <div class="dotmenu">
                                    <ul class="dotMenudropbtn dotmenuicons dotmenuShowLeft" data-id="{{ $id }}">
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                    <div id="{{ $id }}" class="dotmenu-content">
                                        <a href="{{ empUrl() }}account/resume/{{ $id }}">
                                        {{ __('message.edit') }}
                                        </a>
                                    </div>
                                </div>
                                <p class="resume-item-box-heading" title="{{ $resume['title'] }}">
                                    {{ trimString($resume['title'], 23) }}
                                </p>
                                <p class="resume-item-box-date">
                                    {{ __('message.updated') }} : {{ timeFormat($resume['updated_at']) }}
                                </p>
                                <p class="resume-item-box-item">
                                    <i class="fa fa-history"></i> 
                                    {{ $resume['experience'] }} {{ __('message.experiences') }}
                                </p>
                                <p class="resume-item-box-item">
                                    <i class="fa fa-language"></i> 
                                    {{ $resume['language'] }} {{ __('message.languages') }}
                                </p>
                                <p class="resume-item-box-item">
                                    <i class="fa fa-graduation-cap"></i> 
                                    {{ $resume['qualification'] }} {{ __('message.qualifications') }}
                                </p>
                                <p class="resume-item-box-item">
                                    <i class="fa fa-trophy"></i> 
                                    {{ $resume['achievement'] }} {{ __('message.achievements') }}
                                </p>
                                <p class="resume-item-box-item">
                                    <i class="fa fa-globe"></i> 
                                    {{ $resume['reference'] }} {{ __('message.references') }}
                                </p>
                            </div>
                        </div>
                        @else
                        <div class="col-md-6 col-lg-4 col-sm-12">
                            <div class="resume-item-box">
                                <div class="dotmenu">
                                    <ul class="dotMenudropbtn dotmenuicons dotmenuShowLeft" data-id="{{ $id }}">
                                        <li></li>
                                        <li></li>
                                        <li></li>
                                    </ul>
                                    <div id="{{ $id }}" class="dotmenu-content">
                                        <a href="{{ empUrl() }}account/resume/{{ $id }}">{{ __('message.edit') }}</a>
                                    </div>
                                </div>
                                <p class="resume-item-box-heading" title="{{ $resume['title'] }}">
                                    {{ trimString($resume['title'], 25) }}
                                </p>
                                <p class="resume-item-box-date">{{ __('message.updated') }} : {{ timeFormat($resume['updated_at']) }}</p>
                                @if(strpos($resume['file'], 'pdf'))
                                <i class="far fa-file-pdf resume-item-box-file"></i>
                                @else
                                <i class="far fa-file-word resume-item-box-file"></i>
                                @endif
                            </div>
                        </div>
                        @endif
                        @endforeach
                        @else
                        <p>{{ __('message.no_resumes_found') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade in resume-create-modal" id="modal-resume-create" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header resume-modal-header">
                    <h4 class="modal-title resume-modal-title">{{ __('message.new_resume') }}</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body-container">
                    <div class="container">
                        <form class="form" id="resume_create_form">
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.title') }}</label>
                                        <input type="text" class="form-control" placeholder="Marketing Resume" name="title">
                                        <small class="form-text text-muted">{{__('message.enter_title')}}</small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.designation') }}</label>
                                        <input type="text" class="form-control" placeholder="Marketing Manager" name="designation">
                                        <small class="form-text text-muted">{{ __('message.enter_designation') }}</small>
                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-group-account">
                                        <label for="">{{ __('message.type') }}</label>
                                        <select class="form-control" name="type">
                                            <option value="detailed">{{ __('message.detailed') }}</option>
                                            <option value="document">{{ __('message.document') }}</option>
                                        </select>
                                        <small class="form-text text-muted">{{ __('message.select_type') }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-lg-12">
                                    <div class="form-group form-group-account">
                                        <button type="submit" class="btn btn-success" title="Save" 
                                        id="resume_create_form_button">
                                        <i class="fa-regular fa-save"></i> {{ __('message.save') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>

@endsection
