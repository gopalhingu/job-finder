@if ($jobs)
@php $count = 1; @endphp
@foreach ($jobs as $job)
@php $job_id = encode($job['job_id']); @endphp
<div class="job-board-job-wrap">
    <h3 class="job-board-job-title job-{{ $job_id }} {{ $count == 1 ? 'first-job' : '' }}" 
        title="Click to select" 
        data-id="{{ $job_id }}"
        data-title="{{ $job['title'] }}">
        @if ($job['hired_count'])
        <i class="fa fa-check-circle text-green" title="{{ $job['hired_count'].' '.__('message.candidate_hired') }}"></i> 
        @endif
        {{ trimString($job['title']) }}
    </h3>
    <span class="job-board-job-item job-board-job-item-general">
        <i class="fa fa-bookmark"></i> {{ trimString($job['department']) }}
    </span>
    <span class="job-board-job-item job-board-job-item-general">
        <i class="fa fa-clock-o"></i> {{ __('message.posted') }} : {{ dateFormat($job['created_at']) }}
    </span>
    <span class="job-board-job-item job-board-job-item-general view-job-detail" 
        data-id="{{ $job_id }}"
        data-title="{{ $job['title'] }}">
        <i class="fa fa-eye"></i> {{ __('message.view') }}
    </span>
    <span class="job-board-job-item-separator-line" ></span>
    <span class="job-board-job-item job-board-job-item-yellow">
        <i class="fa fa-list"></i> {{ $job['quizes_count'] }} {{ __('message.quizes') }}
    </span>
    <span class="job-board-job-item job-board-job-item-yellow">
        <i class="fa fa-star-half-o"></i> {{ $job['traites_count'] }} {{ __('message.traites') }}
    </span>
</div>
@php $count++; @endphp
@endforeach
@else
<p>{{ __('message.no_jobs_found') }}</p>
@endif