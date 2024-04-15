<form id="admin_language_create_or_edit_form">
    <input type="hidden" name="language_id" value="{{$language['language_id']}}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{$language['title']}}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.slug') }}</label>
                    <input type="text" class="form-control" name="slug" value="{{$language['slug']}}">
                    <small>{{__('message.only_english_alphabets_allowed')}}<br />{{__('message.changing_it_later')}}</small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.status') }}</label>
                    <select class="form-control" name="status">
                        <option value="1" {{ sel($language['status'], 1) }}>{{ __('message.active') }}</option>
                        <option value="0" {{ sel($language['status'], 0) }}>{{ __('message.inactive') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.direction') }}</label>
                    <select class="form-control" name="direction">
                        <option value="ltr" {{ sel($language['direction'], 1) }}>ltr</option>
                        <option value="rtl" {{ sel($language['direction'], 0) }}>rtl</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.display') }}</label>
                    <select class="form-control" name="display">
                        <option value="both" {{ sel($language['display'], 'both') }}>{{ __('message.both') }}</option>
                        <option value="only_title" {{ sel($language['display'], 'only_title') }}>{{ __('message.only_title') }}</option>
                        <option value="only_flag" {{ sel($language['display'], 'only_flag') }}>{{ __('message.only_flag') }}</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>{{ __('message.flag') }}</label>
                    <select class="form-control select2" name="flag">
                        @foreach(flagCodes() as $flag)
                        <option value="{{ $flag }}" {{ sel($language['flag'], $flag) }}>{{ $flag }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="admin_language_create_or_edit_form_button">
        {{ __('message.save') }}
        </button>
    </div>
</form>