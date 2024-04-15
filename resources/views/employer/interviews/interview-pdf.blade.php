<style>
    p, h2, h3 {padding:0px; margin: 0px;}
    ul li {list-style: none; text-decoration: none;}
</style>
@if($interview)
<table>
    <tr>
        <td colspan="2">
            <h2>{{ $interview['title'] }}</h2>
            <br />
            <h3>{{ __('message.description') }}</h3>
            <p>{{ $interview['description'] }}</p>
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
            <br /><br />
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
<p>{{ __('message.no_interview_found') }}</p>
@endif