<div class="row resume-item-edit-box-section language-box">
    <div class="col-md-12 col-lg-12">
        <div class="resume-item-edit-box-section-remove remove-section" 
            data-type="language"
            data-id="{{ $language['resume_language_id'] ? encode($language['resume_language_id']) : ''; }}"
            title="{{__('message.remove_section')}}">
            <i class="fas fa-trash-alt"></i> {{ __('message.remove_section') }}
        </div>
    </div>
    <div class="col-md-6 col-lg-6">
        <div class="form-group form-group-account">
            <label for="">{{ __('message.language') }} *</label>
            <input type="hidden" name="resume_id[]" 
                value="{{ $language['resume_id'] ? encode($language['resume_id']) : ''; }}" />
            <input type="hidden" name="resume_language_id[]" 
                value="{{ $language['resume_language_id'] ? encode($language['resume_language_id']) : ''; }}" />
            <input type="text" class="form-control" placeholder="French" name="title[]"
                value="{{ $language['title'] }}">        
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