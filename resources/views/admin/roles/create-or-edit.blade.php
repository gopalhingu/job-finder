<div class="modal-body">
    <form id="admin_role_create_update_form">
    @csrf
    <input type="hidden" name="role_id" id="role_id" value="{{$role['role_id']}}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{$role['title']}}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.type') }}</label>
                    <select class="form-control" name="type">
                        <option value="admin" {{ sel('admin', $role['type']) }}>{{ __('message.admin')}}</option>
                        <option value="employer" {{ sel('employer', $role['type']) }}>{{ __('message.employer')}}</option>
                    </select>
                </div>
            </div>            
            <div class="col-md-12">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
                <button type="submit" class="btn btn-primary btn-blue" id="admin_role_create_update_form_button">
                {{ __('message.save') }}
                </button>
            </div>
        </div>
    </div>
    </form>
</div>
<div class="modal-footer">
</div>