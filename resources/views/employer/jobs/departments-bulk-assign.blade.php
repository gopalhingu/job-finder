<form id="employer_departments_bulk_assign_form">
    <input type="hidden" name="job_ids" id="job_ids" value="" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                    {{ __('message.select_departments') }}
                    </label>
                    <select class="form-control select2" multiple="multiple" name="departments[]">
                        @foreach ($departments as $key => $value)
                        <option value="{{ encode($value['department_id']) }}">{{ $value['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_departments_bulk_assign_form_button">{{ __('message.save') }}</button>
    </div>
</form>