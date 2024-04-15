<form id="employer_job_filter_create_update_form">
    <input type="hidden" name="job_filter_id" value="{{ $job_filter['job_filter_id'] }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{ $job_filter['title'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.order') }}</label>
                    <input type="number" class="form-control" name="order" value="{{ $job_filter['order'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.front_filter') }}</label>
                    <select class="form-control" name="front_filter">
                    <option value="1" {{ sel($job_filter['front_filter'], 1) }}>{{ __('message.yes') }}</option>
                    <option value="0" {{ sel($job_filter['front_filter'], 0) }}>{{ __('message.no') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.front_value') }}</label>
                    <select class="form-control" name="front_value">
                    <option value="1" {{ sel($job_filter['front_value'], 1) }}>{{ __('message.yes') }}</option>
                    <option value="0" {{ sel($job_filter['front_value'], 0) }}>{{ __('message.no') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.front_filter_type') }}</label>
                    <select class="form-control" name="type">
                    <option value="dropdown" {{ sel($job_filter['type'], 'dropdown') }}>
                    {{ __('message.dropdown').' ('.__('message.single_select').')' }}
                    </option>
                    <option value="checkbox" {{ sel($job_filter['type'], 'checkbox') }}>
                    {{ __('message.checkbox').' ('.__('message.multi_select').')' }}
                    </option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.status') }}</label>
                    <select class="form-control" name="status">
                    <option value="1" {{ sel($job_filter['status'], 1) }}>{{ __('message.active') }}</option>
                    <option value="0" {{ sel($job_filter['status'], 0) }}>{{ __('message.inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_job_filter_create_update_form_button">
        {{ __('message.save') }}
        </button>
    </div>
</form>