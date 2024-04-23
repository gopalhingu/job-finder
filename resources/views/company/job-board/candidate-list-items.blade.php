@if ($candidates)
@foreach ($candidates as $candidate)
<div class="job-board-candidate-wrap">
    <div class="col-md-4 job-board-candidate-profile">
        <div class="row">
            <div class="col-md-4 job-board-candidate-left">
                <input type="checkbox" class="minimal job-board-candidate-select" 
                    data-id="{{ encode($candidate['candidate_id']) }}"
                    data-resume_id="{{ encode($candidate['resume_id']) }}">
                @php $thumb = candidateThumb($candidate['image']); @endphp
                @if ($thumb['image'])
                <img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" class="job-board-candidate-avatar">
                @else
                <img src="{{$thumb['error']}}" class="job-board-candidate-avatar">
                @endif
            </div>
            <div class="col-md-7 job-board-candidate-right">
                <h2 class="job-board-candidate-name view-resume" data-id="{{ encode($candidate['resume_id']) }}"
                    title="{{ $candidate['first_name'].' '.$candidate['last_name'] }}">
                    {{ trimString($candidate['first_name'].' '.$candidate['last_name'], 13) }}
                </h2>
                <p class="job-board-candidate-profile-item">{{ trimString($candidate['designation'], 30) }}</p>
                <p class="job-board-candidate-profile-item">{{ __('message.applied_on') }} : {{ dateFormat($candidate['created_at']) }}</p>
                @if ($candidate['resume_type'] == 'detailed')
                <p class="job-board-candidate-profile-item">
                    {{ __('message.experience') }} : {{ $candidate['experience'] }} {{ __('message.months') }}
                </p>
                <p class="job-board-candidate-profile-item">
                    <span class="job-board-resume-item" title="{{ $candidate['experiences'] }} {{ __('message.job_experiences') }}">
                    <i class="fa fa-history"></i> {{ $candidate['experiences'] }}
                    </span>
                    <span class="job-board-resume-item" title="{{ $candidate['languages'] }} {{ __('message.languages') }}">
                    <i class="fa fa-language"></i> {{ $candidate['languages'] }}
                    </span>
                    <span class="job-board-resume-item" title="{{ $candidate['qualifications'] }} {{ __('message.qualifications') }}">
                    <i class="fa fa-graduation-cap"></i> {{ $candidate['qualifications'] }}
                    </span>
                    <span class="job-board-resume-item" title="{{ $candidate['achievements'] }} {{ __('message.achievements') }}">
                    <i class="fa fa-trophy"></i> {{ $candidate['achievements'] }}
                    </span>
                    <span class="job-board-resume-item" title="{{ $candidate['references'] }} {{ __('message.references') }}">
                    <i class="fa fa-globe"></i> {{ $candidate['references'] }}
                    </span>
                </p>
                @else
                <a class="btn btn-warning btn-xs" target="_blank" href="{{ resumeThumb($candidate['file']) }}" title="{{ __('message.download') }}">
                <i class="fa fa-file"></i> {{ __('message.download') }}
                </a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-2 job-board-candidate-self">
        <p class="job-board-candidate-self-heading">
            <i class="fas fa-star-half-alt"></i> {{ __('message.self_assesment') }}
            {{ $candidate['traites_result'] }}%
        </p>
        <ul class="job-board-candidate-item-list">
            @if($candidate['traites'])
            @foreach($candidate['traites'] as $traite)
            <li title="{{ $traite['title'] }}">
                {{ trimString($traite['title'], 20) }} 
                <br />
                ({{ $traite['rating'] }}/5 - {{ $traite['rating'] != 0 ? ($traite['rating']/5)*100 : 0 }}%)
            </li>
            @endforeach
            @else
            <li>{{ __('message.not_assigned') }}</li>
            @endif
        </ul>
    </div>
    <div class="col-md-2 job-board-candidate-quiz">
        <p class="job-board-candidate-quiz-heading">
            <i class="fa fa-list"></i> {{ __('message.quizes') }} {{ $candidate['quizes_result'] }}%
        </p>
        <ul class="job-board-candidate-item-list">
            @if($candidate['quizes'])
            @foreach($candidate['quizes'] as $quiz)
            <li title="{{ $quiz['title'] }}">
                <i class="far fa-trash-alt text-red delete-candidate-quiz" data-id="{{ encode($quiz['id']) }}" title="Delete quiz"></i>
                {{ trimString($quiz['title'], 20) }}
                <br />
                ({{ $quiz['corrects'] }}/{{ $quiz['questions'] }} - 
                {{ $quiz['corrects'] != 0 ? round(($quiz['corrects']/$quiz['questions'])*100) : 0 }}%)
            </li>
            @endforeach
            @else
            <li>{{ __('message.not_assigned') }}</li>
            @endif
        </ul>
    </div>
    <div class="col-md-2 job-board-candidate-interview">
        <p class="job-board-candidate-interview-heading">
            <i class="fas fa-clipboard-list"></i> {{ __('message.interviews') }} {{ $candidate['interviews_result'] }}%
        </p>
        <ul class="job-board-candidate-item-list">
            @if($candidate['interviews'])
            @foreach($candidate['interviews'] as $interview)
            <li title="{{ $interview['title'] }}">
                <i class="far fa-trash-alt text-red delete-candidate-interview" data-id="{{ encode($interview['id']) }}" title="Delete Interview"></i>
                {{ trimString($interview['title'], 20) }} 
                <br />
                ({{ $interview['ratings'] }}/{{ $interview['questions']*10 }} - 
                {{ $interview['ratings'] != 0 ? round(($interview['ratings']/($interview['questions']*10))*100) : 0 }}%)
            </li>
            @endforeach
            @else
            <li>{{ __('message.not_assigned') }}</li>
            @endif
        </ul>
    </div>
    <div class="col-md-2 job-board-candidate-overall">
        <p class="job-board-candidate-overall-heading">{{ __('message.overall_result') }}</p>
        <p class="job-board-candidate-overall-result"><strong>{{ $candidate['overall_result'] }}%</strong>
            <br /><span class="job-board-candidate-status job-board-candidate-{{ $candidate['status'] }}">{{ strtoupper($candidate['status']) }}</span>
        </p>
    </div>
</div>
@endforeach
@else
<div class="job-board-candidate-wrap">
    <p class="job-board-candidate-wrap-not">{{ __('message.no_candidates_found') }}</p>
</div>
@endif