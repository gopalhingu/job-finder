<form id="employer_department_create_update_form">
    <input type="hidden" name="department_id" value="{{ encode($department['department_id']) }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{ ($department['title']) }}">
                </div>
            </div>
            <div class="col-md-12">
                <label>{{ __('message.status') }}</label>
                <select class="form-control" name="status">
                <option value="1" {{ sel($department['status'], 1) }}>{{ __('message.active') }}</option>
                <option value="0" {{ sel($department['status'], 0) }}>{{ __('message.inactive') }}</option>
                </select>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <br />
                    <label>{{ __('message.image') }}</label>
                    @php $thumb = departmentThumb($department['image']); @endphp
                    <input type="file" class="form-control dropify" name="image" data-default-file="{{ $thumb['image'] }}" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_department_create_update_form_button">
        {{ __('message.save') }}
        </button>
    </div>
</form>