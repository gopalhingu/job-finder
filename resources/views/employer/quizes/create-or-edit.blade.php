<form id="employer_quiz_create_update_form">
    <input type="hidden" name="quiz_id" value="{{ encode($quiz['quiz_id']) }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.category') }}</label>
                    <select class="form-control" name="quiz_category_id" id="quizs_category_id">
                    @if($quiz_categories)
                    @foreach($quiz_categories as $category)
                    <option value="{{ encode($category['quiz_category_id']) }}" {{ sel($quiz['quiz_category_id'], $category['quiz_category_id']) }}>
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
                    <input type="text" name="title" class="form-control" value="{{ $quiz['title'] }}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.allowed_time_in_minutes') }}</label>
                    <input type="number" name="allowed_time" class="form-control" value="{{ $quiz['allowed_time'] }}">
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.description') }}</label>
                    <textarea class="form-control" name="description">{{ $quiz['description'] }}</textarea>
                </div>
            </div>
            <div class="col-md-12">
                <label>{{ __('message.status') }}</label>
                <select class="form-control" name="status">
                <option value="1" {{ sel($quiz['status'], 1) }}>{{ __('message.active') }}</option>
                <option value="0" {{ sel($quiz['status'], 0) }}>{{ __('message.inactive') }}</option>
                </select>
            </div>            
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_quiz_create_update_form_button">{{ __('message.save') }}</button>
    </div>
</form>