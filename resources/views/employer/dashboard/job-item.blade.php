@if($jobs)
@foreach($jobs as $job)
<tr>
    <td>
        <a href="{{ url('/') }}/employer/jobs/create-or-edit/{{ encode($job['job_id']) }}">
        {{ ($job['title']) }}
        </a>
        <a href="{{ url('/') }}/employer/job-board/{{ encode($job['job_id']) }}" target="_blank">
        <i class="fas fa-external-link-alt"></i>
        </a>
    </td>
    <td>{{ ($job['department']) }}</td>
    <td>{{ ($job['total_count']) }}</td>
    <td>
        <span class="label label-info" title="{{ ($job['shortlisted_count']) }} {{ __('message.shortlisted') }}">
        {{ ($job['shortlisted_count']) }}
        </span>
        &nbsp;
        <span class="label label-warning" title="{{ ($job['interviewed_count']) }} {{ __('message.interviewed') }}">
        {{ ($job['interviewed_count']) }}
        </span>
        &nbsp;
        <span class="label label-success" title="{{ ($job['hired_count']) }} {{ __('message.hired') }}">
        {{ ($job['hired_count']) }}
        </span>
        &nbsp;
        <span class="label label-danger" title="{{ ($job['rejected_count']) }} {{ __('message.rejected') }}">
        {{ ($job['rejected_count']) }}
        </span>
    </td>
</tr>
@endforeach
@endif