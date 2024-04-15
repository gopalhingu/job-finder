<form id="admin_user_message_form">
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('message.subject') }}</label>
                    <input type="text" class="form-control" name="subject">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('message.message') }}</label>
                    <textarea class="form-control" name="msg" id="msg"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_user_message_form_button">
        {{ __('message.send') }}
        </button>
    </div>
</form>