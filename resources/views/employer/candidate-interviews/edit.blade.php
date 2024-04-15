<form id="interview_conduct_form">
    <input type="hidden" name="candidate_interview_id" value="{{ encode($candidate_interview['candidate_interview_id']) }}" />
    <div class="modal-body">
        <div class="row">
            @php $interview = objToArr(json_decode($candidate_interview['interview_data'])); @endphp
            @php $answers = objToArr(json_decode($candidate_interview['answers_data'])); @endphp
            @if ($interview['questions'])
            @foreach ($interview['questions'] as $key => $question)
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ ($key+1).' : '.$question['title'] }}</label>
                    @if ($candidate_interview['status'] == 0)
                    <select class="pill-rating" name="ratings[]" autocomplete="off">
                        <option value="0" selected="selected">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                    @else
                    @if (isset($answers[$key]['rating']))
                    <p>{{ $answers[$key]['rating'] }}/10</p>
                    @else
                    ---
                    @endif
                    @endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.comments') }}</label>
                    @if ($candidate_interview['status'] == 0)
                    <input type="text" class="form-control" name="comments[]" placeholder="{{ __('message.comments') }}">
                    @else
                    @if (isset($answers[$key]))
                    <p>{{ trimString($answers[$key]['comment']) }}</p>
                    @else
                    ---
                    @endif
                    @endif
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <hr />
                </div>
            </div>
            @endforeach
            @else
            <div class="col-md-12">
                <div class="form-group">
                    {{__('message.there_are_no_questions')}}
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        @if ($candidate_interview['status'] == 0)
        <button type="submit" class="btn btn-primary btn-blue" id="interview_conduct_form_button">{{ __('message.save') }}</button>
        @endif
    </div>
</form>