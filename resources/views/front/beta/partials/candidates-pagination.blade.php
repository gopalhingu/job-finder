@if ($paginator->lastPage() > 1)
@php 
    $params = '&sort='.app('request')->input('sort'); 
    $params .= '&search='.app('request')->input('keyword');
    $params .= '&view='.app('request')->input('view');

    $params .= '&candidates_experiences_value='.app('request')->input('candidates_experiences_value');
    $params .= '&candidates_experiences_range='.app('request')->input('candidates_experiences_range');
    $params .= '&candidates_qualifications_value='.app('request')->input('candidates_qualifications_value');
    $params .= '&candidates_qualifications_range='.app('request')->input('candidates_qualifications_range');
    $params .= '&candidates_achievements_value='.app('request')->input('candidates_achievements_value');
    $params .= '&candidates_achievements_range='.app('request')->input('candidates_achievements_range');
    $params .= '&candidates_skills_value='.app('request')->input('candidates_skills_value');
    $params .= '&candidates_skills_range='.app('request')->input('candidates_skills_range');
    $params .= '&candidates_languages_value='.app('request')->input('candidates_languages_value');
    $params .= '&candidates_languages_range='.app('request')->input('candidates_languages_range');

@endphp
<ul>
    <li {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a href="{{ $paginator->url(1).$params }}">&lt;</a>
    </li>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li>
            <a class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}" href="{{ $paginator->url($i).$params }}">{{ $i }}</a>
        </li>
    @endfor
    <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a href="{{ $paginator->url($paginator->currentPage()+1).$params }}">&gt;</a>
    </li>
</ul>
@endif
