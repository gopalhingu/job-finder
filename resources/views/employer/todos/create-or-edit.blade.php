<form id="employer_to_do_create_update_form">
    <input type="hidden" name="to_do_id" value="{{ encode($to_do['to_do_id']) }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{ $to_do['title'] }}">
                </div>
            </div>
            <div class="col-md-12">
                <label>{{ __('message.status') }}</label>
                <select class="form-control" name="status">
                    <option value="1" {{ sel($to_do['status'], 1) }}>{{ __('message.active') }}</option>
                    <option value="0" {{ sel($to_do['status'], 0) }}>{{ __('message.inactive') }}</option>
                </select>
            </div>
            <div class="col-md-12">
                <Br />
                <label>{{ __('message.description') }}</label>
                <textarea id="description" class="form-control" name="description">{{ $to_do['description'] }}</textarea>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_to_do_create_update_form_button">{{ __('message.save') }}</button>
    </div>
</form>