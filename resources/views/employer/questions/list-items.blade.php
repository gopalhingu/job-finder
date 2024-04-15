@if ($questions)
@foreach ($questions as $question)
@php $question_id = encode($question['question_id']); @endphp
<li class="question-list-item bank-item-{{ $question_id }}" 
    data-id="{{ $question_id }}">
    <span class="handle" title="{{__('message.drag_to')}} {{ $nature }}">
    <i class="fa fa-ellipsis-v"></i> <i class="fa fa-ellipsis-v"></i>
    </span>
    @if($question['nature'] == 'quiz')
    @if($question['type'] == 'radio')
    <small class="label label-primary label-green">
    <i class="fa fa-dot-circle-o"></i> {{ $question['answers_count'] }} {{ __('message.items') }}
    </small>
    @elseif($question['type'] == 'checkbox')
    <small class="label label-primary label-red">
    <i class="fa fa-check-square-o"></i> {{ $question['answers_count'] }} {{ __('message.items') }}
    </small>
    @endif
    @endif
    <br />
    <span class="text">{{ $question['title'] }}</span>
    <div class="tools">
        @if(empAllowedTo('edit_questions'))
        <i class="fa fa-edit create-or-edit-question" data-id="{{ $question_id }}"></i>
        @endif
        @if(empAllowedTo('delete_questions'))
        <i class="far fa-trash-alt delete-question" data-id="{{ $question_id }}"></i>
        @endif
    </div>
</li>
@endforeach
@else
<li>
    <p>{{ __('message.no_questions_found') }}</p>
</li>
@endif