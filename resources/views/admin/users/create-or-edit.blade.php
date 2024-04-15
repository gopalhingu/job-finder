<form id="admin_user_create_update_form">
    <input type="hidden" name="user_id" value="{{ $user['user_id'] }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.first_name') }}</label>
                    <input type="text" class="form-control" name="first_name" value="{{ $user['first_name'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.last_name') }}</label>
                    <input type="text" class="form-control" name="last_name" value="{{ $user['last_name'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.username') }}</label>
                    <input type="text" class="form-control" name="username" value="{{ $user['username'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.email') }}</label>
                    <input type="text" class="form-control" name="email" value="{{ $user['email'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.phone') }}</label>
                    <input type="text" class="form-control" name="phone" value="{{ $user['phone'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.password') }}</label>
                    <input type="password" class="form-control" name="password">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.roles') }}</label>
                    <select class="form-control" multiple="multiple" name="roles[]" id="roles-dropdown">
                        @foreach ($roles as $key => $value)
                        @php $selected = in_array($value['role_id'], $userRoles) ? 'selected="selected"' : ''; @endphp
                        <option value="{{ $value['role_id'] }}"  {{ $selected }}>{{ $value['title'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('message.image') }}</label>
                    @php $thumb = userThumb($user['image']); @endphp
                    <input type="file" class="form-control dropify" name="image" data-default-file="{{ $thumb['image'] }}" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_user_create_update_form_button">{{__('message.save') }}</button>
    </div>
</form>