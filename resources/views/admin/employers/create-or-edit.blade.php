<form id="admin_employer_create_update_form">
    <input type="hidden" name="employer_id" value="{{ esc_output($employer['employer_id']) }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.first_name') }}</label>
                    <input type="text" class="form-control" name="first_name" value="{{ esc_output($employer['first_name']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.last_name') }}</label>
                    <input type="text" class="form-control" name="last_name" value="{{ esc_output($employer['last_name']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.company') }}</label>
                    <input type="text" class="form-control" name="company" value="{{ esc_output($employer['company']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.slug').' ('.__('message.will_auto_generate').')' }}</label>
                    <input type="text" class="form-control" id="slug" name="slug" 
                        value="{{ $employer['slug'] ? esc_output($employer['slug']) : __('message.will_auto_generate') }}" readonly="readonly">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.email') }}</label>
                    <input type="text" class="form-control" name="email" value="{{ esc_output($employer['email']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.phone1') }}</label>
                    <input type="text" class="form-control" name="phone1" value="{{ esc_output($employer['phone1']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.phone2') }}</label>
                    <input type="text" class="form-control" name="phone2" value="{{ esc_output($employer['phone2']) }}">
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
                    <label>{{__('message.short_description')}}</label>
                    <textarea class="form-control" name="short_description">{{$employer['short_description']}}</textarea>
                </div>
            </div>            
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.roles') }}</label>
                    <select class="form-control" multiple="multiple" name="roles[]" id="roles-dropdown">
                        @foreach ($roles as $key => $value)
                        @php $selected = in_array($value['role_id'], $employerRoles) ? 'selected="selected"' : ''; @endphp
                        <option value="{{ esc_output($value['role_id']) }}"  {{ esc_output($selected) }}>{{ esc_output($value['title'], 'html') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('message.image') }}</label>
                    @php $thumb = employerThumb($employer['image']); @endphp
                    <input type="file" class="form-control dropify" name="image" data-default-file="{{$thumb['image']}}" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('message.logo') }}</label>
                    @php $thumb = employerThumb($employer['logo']); @endphp
                    <input type="file" class="form-control dropify" name="logo" data-default-file="{{$thumb['image']}}" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_employer_create_update_form_button">{{__('message.save') }}</button>
    </div>
</form>