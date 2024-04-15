<form id="admin_candidate_membership_create_update_form">
    <input type="hidden" name="candidate_membership_id" value="{{ $candidate_membership['candidate_membership_id'] }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{ $candidate_membership['title'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.price_paid') }}</label>
                    <input type="text" class="form-control" name="price_paid" value="{{ $candidate_membership['price_paid'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    @php 
                        $future_timestamp = strtotime("+1 month");
                        $date = date('Y-m-d', $future_timestamp);
                    @endphp
                    <label>{{__('message.expiry') }}</label>
                    <input type="date" class="form-control" name="expiry" 
                    value="{{ $candidate_membership['expiry'] ? date('Y-m-d', strtotime($candidate_membership['expiry'])) : $date; }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.candidate_packages') }}</label>
                    <select class="form-control select2" name="candidate_package_id">
                        @foreach ($candidate_packages as $key => $value)
                        <option value="{{ $value['candidate_package_id'] }}" {{sel($value['candidate_package_id'], $candidate_membership['candidate_package_id'])}}>{{ $value['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.candidate') }}</label>
                    <select class="form-control select2" name="candidate_id">
                        @foreach ($candidates as $key => $value)
                        <option value="{{ $value['candidate_id'] }}" {{sel($value['candidate_id'], $candidate_membership['candidate_id'])}}>{{ $value['first_name'].' '.$value['last_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.payment_type') }}</label>
                    <select class="form-control select2" name="payment_type">
                        @foreach ($payment_types as $key => $value)
                        <option value="{{ $value }}" {{sel($value, $candidate_membership['payment_type'])}}>{{ ucwords($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.package_type') }}</label>
                    <select class="form-control select2" name="package_type">
                        @foreach ($package_types as $key => $value)
                        <option value="{{ $value }}" {{sel($value, $candidate_membership['package_type'])}}>{{ ucwords($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.status') }}</label>
                    <select class="form-control select2" name="status">
                        <option value="1" {{ sel($candidate_membership['status'], '1') }}>{{ __('message.active') }}</option>
                        <option value="0" {{ sel($candidate_membership['status'], '0') }}>{{ __('message.inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_candidate_membership_create_update_form_button">{{__('message.save') }}</button>
    </div>
</form>