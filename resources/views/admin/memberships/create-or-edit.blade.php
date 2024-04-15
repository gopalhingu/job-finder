<form id="admin_membership_create_update_form">
    <input type="hidden" name="membership_id" value="{{ $membership['membership_id'] }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{ $membership['title'] }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{__('message.price_paid') }}</label>
                    <input type="text" class="form-control" name="price_paid" value="{{ $membership['price_paid'] }}">
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
                    value="{{ $membership['expiry'] ? date('Y-m-d', strtotime($membership['expiry'])) : $date; }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.package') }}</label>
                    <select class="form-control select2" name="package_id">
                        @foreach ($packages as $key => $value)
                        <option value="{{ $value['package_id'] }}" {{sel($value['package_id'], $membership['package_id'])}}>{{ $value['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.employer') }}</label>
                    <select class="form-control select2" name="employer_id">
                        @foreach ($employers as $key => $value)
                        <option value="{{ $value['employer_id'] }}" {{sel($value['employer_id'], $membership['employer_id'])}}>{{ $value['first_name'].' '.$value['last_name']. ' ('.$value['company'].')' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.payment_type') }}</label>
                    <select class="form-control select2" name="payment_type">
                        @foreach ($payment_types as $key => $value)
                        <option value="{{ $value }}" {{sel($value, $membership['payment_type'])}}>{{ ucwords($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.package_type') }}</label>
                    <select class="form-control select2" name="package_type">
                        @foreach ($package_types as $key => $value)
                        <option value="{{ $value }}" {{sel($value, $membership['package_type'])}}>{{ ucwords($value) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>{{ __('message.status') }}</label>
                    <select class="form-control select2" name="status">
                        <option value="1" {{ sel($membership['status'], '1') }}>{{ __('message.active') }}</option>
                        <option value="0" {{ sel($membership['status'], '0') }}>{{ __('message.inactive') }}</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_membership_create_update_form_button">{{__('message.save') }}</button>
    </div>
</form>