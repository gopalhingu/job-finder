

<br/>
@if ($job)
<table>
    <tr>
        <td>
            <h2 class="job-board-resume-section-title">
                {{ $job['title'] }}
            </h2>
        </td>
    </tr>
</table>
<p>
    {!! $job['description'] !!}
</p>
@else
<p>No Resume Found</p>
@endif