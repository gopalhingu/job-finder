<form id="admin_candidate_package_create_update_form">
    <input type="hidden" name="candidate_package_id" value="{{ $candidate_package['candidate_package_id'] }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{ $candidate_package['title'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.currency') }}</label>
                    <input type="text" class="form-control" name="currency" value="{{ $candidate_package['currency'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.currency_for_api') }}</label>
                    <select class="form-control select2" name="currency_for_api">
                        {!! stripeCurrencies(true, $candidate_package['currency_for_api']) !!}
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.monthly_price') }}</label>
                    <input type="number" step="any" class="form-control" name="monthly_price" value="{{ $candidate_package['monthly_price'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.yearly_price') }}</label>
                    <input type="number" step="any" class="form-control" name="yearly_price" value="{{ $candidate_package['yearly_price'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label title="{{__('message.allowed_job_applications') }}">
                        {{trimString(__('message.allowed_job_applications')) }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 
                        title="{{__('message.enter_zero_for')}}"></i>
                    </label>
                    <input type="number" step="any" class="form-control" name="allowed_job_applications" value="{{ $candidate_package['allowed_job_applications'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        {{__('message.allowed_resumes') }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 
                        title="{{__('message.enter_zero_for')}}"></i>
                    </label>
                    <input type="number" step="any" class="form-control" name="allowed_resumes" value="{{ $candidate_package['allowed_resumes'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.show_hide_personal_info') }}</label>
                    <select class="form-control" name="show_hide_personal_info">
                        <option value="0" {{ sel($candidate_package['show_hide_personal_info'], '0') }}>{{ __('message.no') }}</option>
                        <option value="1" {{ sel($candidate_package['show_hide_personal_info'], '1') }}>{{ __('message.yes') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('message.image') }}</label>
                    @php $thumb = packageThumb($candidate_package['image']); @endphp
                    <input type="file" class="form-control dropify" name="image" data-default-file="{{$thumb['image']}}" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_candidate_package_create_update_form_button">{{__('message.save') }}</button>
    </div>
</form>