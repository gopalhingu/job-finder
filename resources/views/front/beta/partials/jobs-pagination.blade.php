@if ($paginator->lastPage() > 1)
@php 
    $params = '&sort='.app('request')->input('sort'); 
    $params .= '&search='.app('request')->input('search');
    $params .= '&departments='.app('request')->input('departments');
    $params .= '&companies='.app('request')->input('companies');
    $params .= '&filters='.app('request')->input('filters');
    $params .= '&view='.app('request')->input('view');
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
