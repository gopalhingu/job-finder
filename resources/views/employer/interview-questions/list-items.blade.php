@if($questions)
@foreach($questions as $question)
@php $id = encode($question['interview_question_id']); @endphp
<li class="question-list-item interview-item" data-id="{{ $id }}">
    <span class="handle" title="Drag to order">
    <i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i>
    </span>
    <br />
    <span class="text">{{ esc_output($question['title']) }}</span>
    <div class="tools">
        @if(empAllowedTo('edit_interview_questions'))
        <i class="fa fa-edit edit-interview-question" data-id="{{ $id }}"></i>
        @endif
        @if(empAllowedTo('delete_interview_questions'))
        <i class="far fa-trash-alt delete-interview-question" data-id="{{ $id }}"></i>
        @endif
    </div>
</li>
@endforeach
@else
<li class="no-questions-found">
    <p>{{ __('message.no_questions_found') }}</p>
</li>
@endif