<style>
    p, h2, h3 {padding:0px; margin: 0px;}
    ul li {list-style: none; text-decoration: none;}
</style>
@if($quiz)
<table>
    <tr>
        <td colspan="2">
            <h2>{{ $quiz['title'] }}</h2>
            <br />
            <h3>{{ __('message.description') }}</h3>
            <p>
                {{ $quiz['description'] }}
            </p>
            <br />
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <h3>{{ __('message.questions') }}</h3>
        </td>
    </tr>
    @if($questions)
    @foreach ($questions as $key => $question)
    <tr>
        <td colspan="2">
            <p>{{ $key+1 .'. '. $question['title'] }}</p>
            @if($question['image'])
            @php $thumb = questionThumb($question['image']); @endphp
            <img src="{{ $thumb['image'] }}" height="100"/>
            @endif
        </td>
    </tr>
    <tr>
        <td colspan="2">
            @if($question['answers'])
            <div>
                <ul>
                    @foreach ($question['answers'] as $answer)
                    <li>
                        <input type="{{ $question['type'] }}" /> {{ $answer['title'] }}
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <p>{{ __('message.there_are_no_answers') }}</p>
            @endif
        </td>
    </tr>
    @endforeach
    @else
    <tr>
        <td>
            <p>{{ __('message.there_are_no_questions') }}</p>
        </td>
    </tr>
    @endif
</table>
<hr />
@else
<p>{{ __('message.no_quiz_found') }}</p>
@endif