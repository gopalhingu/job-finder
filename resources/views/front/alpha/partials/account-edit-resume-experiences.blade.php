<div class="account-box">
    <div class="row resume-item-edit-box-section experience-box">
        <div class="col-md-12 col-lg-12">
            <div class="resume-item-edit-box-section-remove remove-section" 
                data-type="experience"
                data-id="{{ $experience['resume_experience_id'] ? encode($experience['resume_experience_id']) : ''; }}"
                title="{{__('message.remove_section')}}">
                <i class="fas fa-trash-alt"></i>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.job_title') }} *</label>
                <input type="hidden" name="resume_id[]" 
                    value="{{ $experience['resume_id'] ? encode($experience['resume_id']) : ''; }}" />
                <input type="hidden" name="resume_experience_id[]" 
                    value="{{ $experience['resume_experience_id'] ? encode($experience['resume_experience_id']) : ''; }}" />
                <input type="text" class="form-control" placeholder="Software Engineer" name="title[]" 
                    value="{{ esc_output($experience['title']) }}">
                <small class="form-text text-muted">{{ __('message.enter_job_title') }}</small>
            </div>
            <div class="form-group form-group-account">
                <label for="">{{ __('message.from') }} *</label>
                <input type="date" class="form-control datepicker" placeholder="29-12-1985" name="from[]"
                    value="{{ dateOnly($experience['from']) }}">
                <small class="form-text text-muted">{{ __('message.start_date_of_job') }}</small>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.company') }} *</label>
                <input type="text" class="form-control" placeholder="ABC Company" name="company[]"
                    value="{{ esc_output($experience['company']) }}">
                <small class="form-text text-muted">{{ __('message.enter_company') }}</small>
            </div>
            <div class="form-group form-group-account">
                <label for="">{{ __('message.to') }} *</label>
                <input type="date" class="form-control datepicker" placeholder="29-12-1985" name="to[]"
                    value="{{ dateOnly($experience['to']) }}">
                <small class="form-text text-muted">{{ __('message.enter_date_of_job') }}</small>
            </div>
        </div>
        <div class="col-md-12 col-lg-12">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.job_description') }} *</label>
                <textarea class="form-control" placeholder="Job Description" name="description[]">{{ $experience['description'] }}</textarea>
                <small class="form-text text-muted">{{ __('message.enter_job_description') }}.</small>
            </div>
        </div>
    </div>
</div>