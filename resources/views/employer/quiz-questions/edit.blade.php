<form id="employer_quiz_question_create_update_form">
    @php $quiz_question_id = encode($question['quiz_question_id']); @endphp
    <input type="hidden" name="quiz_question_id" value="{{ $quiz_question_id }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.title') }}</label>
                    <textarea class="form-control" name="title">{{ $question['title'] }}</textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <br />
                    <label>{{ __('message.image') }}</label>
                    @php $thumb = questionThumb($question['image']); @endphp
                    <input type="file" class="form-control dropify" name="image" data-id="{{ $quiz_question_id }}"
                        data-default-file="{{$thumb['image']}}" />
                </div>
            </div>
            <hr />
            <div class="col-md-12">
                <input type="hidden" name="type" id="type" value="{{ $type }}">
                <div class="row answers-container">
                    <div class="col-md-8">
                        <div class="form-group">
                            @php $title = $question['type'] == 'radio' ? __('message.change_to_multi_correct') : __('message.change_to_single_correct'); @endphp
                            <label>{{ __('message.answers') }} <i class="fas fa-exchange-alt change-answer-type" title="{{ $title }}"></i></label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group text-center">
                            <label>{{ __('message.correct') }} ?</label>
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <label>{{ __('message.delete') }}</label>
                    </div>
                </div>
                @if ($answers)
                @foreach ($answers as $key => $answer)
                <div class="row answers-container">
                    <div class="col-md-8">
                        <div class="form-group">
                            <input type="hidden" name="answer_ids[]" class="form-control" 
                                value="{{ encode($answer['quiz_question_answer_id']) }}" />
                            <input type="text" name="answer_titles[]" class="form-control" 
                                value="{{ $answer['title'] }}" placeholder="{{__('message.enter_option_value')}}" />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group text-center">
                            @php $checked = $answer['is_correct'] == 1 ? 'checked="checked"' : ''; @endphp
                            <input type="hidden" class="answer" name="answers[]" value="{{ $checked ? 1 : 0 }}">
                            @if($question['type'] == 'checkbox')
                            <input type="checkbox" class="minimal" name="answers2[]" {{ $checked }}>
                            @else
                            <input type="radio" class="minimal" name="answers2[]" {{ $checked }}>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 text-center">
                        <div class="form-group">
                            <i class="fa fa-trash text-red remove-answer" data-id="{{ encode($answer['quiz_question_answer_id']) }}" 
                                title="{{__('message.remove_answer')}}"></i>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                @php $type = $question['type']; @endphp
                @include('employer.quiz-questions.new-answer-item')
                @include('employer.quiz-questions.new-answer-item')
                @endif
            </div>
            <div class="col-md-12">
                <a href="#" class="btn btn-primary add-answer" 
                    data-type="{{ $question['type'] }}"
                    data-id="{{ $quiz_question_id }}">
                <i class="fa fa-plus"></i> {{ __('message.add') }}
                </a>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_quiz_question_create_update_form_button">{{ __('message.save') }}</button>
    </div>
</form>