@if ($paginator->lastPage() > 1)
@php
    $params = '';
    $params .= '&search='. app('request')->input('search');
    $params .= '&departments='.app('request')->input('departments');
    $params .= '&filters='.app('request')->input('filters');
@endphp
<ul>
    <li {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a href="{{ $paginator->url(1) }}{{$params}}">&lt;</a>
    </li>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li>
            <a class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}" href="{{ $paginator->url($i).$params }}">{{ $i }}</a>
        </li>
    @endfor
    <li class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a href="{{ $paginator->url($paginator->currentPage()+1) }}{{$params}}" >&gt;</a>
    </li>
</ul>
@endif