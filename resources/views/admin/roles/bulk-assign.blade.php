<form id="admin_roles_bulk_assign_form">
    <input type="hidden" name="employer_ids" id="employer_ids" value="" /><!-- For Employers -->
    <input type="hidden" name="user_ids" id="user_ids" value="" /><!-- For User -->
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                    {{ __('message.select_roles') }}
                    </label>
                    <select class="form-control select2" multiple="multiple" name="roles[]">
                        @foreach ($roles as $key => $value)
                        <option value="{{ $value['role_id'] }}">{{ $value['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_roles_bulk_assign_form_button">{{ __('message.save') }}</button>
    </div>
</form>