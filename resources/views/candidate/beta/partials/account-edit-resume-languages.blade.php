<div class="section-incremental-form-alpha-item">
    <div class="row section-incremental-form-alpha-relative skill-box">
        <div class="col-md-12 col-lg-12">
            <div class="section-incremental-form-alpha-remove remove-section" 
                data-type="language" title="{{__('message.remove_section')}}"
                data-id="{{ $language['resume_language_id'] ? encode($language['resume_language_id']) : ''; }}">
                <i class="fas fa-trash-alt"></i>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.language') }} *</label>
                <input type="hidden" name="resume_id[]" 
                    value="{{ $language['resume_id'] ? encode($language['resume_id']) : ''; }}" />
                <input type="hidden" name="resume_language_id[]" 
                    value="{{ $language['resume_language_id'] ? encode($language['resume_language_id']) : ''; }}" />
                <input type="text" class="form-control" name="title[]" value="{{ $language['title'] }}">
                <small class="form-text text-muted">{{ __('message.select_language') }}</small>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.select_proficiency') }}</label>
                <select class="form-control" name="proficiency[]">
                    <option value="beginner" {{ sel('beginner', $language['proficiency']) }} >{{ __('message.beginner') }}</option>
                    <option value="intermediate" {{ sel('intermediate', $language['proficiency']) }}>{{ __('message.intermediate') }}</option>
                    <option value="expert" {{ sel('expert', $language['proficiency']) }}>{{ __('message.expert') }}</option>
                    <option value="native" {{ sel('native', $language['proficiency']) }}>{{ __('message.native') }}</option>
                </select>
                <small class="form-text text-muted">{{ __('message.select_proficiency') }}</small>
            </div>
        </div>
    </div>
</div>
