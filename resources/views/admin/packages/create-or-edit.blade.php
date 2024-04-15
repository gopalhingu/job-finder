<form id="admin_package_create_update_form">
    <input type="hidden" name="package_id" value="{{ $package['package_id'] }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{ $package['title'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.currency') }}</label>
                    <input type="text" class="form-control" name="currency" value="{{ $package['currency'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.currency_for_api') }}</label>
                    <select class="form-control select2" name="currency_for_api">
                        {!! stripeCurrencies(true, $package['currency_for_api']) !!}
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.monthly_price') }}</label>
                    <input type="number" step="any" class="form-control" name="monthly_price" value="{{ $package['monthly_price'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.yearly_price') }}</label>
                    <input type="number" step="any" class="form-control" name="yearly_price" value="{{ $package['yearly_price'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        {{__('message.active_jobs') }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 
                        title="{{__('message.enter_zero_for')}}"></i>
                    </label>
                    <input type="number" step="any" class="form-control" name="active_jobs" value="{{ $package['active_jobs'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        {{__('message.active_users') }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 
                        title="{{__('message.enter_zero_for')}}"></i>
                    </label>
                    <input type="number" step="any" class="form-control" name="active_users" value="{{ $package['active_users'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        {{__('message.active_custom_filters') }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 
                        title="{{__('message.enter_zero_for')}}"></i>
                    </label>
                    <input type="number" step="any" class="form-control" name="active_custom_filters" value="{{ $package['active_custom_filters'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        {{__('message.active_quizes') }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 
                        title="{{__('message.enter_zero_for')}}"></i>
                    </label>
                    <input type="number" step="any" class="form-control" name="active_quizes" value="{{ $package['active_quizes'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        {{__('message.active_interviews') }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 
                        title="{{__('message.enter_zero_for')}}"></i>
                    </label>
                    <input type="number" step="any" class="form-control" name="active_interviews" value="{{ $package['active_interviews'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>
                        {{__('message.active_traites') }}
                        <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" 
                        title="{{__('message.enter_zero_for')}}"></i>
                    </label>
                    <input type="number" step="any" class="form-control" name="active_traites" value="{{ $package['active_traites'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.separate_site') }}</label>
                    <select class="form-control" name="separate_site">
                        <option value="0" {{ sel($package['separate_site'], '0') }}>{{ __('message.no') }}</option>
                        <option value="1" {{ sel($package['separate_site'], '1') }}>{{ __('message.yes') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.branding') }}</label>
                    <select class="form-control" name="branding">
                        <option value="0" {{ sel($package['branding'], '0') }}>{{ __('message.no') }}</option>
                        <option value="1" {{ sel($package['branding'], '1') }}>{{ __('message.yes') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.role_permissions') }}</label>
                    <select class="form-control" name="role_permissions">
                        <option value="0" {{ sel($package['role_permissions'], '0') }}>{{ __('message.no') }}</option>
                        <option value="1" {{ sel($package['role_permissions'], '1') }}>{{ __('message.yes') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.custom_emails') }}</label>
                    <select class="form-control" name="custom_emails">
                        <option value="0" {{ sel($package['custom_emails'], '0') }}>{{ __('message.no') }}</option>
                        <option value="1" {{ sel($package['custom_emails'], '1') }}>{{ __('message.yes') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.status') }}</label>
                    <select class="form-control" name="status">
                        <option value="0" {{ sel($package['status'], '0') }}>{{ __('message.no') }}</option>
                        <option value="1" {{ sel($package['status'], '1') }}>{{ __('message.yes') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('message.image') }}</label>
                    @php $thumb = packageThumb($package['image']); @endphp
                    <input type="file" class="form-control dropify" name="image" data-default-file="{{$thumb['image']}}" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_package_create_update_form_button">{{__('message.save') }}</button>
    </div>
</form>