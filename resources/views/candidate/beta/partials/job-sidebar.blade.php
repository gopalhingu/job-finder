<div class="section-sidebar-beta-container">
    <div class="row">
        <div class="section-sidebar-beta-item">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-sidebar-beta-item-heading">
                    <i class="fa-icon fa-solid fa-bullseye"></i> <h3>{{__('message.keywords')}}</h3>
                </div>
                <div class="section-sidebar-beta-item-content">
                    <input type="hidden" id="jobs-page" value="{{$page}}">
                    <input type="text" class="job-search-value" value="{{$search}}" />
                </div>
            </div>
        </div>
        @if($departments)
        <div class="section-sidebar-beta-item">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="section-sidebar-beta-item-heading">
                    <i class="fa-icon fa fa-briefcase"></i> <h3>{{__('message.departments')}}</h3>
                </div>
                <div class="section-sidebar-beta-item-content">
                    <ul>
                        <label for="{{encode(32859, false)}}">{{__('message.all')}}</label> 
                        <input 
                            class="department-check" 
                            id="{{encode(32859, false)}}" 
                            value="" 
                            type="radio" 
                            name="department" 
                            {{ jobsCheckboxSel($departmentsSel, '') }} 
                        />
                        @foreach($departments as $dept)
                        <li>
                            <label for="{{encode($dept['department_id'], false)}}">{{$dept['title']}}</label> 
                            <input 
                                class="department-check" 
                                id="{{encode($dept['department_id'], false)}}" 
                                value="{{ encode($dept['department_id']) }}" 
                                type="radio" 
                                name="department" 
                                {{ jobsCheckboxSel($departmentsSel, $dept['department_id']) }} 
                            />
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif
        @if($job_filters)
        <input type="hidden" id="job_filters_sel" value='{{ $filtersEncoded }}' />
        @foreach($job_filters as $key => $jf)
            @if($jf['type'] == 'dropdown')
            <div class="section-sidebar-beta-item">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="section-sidebar-beta-item-heading">
                        <i class="fa-icon {{$jf['icon'] ? $jf['icon'] : 'fa-solid fa-paperclip'}}"></i> 
                        <h3>{{$jf['title']}}</h3>
                    </div>
                    <div class="section-sidebar-beta-item-content">
                        <select class="job-filter-dd" data-id="{{ encode($jf['job_filter_id'], false) }}">
                            <option value="">{{__('message.all')}}</option>
                            @foreach($jf['values'] as $k => $jfv)
                            @php 
                                $selDd = sel3($jf['job_filter_id'], $jfv['id'], $filtersSel);
                            @endphp                                                                         
                            <option value="{{encode($jfv['id'], false)}}" {{ $selDd ? 'selected' : ''; }}>
                                {{$jfv['title']}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            @else
            <div class="section-sidebar-beta-item">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="section-sidebar-beta-item-heading">
                        <i class="fa-icon {{$jf['icon'] ? $jf['icon'] : 'fa-solid fa-paperclip'}}"></i> 
                        <h3>{{$jf['title']}}</h3>
                    </div>
                    <div class="section-sidebar-beta-item-content">
                        <ul class="job-filter" data-id="{{ encode($jf['job_filter_id'], false) }}">
                            <li>
                                <label for="{{slugify($jf['title']).'-100'}}">{{__('message.all')}}</label> 
                                <input 
                                    type="radio" 
                                    class="job-filter-radio" 
                                    id="{{slugify($jf['title']).'-100'}}" 
                                    data-id="{{ encode($jf['job_filter_id'], false) }}"
                                    value="" 
                                    name="{{slugify($jf['title'])}}" 
                                    {{ empty($filtersSel[$jf['job_filter_id']]) ? 'checked="checked"' : '' }}
                                />
                            </li>
                            @foreach($jf['values'] as $k => $jfv)
                            @php 
                                $job_filter_value_ids = isset($filtersSel[$jf['job_filter_id']]) ? $filtersSel[$jf['job_filter_id']] : array(); 
                                $selCb = sel3($jf['job_filter_id'], $jfv['id'], $filtersSel);
                            @endphp                         
                            <li>
                                <label for="{{slugify($jf['title']).'-'.$k}}">{{$jfv['title']}}</label>
                                <input 
                                    type="radio" 
                                    class="job-filter-radio" 
                                    id="{{slugify($jf['title']).'-'.$k}}" 
                                    data-id="{{ encode($jf['job_filter_id'], false) }}"
                                    name="{{slugify($jf['title'])}}" 
                                    value="{{ encode($jfv['id'], false) }}" 
                                    {{ $selCb }}
                                />
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="section-sidebar-beta-item section-sidebar-beta-item-btn-container">
                <div class="section-sidebar-beta-item-button">
                    <button class="btn job-search-btn">{{__('message.search')}}</button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
