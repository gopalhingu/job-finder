<form id="employer_interview_create_update_form">
    <input type="hidden" name="interview_id" value="{{ encode($interview['interview_id']) }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.category') }}</label>
                    <select class="form-control" name="interview_category_id" id="interviews_category_id">
                    @if($interview_categories)
                    @foreach ($interview_categories as $category)
                    <option value="{{ encode($category['interview_category_id']) }}" {{ sel($interview['interview_category_id'], $category['interview_category_id']) }}>
                    {{ $category['title'] }}
                    </option>
                    @endforeach
                    @endif
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.title') }}</label>
                    <input type="text" name="title" class="form-control" value="{{ $interview['title'] }}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.description') }}</label>
                    <textarea class="form-control" name="description">{{ $interview['description'] }}</textarea>
                </div>
            </div>
            <div class="col-md-12">
                <label>{{ __('message.status') }}</label>
                <select class="form-control" name="status">
                <option value="1" {{ sel($interview['status'], 1) }}>{{ __('message.active') }}</option>
                <option value="0" {{ sel($interview['status'], 0) }}>{{ __('message.inactive') }}</option>
                </select>
            </div>            
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_interview_create_update_form_button">
        {{ __('message.save') }}
        </button>
    </div>
</form>