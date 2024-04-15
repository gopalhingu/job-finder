<div class="section-incremental-form-alpha-item">
    <div class="row section-incremental-form-alpha-relative reference-box">
        <div class="col-md-12 col-lg-12">
            <div class="section-incremental-form-alpha-remove remove-section" 
                data-type="reference"
                data-id="{{ $reference['resume_reference_id'] ? encode($reference['resume_reference_id']) : ''; }}"
                title="{{__('message.remove_section')}}">
                <i class="fas fa-trash-alt"></i>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.title') }} *</label>
                <input type="hidden" name="resume_id[]" 
                    value="{{ $reference['resume_id'] ? encode($reference['resume_id']) : ''; }}" />
                <input type="hidden" name="resume_reference_id[]" 
                    value="{{ $reference['resume_reference_id'] ? encode($reference['resume_reference_id']) : ''; }}" />
                <input type="text" class="form-control" name="title[]" value="{{ $reference['title'] }}">
                <small class="form-text text-muted">{{ __('message.enter_person_name') }}</small>
            </div>
            <div class="form-group form-group-account">
                <label for="">{{ __('message.company') }}</label>
                <input type="text" class="form-control" name="company[]" value="{{ $reference['company'] }}">
                <small class="form-text text-muted">{{ __('message.enter_person_company') }}</small>
            </div>
            <div class="form-group form-group-account">
                <label for="">{{ __('message.email') }} *</label>
                <input type="text" class="form-control" name="email[]" value="{{ $reference['email'] }}">
                <small class="form-text text-muted">{{ __('message.enter_person_email') }}</small>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.relation') }} *</label>
                <input type="text" class="form-control" name="relation[]" value="{{ $reference['relation'] }}">
                <small class="form-text text-muted">{{ __('message.enter_relation_association') }}</small>
            </div>
            <div class="form-group form-group-account">
                <label for="">{{ __('message.phone') }}</label>
                <input type="text" class="form-control" name="phone[]" value="{{ $reference['phone'] }}">
                <small class="form-text text-muted">{{ __('message.enter_person_phone') }}</small>
            </div>
        </div>
    </div>
</div>
