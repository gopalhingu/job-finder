<style>
    p, h2, h3 {padding:0px; margin: 0px;}
</style>
@if ($interviews)
<table>
    <tr>
        <td colspan="2">
            <h2>{{ $interviews[0]['first_name'].' '.$interviews[0]['last_name'] }} 
                ({{ $interviews[0]['interviews_result'] }}%)
            </h2>
        </td>
    </tr>
    @foreach ($interviews as $value)
    @php $interview = objToArr(json_decode($value['interview_data'])) @endphp
    @php $answers = objToArr(json_decode($value['answers_data'])) @endphp
    <tr>
        <td colspan="2">
            <u>
                <h3>
                    {{ $value['interview_title'] }}
                    ({{ round(($value['overall_rating']/($value['total_questions']*10))*100) }}%)
                </h3>
            </u>
        </td>
    </tr>
    @foreach($interview['questions'] as $i => $question)
    <tr>
        <td>
            <strong>{{ $question['title'] }}</strong>
            @if(isset($answers[$i]['comment']) && $answers[$i]['comment'])
            <br />{{ __('message.comment') }} : {{ $answers[$i]['comment'] }}
            @else
            <br />{{ __('message.comment') }} : ---
            @endif
        </td>
        <td>{{ isset($answers[$i]['rating']) ? $answers[$i]['rating'].'/10' : '---' }}</td>
    </tr>
    @endforeach
    @endforeach
</table>
<hr />
@else
@endif