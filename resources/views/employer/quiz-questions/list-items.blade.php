@if($questions)
@foreach($questions as $question)
@php $quiz_question_id = encode($question['quiz_question_id']); @endphp
<li class="question-list-item quiz-item" data-id="{{ $quiz_question_id }}">
    <span class="handle" title="{{__('message.drag_to_order')}}">
    <i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i>
    </span>
    @if($question['type'] == 'radio')
    <small class="label label-primary label-green">
    <i class="fa fa-dot-circle-o"></i> {{ $question['answers_count'] }} {{ __('message.items') }}
    </small>
    @elseif($question['type'] == 'checkbox')
    <small class="label label-primary label-red">
    <i class="fa fa-check-square-o"></i> {{ $question['answers_count'] }} {{ __('message.items') }}
    </small>
    @endif
    <br />
    <span class="text">{{ $question['title'] }}</span>
    <div class="tools">
        @if(empAllowedTo('edit_quiz_questions'))
        <i class="fa fa-edit edit-quiz-question" data-id="{{ $quiz_question_id }}"></i>
        @endif
        @if(empAllowedTo('delete_quiz_questions'))
        <i class="far fa-trash-alt delete-quiz-question" data-id="{{ $quiz_question_id }}"></i>
        @endif
    </div>
</li>
@endforeach
@else
<li class="no-questions-found">
    <p>{{ __('message.no_questions_found') }}</p>
</li>
@endif