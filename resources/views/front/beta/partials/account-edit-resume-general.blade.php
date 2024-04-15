<div class="edit-resume-content">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="section-incremental-form-alpha-item">
                <form class="form" id="resume_edit_general_form">
                    @csrf
                    <div class="row section-incremental-form-alpha-relative">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group form-group-account">
                                <label for="">{{ __('message.title') }}</label>
                                <input type="hidden" name="id" value="{{ encode($resume['resume_id']) }}" />
                                <input type="text" class="form-control" name="title" value="{{ $resume['title'] }}">
                                <small class="form-text text-muted">{{ __('message.enter_title') }}</small>
                            </div>
                            <div class="form-group form-group-account">
                                <label for="">{{ __('message.designation') }}</label>
                                <input type="text" class="form-control" name="designation" value="{{ $resume['designation'] }}">
                                <small class="form-text text-muted">{{ __('message.enter_designation') }}</small>
                            </div>
                            <div class="form-group form-group-account">
                                <label for="">{{ __('message.objective') }}</label>
                                <textarea class="form-control" name="objective">{{ $resume['objective'] }}</textarea>
                                <small class="form-text text-muted">{{ __('message.enter_objective') }}.</small>
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
                            <div class="form-group form-group-account">
                                <label for="">{{ __('message.status') }}</label>
                                <select class="form-control" name="status">
                                    <option value="1" {{ sel($resume['status'], '1') }}>{{ __('message.active') }}</option>
                                    <option value="0" {{ sel($resume['status'], '0') }}>{{ __('message.inactive') }}</option>
                                </select>
                                <small class="form-text text-muted">{{ __('message.select_status') }}</small>
                            </div>
                            <div class="form-group form-group-account">
                                <button type="submit" class="btn btn-general" title="Save" id="resume_edit_general_form_button">
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