<form id="employer_job_filter_values_update_form">
    <input type="hidden" name="job_filter_id" value="{{ $job_filter_id }}" />
    <div class="modal-body">
        <div class="row values-container">
            @if ($values)
            @foreach ($values as $val)
                @include('employer.job-filters.value')
            @endforeach
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="#" class="btn btn-primary add-value" 
                    data-id="{{ encode($job_filter_id) }}">
                <i class="fa fa-plus"></i> {{ __('message.add') }}
                </a>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_job_filter_values_update_form_button">
        {{ __('message.save') }}
        </button>
    </div>
</form>