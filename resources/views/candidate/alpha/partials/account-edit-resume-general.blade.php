<div class="edit-resume-content">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="account-box">
                <p class="account-box-heading">
                    <span class="account-box-heading-text">{{ __('message.general') }}</span>
                    <span class="account-box-heading-line"></span>
                </p>
                <div class="container">
                    <form class="form" id="resume_edit_general_form">
                        <div class="row resume-item-edit-box-section">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.title') }}</label>
                                    <input type="hidden" name="id" value="{{ encode($resume['resume_id']) }}" />
                                    <input type="text" class="form-control" placeholder="Marketing Resume" 
                                        name="title" value="{{ $resume['title'] }}">
                                    <small class="form-text text-muted">{{ __('message.enter_title') }}</small>
                                </div>
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.designation') }}</label>
                                    <input type="text" class="form-control" placeholder="Marketing Manager" 
                                        name="designation" value="{{ $resume['designation'] }}">
                                    <small class="form-text text-muted">{{ __('message.enter_designation') }}</small>
                                </div>
                                <div class="form-group form-group-account">
                                    <label for="">{{ __('message.objective') }}</label>
                                    <textarea class="form-control" placeholder="Marketing Manager" 
                                        name="objective">{{ $resume['objective'] }}</textarea>
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
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div class="form-group form-group-account">
                                    <button type="submit" class="btn btn-success" title="Save" id="resume_edit_general_form_button">
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