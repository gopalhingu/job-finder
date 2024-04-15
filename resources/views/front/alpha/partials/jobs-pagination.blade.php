@if ($paginator->lastPage() > 1)
@php 
    $params = '&sort='.app('request')->input('sort'); 
    $params .= '&search='.app('request')->input('search');
    $params .= '&departments='.app('request')->input('departments');
    $params .= '&companies='.app('request')->input('companies');
    $params .= '&filters='.app('request')->input('filters');
@endphp
<ul class="pagination">
    <li class="page-item {{ ($paginator->currentPage() == 1) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->url(1).$params }}">&lt;</a>
    </li>
    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
        <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
            <a class="page-link" href="{{ $paginator->url($i).$params }}">{{ $i }}</a>
        </li>
    @endfor
    <li class="page-item {{ ($paginator->currentPage() == $paginator->lastPage()) ? ' disabled' : '' }}">
        <a class="page-link" href="{{ $paginator->url($paginator->currentPage()+1).$params }}" >&gt;</a>
    </li>
</ul>
@endif