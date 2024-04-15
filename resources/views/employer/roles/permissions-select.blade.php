<select id='optgroup' multiple='multiple'>
    @foreach($permissions as $key => $values)
    @if (strtolower($key) == 'departments' && setting('departments_creation') == 'only_admin')
     <!-- Show nothing -->
    @elseif (strtolower($key) == 'job filters' && setting('job_filters_creation') == 'only_admin')
     <!-- Show nothing -->
    @else
    <optgroup label='{{ $key }}'>
        @foreach($values as $k => $v)
        <option value='{{ encode($v["id"]) }}' {{ $v['selected'] == 1 ? 'selected="selected"' : ''; }}>
        {{ $v['title'] }}
        </option>
        @endforeach
    </optgroup>
    @endif
    @endforeach
</select>