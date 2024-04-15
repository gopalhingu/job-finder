<form id="admin_candidate_create_update_form">
    <input type="hidden" name="candidate_id" value="{{ esc_output($candidate['candidate_id']) }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.first_name') }}</label>
                    <input type="text" class="form-control" name="first_name" value="{{ esc_output($candidate['first_name']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.last_name') }}</label>
                    <input type="text" class="form-control" name="last_name" value="{{ esc_output($candidate['last_name']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.email') }}</label>
                    <input type="text" class="form-control" name="email" value="{{ esc_output($candidate['email']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.phone1') }}</label>
                    <input type="text" class="form-control" name="phone1" value="{{ esc_output($candidate['phone1']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.phone2') }}</label>
                    <input type="text" class="form-control" name="phone2" value="{{ esc_output($candidate['phone2']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.date_of_birth') }}</label>
                    <input type="date" class="form-control" name="dob" 
                        value="{{ $candidate['dob'] ? esc_output(date('Y-m-d', strtotime($candidate['dob'])), 'raw') : date('Y-m-d') }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.address') }}</label>
                    <input type="text" class="form-control" name="address" value="{{ esc_output($candidate['address']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.city') }}</label>
                    <input type="text" class="form-control" name="city" value="{{ esc_output($candidate['city']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.state') }}</label>
                    <input type="text" class="form-control" name="state" value="{{ esc_output($candidate['state']) }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{__('message.country') }}</label>
                    <input type="text" class="form-control" name="country" value="{{ esc_output($candidate['country']) }}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.gender') }}</label>
                    <select class="form-control" name="gender">
                        <option value="male" {{ sel($candidate['gender'], 'male') }}>{{ __('message.male') }}</option>
                        <option value="female" {{ sel($candidate['gender'], 'female') }}>{{ __('message.female') }}</option>
                        <option value="other" {{ sel($candidate['gender'], 'other') }}>{{ __('message.other') }}</option>
                    </select>
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
                    <label>{{__('message.short_biography') }}</label>
                    <textarea class="form-control" name="bio">{{ esc_output($candidate['bio'], 'textarea') }}</textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{__('message.image') }}</label>
                    @php $thumb = candidateThumb($candidate['image']); @endphp
                    <input type="file" class="form-control dropify" name="image" data-default-file="{{$thumb['image']}}" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{__('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_candidate_create_update_form_button">{{__('message.save') }}</button>
    </div>
</form>