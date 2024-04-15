<div class="section-incremental-form-alpha-item">
    <div class="row section-incremental-form-alpha-relative skill-box">
        <div class="col-md-12 col-lg-12">
            <div class="section-incremental-form-alpha-remove remove-section" 
                data-type="skill" title="{{__('message.remove_section')}}"
                data-id="{{ $skill['resume_skill_id'] ? encode($skill['resume_skill_id']) : ''; }}">
                <i class="fas fa-trash-alt"></i>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.skill') }} *</label>
                <input type="hidden" name="resume_id[]" 
                    value="{{ $skill['resume_id'] ? encode($skill['resume_id']) : ''; }}" />
                <input type="hidden" name="resume_skill_id[]" 
                    value="{{ $skill['resume_skill_id'] ? encode($skill['resume_skill_id']) : ''; }}" />
                <input type="text" class="form-control" name="title[]" value="{{ $skill['title'] }}">
                <small class="form-text text-muted">{{ __('message.select_skill') }}</small>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.select_proficiency') }}</label>
                <select class="form-control" name="proficiency[]">
                    <option value="beginner" {{ sel('beginner', $skill['proficiency']) }} >{{ __('message.beginner') }}</option>
                    <option value="intermediate" {{ sel('intermediate', $skill['proficiency']) }}>{{ __('message.intermediate') }}</option>
                    <option value="expert" {{ sel('expert', $skill['proficiency']) }}>{{ __('message.expert') }}</option>
                </select>
                <small class="form-text text-muted">{{ __('message.select_proficiency') }}</small>
            </div>
        </div>
    </div>
</div>
