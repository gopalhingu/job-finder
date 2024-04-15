<form id="employer_job_board_assign_form">
    <input type="hidden" name="candidates" id="candidates" />
    <input type="hidden" name="type" value="{{ $type }}" />
    <input type="hidden" name="job_id" value="{{ $job_id }}" />
    <div class="modal-body">
        <div class="row">
            @if($type == 'quiz')
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.quizes') }}</label>
                    <select class="form-control select2" name="quiz_id">
                        @if ($quizes)
                        @foreach ($quizes as $quiz)
                        <option value="{{ encode($quiz['quiz_id']) }}">{{ $quiz['title'] }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="checkbox" class="minimal" name="notify_candidate" />
                    <label>{{ __('message.send_email_candidate') }}</label>
                </div>
            </div>
            @else
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.team_member') }} ({{ __('message.interviewer') }})</label>
                    <select class="form-control select2" name="interviewer_id">
                        @if($employers)
                        @foreach($employers as $employer)
                        <option value="{{ encode($employer['employer_id']) }}">{{ $employer['first_name'].' '.$employer['last_name'] }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.interviews') }}</label>
                    <select class="form-control select2" name="interview_id">
                        @if($interviews)
                        @foreach($interviews as $interview)
                        <option value="{{ encode($interview['interview_id']) }}">{{ $interview['title'] }}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.date_time') }}</label>
                    <input type="text" class="form-control datetimepicker" name="interview_time" />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.description') }}</label>
                    <textarea class="form-control" name="description" placeholder="{{ __('message.location_or_instructions') }}"></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="checkbox" class="minimal" name="notify_candidate" />
                    <label>{{ __('message.send_email_candidate') }}</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <input type="checkbox" class="minimal" name="notify_team_member" />
                    <label>{{ __('message.send_email_team') }}</label>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_job_board_assign_form_button">{{ __('message.save') }}</button>
    </div>
</form>