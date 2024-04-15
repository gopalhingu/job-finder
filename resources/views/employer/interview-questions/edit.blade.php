<form id="employer_interview_question_create_update_form">
    <input type="hidden" name="interview_question_id" value="{{ encode($question['interview_question_id']) }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.title') }}</label>
                    <textarea class="form-control" name="title">{{ $question['title'] }}</textarea>
                </div>
            </div>
            <hr />
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_interview_question_create_update_form_button">
        {{ __('message.save') }}
        </button>
    </div>
</form>