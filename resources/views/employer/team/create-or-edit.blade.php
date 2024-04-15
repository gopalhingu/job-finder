<form id="employer_team_create_update_form">
    <input type="hidden" name="employer_id" value="{{ encode($employer['employer_id']) }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.first_name') }}</label>
                    <input type="text" class="form-control" name="first_name" value="{{ $employer['first_name'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.last_name') }}</label>
                    <input type="text" class="form-control" name="last_name" value="{{ $employer['last_name'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.email') }}</label>
                    <input type="text" class="form-control" name="email" value="{{ $employer['email'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.password') }}</label>
                    <input type="password" class="form-control" name="password">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.phone1') }}</label>
                    <input type="text" class="form-control" name="phone1" value="{{ $employer['phone1'] }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.phone2') }}</label>
                    <input type="text" class="form-control" name="phone2" value="{{ $employer['phone2'] }}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    @php $thumb = employerThumb($employer['image']); @endphp
                    <label>{{ __('message.image') }}</label>
                    <input type="file" class="form-control dropify" name="image" data-default-file="{{$thumb['image']}}" />
                </div>
            </div>
            @if(empMembership(employerId(), 'role_permissions') == 1)
            <div class="col-md-12">
                <div class="form-group">
                    <label>
                    {{ __('message.roles') }}
                    <button type="button" class="btn btn-xs btn-warning btn-blue view-roles">
                    <i class="fa fa-plus"></i>
                    </button>
                    </label>
                    <select class="form-control" multiple="multiple" name="roles[]" id="roles-dropdown">
                    @foreach($roles as $key => $value)
                    @php $selected = in_array($value['role_id'], $employerRoles) ? 'selected="selected"' : ''; @endphp
                    <option value="{{ encode($value['role_id']) }}" {{ $selected }}>{{ $value['title'] }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            @endif
            <div class="col-md-12">
                <div class="form-group">
                    <input type="checkbox" class="minimal" name="notify_team_member" />
                    <label>{{ __('message.send_credentials_team') }}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_team_create_update_form_button">{{ __('message.save') }}</button>
    </div>
</form>