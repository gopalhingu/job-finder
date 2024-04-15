<form id="admin_testimonial_create_update_form">
    <input type="hidden" name="testimonial_id" value="{{ $testimonial['testimonial_id'] }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('message.description')}}</label>
                    <textarea class="form-control" name="description">{{$testimonial['description']}}</textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.employer') }}</label>
                    <select class="form-control select2" name="employer_id">
                        @foreach ($employers as $key => $value)
                        <option value="{{ $value['employer_id'] }}" {{sel($value['employer_id'], $testimonial['employer_id'])}}>{{ $value['first_name'].' '.$value['last_name']. ' ('.$value['company'].')' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>            
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.status') }}</label>
                    <select class="form-control" name="status">
                        <option value="0" {{ sel($testimonial['status'], '0') }}>{{ __('message.no') }}</option>
                        <option value="1" {{ sel($testimonial['status'], '1') }}>{{ __('message.yes') }}</option>
                    </select>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_testimonial_create_update_form_button">{{__('message.save') }}</button>
    </div>
</form>