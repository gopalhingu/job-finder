<div class="job-listing-left">
    <div class="input-group job-listing-job-search">
        <input type="text" class="form-control job-search-value" placeholder="{{__('message.search_jobs')}}" 
            value="{{ $search }}">
        <span class="input-group-btn">
        <button type="button" class="btn btn-primary btn-blue btn-flat job-search-button">
        <i class="fa fa-search"></i>
        </button>
        </span>
    </div>

    <!-- Departments Filters -->
    @if($departments)
    <hr />
    <p class="job-listing-heading">
        <span class="job-listing-heading-text"><i class="fa fa-filter"></i> {{ __('message.departments') }}</span>
        <span class="job-listing-heading-line"></span>
    </p>
    <ul class="job-listing-filters-list">
        @foreach ($departments as $key => $value)
        <li title="{{ $value['title'] }}">
            <input type="checkbox" 
                class="department-check" 
                value="{{ encode($value['department_id']) }}"
                {{ jobsCheckboxSel($departmentsSel, $value['department_id']) }} 
            />
            {{ trimString($value['title']) }}
        </li>
        @endforeach
    </ul>
    @endif

    <!-- Custom Job Filters -->
    @if ($job_filters)
    <input type="hidden" id="job_filters_sel" value='{{ $filtersEncoded }}' />
    @foreach ($job_filters as $filter)
        <!--For Multi Select-->
        @if ($filter['type'] == 'checkbox')
        <hr />
        <p class="job-listing-heading" title="{{ $filter['title'] }}">
            <span class="job-listing-heading-text">
            <i class="fa fa-filter"></i> {{ trimString($filter['title']) }}
            </span>
            <span class="job-listing-heading-line"></span>
        </p>
        <ul class="job-listing-filters-list job-filter" data-id="{{ encode($filter['job_filter_id'], false) }}">
            @foreach ($filter['values'] as $v)
            <li>
                @php 
                    $job_filter_value_ids = isset($filtersSel[$filter['job_filter_id']]) ? $filtersSel[$filter['job_filter_id']] : array(); 
                    $selCb = sel3($filter['job_filter_id'], $v['id'], $filtersSel);
                @endphp
                <input 
                    type="checkbox" 
                    data-id="{{ encode($filter['job_filter_id'], false) }}"
                    value="{{ encode($v['id'], false) }}" 
                    class="job-filter-check" 
                    {{ $selCb }}
                /> 
                {{ trimString($v['title']) }}
            </li>
            @endforeach
        </ul>
        @else
        <!--For Single Select-->
        <hr />
        <p class="job-listing-heading" title="{{ $filter['title'] }}">
            <span class="job-listing-heading-text">
            <i class="fa fa-filter"></i> {{ trimString($filter['title']) }}
            </span>
            <span class="job-listing-heading-line"></span>
        </p>
        <select class="form-control select2 job-listing-filters-dd job-filter-dd job-filter"
            data-id="{{ encode($filter['job_filter_id'], false) }}">
            <option value="">{{ __('message.none') }}</option>
            @foreach ($filter['values'] as $v)
            @php 
                $selDd = sel3($filter['job_filter_id'], $v['id'], $filtersSel);
            @endphp
            <option value="{{ encode($v['id'], false) }}" {{ $selDd ? 'selected' : ''; }}>{{ $v['title'] }}</option>
            @endforeach
        </select>
        @endif
    @endforeach
    @endif
</div>
<br /><br /><br />