<form id="resume-form" class="download-resume-form" method="POST" 
    action="{{ url('/') }}/employer/candidates/resume-download">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="ids" value="{{ encode($resume_id) }}">
    <button type="submit" class="btn btn-primary">{{ __('message.download') }}</button>
</form>
@if ($resume_file)
<a href="{{ resumeThumb($resume_file) }}" title="Download" class="btn btn-primary">
{{ __('message.download') }} File
</a>
@endif
<br /><br />
@if ($resume)
<table>
    <tr>
        <td>
            @php $thumb = candidateThumb($resume['image']); @endphp
            <img src="{{$thumb['image']}}" height="70" onerror='this.src="{{$thumb['error']}}"' />
        </td>
        <td>
            <h2 class="job-board-resume-section-title">
                {{ $resume['first_name'].' '.$resume['last_name'] }}
            </h2>
            <p>
                {{
                    ($resume['email'] ? $resume['email'] : '') 
                    . ($resume['phone1'] ? ", ".$resume['phone1'] : '')
                    . ($resume['phone2'] ? ", ".$resume['phone2'] : '')
                    . ($resume['address'] ? "<br /> ".$resume['address'] : '')
                    . ($resume['city'] ? ", ".$resume['city'] : '')
                    . ($resume['state'] ? ", ".$resume['state'] : '')
                    . ($resume['country'] ? ", ".$resume['country'] : '')
                }}
            </p>
        </td>
    </tr>
</table>

<h2 class="job-board-resume-section-title">{{ __('message.objective') }}</h2>
<p>{{ $resume['objective'] }}</p>

<h2 class="job-board-resume-section-title">{{ __('message.job_experiences') }}</h2>
@if ($resume['experiences'])
<div class="circles-content-element circles-list">
    <ol>
        @foreach ($resume['experiences'] as $experience)
        <li>
            <p class="job-board-resume-job-title">{{ $experience['title'] }} - {{ $experience['company'] }}</p>
            <p class="job-board-resume-job-duration">
                ({{ timeFormat($experience['from']) }} - {{ timeFormat($experience['to']) }})
            </p>
            <p class="job-board-resume-job-description">{{ $experience['description'] }}</p>
        </li>
        @endforeach
    </ol>
</div>
@else
<p>{{ __('message.there_are_no_experiences') }}</p>
@endif

<h2 class="job-board-resume-section-title">{{ __('message.qualifications') }}</h2>
@if ($resume['qualifications'])
<div class="circles-content-element circles-list">
    <ol>
        @foreach ($resume['qualifications'] as $qualification)
        <li>
            <p class="job-board-resume-job-title">{{ $qualification['title'] }} - {{ $qualification['institution'] }}</p>
            <p class="job-board-resume-job-duration">
                ({{ timeFormat($qualification['from']) }} - {{ timeFormat($qualification['to']) }})
            </p>
            <p class="job-board-resume-job-description">
                {{ $qualification['marks'] }} Out of {{ $qualification['out_of'] }}
            </p>
        </li>
        @endforeach
    </ol>
</div>
@else
<p>{{ __('message.there_are_no_qualifications') }}</p>
@endif

<h2 class="job-board-resume-section-title">{{ __('message.languages') }}</h2>
@if ($resume['languages'])
<div class="circles-content-element circles-list">
    <ol>
        @foreach ($resume['languages'] as $language)
        <li>
            <p class="job-board-resume-job-title">{{ $language['title'] }} ({{ $language['proficiency'] }})</p>
        </li>
        @endforeach
    </ol>
</div>
@else
<p>{{ __('message.there_are_no_languages') }}</p>
@endif

<h2 class="job-board-resume-section-title">{{ __('message.achievements') }}</h2>
@if ($resume['achievements'])
<div class="circles-content-element circles-list">
    <ol>
        @foreach ($resume['achievements'] as $achievement)
        <li>
            <p class="job-board-resume-job-title">{{ $achievement['title'] }} ({{ $achievement['type'] }})</p>
            @if ($achievement['date'])
            <p class="job-board-resume-job-duration">
                ({{ $achievement['date'] }})
            </p>
            @endif
            @if ($achievement['link'])
            <p class="job-board-resume-job-duration">
                ({{ $achievement['link'] }})
            </p>
            @endif
            <p class="job-board-resume-job-description">
                {{ $achievement['description'] }}
            </p>
        </li>
        @endforeach
    </ol>
</div>
@else
<p>{{ __('message.there_are_no_achievements') }}</p>
@endif

<h2 class="job-board-resume-section-title">{{ __('message.references') }}</h2>
@if ($resume['references'])
<div class="circles-content-element circles-list">
    <ol>
        @foreach ($resume['references'] as $reference)
        <li>
            <p class="job-board-resume-job-title">{{ $reference['title'] }} ({{ $reference['relation'] }})</p>
            @if ($reference['company'])
            <p class="job-board-resume-job-duration">
                ({{ $reference['company'] }})
            </p>
            @endif
            @if ($reference['phone'])
            <p class="job-board-resume-job-duration">
                ({{ $reference['phone'] }})
            </p>
            @endif
            <p class="job-board-resume-job-duration">
                ({{ $reference['email'] }})
            </p>
        </li>
        @endforeach
    </ol>
</div>
@else
<p>{{ __('message.there_are_no_references') }}</p>
@endif
@else
<p>No Resume Found</p>
@endif