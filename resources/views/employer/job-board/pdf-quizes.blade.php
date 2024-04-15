<style>
    p, h2, h3 {padding:0px; margin: 0px;}
    .border-tr {border: 1px solid;}
</style>
@if ($quizes)
<table>
    <tr>
        <td colspan="3">
            <h2>{{ $quizes[0]['first_name'].' '.$quizes[0]['last_name'] }} 
                ({{ $quizes[0]['quizes_result'] }}%)
            </h2>
        </td>
    </tr>
    <tr>
        <td colspan="3"><br /></td>
    </tr>
    @foreach ($quizes as $value)
    @php $quiz = objToArr(json_decode($value['quiz_data'])); @endphp
    @php $user_answers = objToArr(json_decode($value['answers_data'])); @endphp
    <tr>
        <td colspan="3">
            <u>
                <h3>
                    {{ $value['quiz_title'] }}
                    ({{ $value['total_questions'] != 0 ? round(($value['correct_answers']/$value['total_questions'])*100) : '0' }}%)
                </h3>
            </u>
        </td>
    </tr>
    <tr>
        <td colspan="3"><br /></td>
    </tr>
    @foreach ($quiz['questions'] as $i => $question)
        <tr>
            <td colspan="3">
                <strong>{{ ($i+1).': '.$question['title'] }}</strong>
                <br />
            </td>
        </tr>
        @foreach ($question['answers'] as $answer)
        @php $user_answer = isset($user_answers[$i]) ? $user_answers[$i] : ''; @endphp
        <tr class="border-tr">
            <td class="border-tr">{{ $answer['title'] }}</td>
            <td class="border-tr">{{ $answer['is_correct'] ? 'Correct' : '' }}</td>
            <td class="border-tr">{{ checkQuizCorrect($user_answer, $answer['quiz_question_answer_id'], $question['type']) }}</td>
        </tr>
        @endforeach
    @endforeach
    <tr>
        <td colspan="3"><br /><br /></td>
    </tr>
    @endforeach
</table>
<hr />
@else
@endif