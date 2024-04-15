<div class="account-box">
    <div class="row resume-item-edit-box-section achievement-box">
        <div class="col-md-12 col-lg-12">
            <div class="resume-item-edit-box-section-remove remove-section" 
                data-type="achievement" title="{{ __('message.remove_section') }}"
                data-id="{{ $achievement['resume_achievement_id'] ? encode($achievement['resume_achievement_id']) : ''; }}">
                <i class="fas fa-trash-alt"></i>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.title') }} *</label>
                <input type="hidden" name="resume_id[]" 
                    value="{{ $achievement['resume_id'] ? encode($achievement['resume_id']) : '' }}" />
                <input type="hidden" name="resume_achievement_id[]" 
                    value="{{ $achievement['resume_achievement_id'] ? encode($achievement['resume_achievement_id']) : '' }}" />
                <input type="text" class="form-control" placeholder="My Designs" name="title[]" 
                    value="{{ $achievement['title'] }}">
                <small class="form-text text-muted">{{ __('message.enter_title') }}</small>
            </div>
            <div class="form-group form-group-account">
                <label for="">{{ __('message.date') }}</label>
                <input type="date" class="form-control datepicker" placeholder="29-12-1985" name="date[]"
                    value="{{ dateOnly($achievement['date']) }}">
                <small class="form-text text-muted">{{ __('message.enter_date') }}</small>
            </div>
        </div>
        <div class="col-md-6 col-lg-6">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.link') }}</label>
                <input type="text" class="form-control" placeholder="http://www.example.com" name="link[]"
                    value="{{ $achievement['link'] }}">
                <small class="form-text text-muted">{{ __('message.enter_link') }}</small>
            </div>
            <div class="form-group form-group-account">
                <label for="">{{ __('message.select_type') }} *</label>
                <select class="form-control" name="type[]">
                    <option value="certificate" {{ sel('certificate', $achievement['type']) }}>{{ __('message.certificate') }}</option>
                    <option value="portfolio" {{ sel('portfolio', $achievement['type']) }}>{{ __('message.portfolio') }}</option>
                    <option value="publication" {{ sel('publication', $achievement['type']) }}>{{ __('message.publication') }}</option>
                    <option value="award" {{ sel('award', $achievement['type']) }}>{{ __('message.award') }}</option>
                    <option value="other" {{ sel('other', $achievement['type']) }}>{{ __('message.other') }}</option>
                </select>
                <small class="form-text text-muted">{{ __('message.select_type') }}</small>
            </div>
        </div>
        <div class="col-md-12 col-lg-12">
            <div class="form-group form-group-account">
                <label for="">{{ __('message.description') }} *</label>
                <textarea class="form-control" placeholder="Description" name="description[]">{{ $achievement['description'] }}</textarea>
                <small class="form-text text-muted">{{ __('message.enter_description') }}</small>
            </div>
        </div>
    </div>
</div>