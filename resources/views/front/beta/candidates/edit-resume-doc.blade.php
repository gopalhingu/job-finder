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
                    <!-- Resume Update Starts -->
                    <form class="form" id="resume_update_form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.title') }}</label>
                                    <input type="hidden" name="resume_id" value="{{ encode($resume['resume_id']) }}">
                                    <input type="text" class="form-control" placeholder="Marketing Resume" 
                                        name="title" value="{{ $resume['title'] }}">
                                    <small class="form-text text-muted">{{ __('message.enter_first_name') }}</small>
                                </div>
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.status') }}</label>
                                    <select name="status" class="form-control">
                                        <option value="1" {{ $resume['status'] == '1' ? 'selected' : '' }}>{{ __('message.active') }}</option>
                                        <option value="0" {{ $resume['status'] == '0' ? 'selected' : '' }}>{{ __('message.inactive') }}</option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('message.select_status') }}</small>
                                </div>
                                <div class="form-group form-group-account">
                                    <label for="input-file-now-custom-1">
                                    {{ __('message.file') }}
                                    @if ($resume['file'])
                                    <a target="_blank" href="{{ resumeThumb($resume['file']) }}" title="Download">
                                    {{ __('message.download') }}
                                    </a>
                                    @endif
                                    </label>
                                    <input type="file" id="input-file-now-custom-1" class="dropify" 
                                        data-default-file="" name="file" />
                                    <small class="form-text text-muted">{{ __('message.only_doc_docx_pdf_allowed') }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group form-group-account">
                                    <button type="submit" class="btn btn-general" title="Save" id="resume_update_form_button">
                                    <i class="fa-regular fa-save"></i> {{ __('message.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Resume Update Ends -->
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Account Section Ends -->

@endsection
