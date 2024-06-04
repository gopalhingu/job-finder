@extends('company.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-briefcase"></i> {{ __('message.jobs') }}<small></small></h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/') }}/company/dashboard"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="fa fa-briefcase"></i> {{ __('message.job') }}</li>
            <li class="active">{{ __('message.create') }}</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            @if(empAllowedTo('create_jobs') || empAllowedTo('edit_jobs'))
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('message.details') }}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" id="company_job_create_update_form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.title') }}</label>
                                        <input type="hidden" name="job_id" value="{{ encode($job['job_id']) }}" />
                                        <input type="text" class="form-control" placeholder="{{__('message.enter_title')}}" 
                                            name="title" value="{{ $job['title'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.slug') }}</label>
                                        <input type="text" class="form-control" placeholder="{{__('message.will_auto_generate_if_blank')}}" 
                                            name="slug" value="{{ $job['slug'] }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.last_date') }}</label>
                                        <input type="date" class="form-control" name="last_date" value="{{ $job['last_date'] ? date('Y-m-d', strtotime($job['last_date'])) : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.hide_job_after_last_date') }}</label>
                                        <select class="form-control" name="hide_job_after_last_date">
                                        <option value="1" {{ sel($job['hide_job_after_last_date'], 1) }}>{{ __('message.yes') }}</option>
                                        <option value="0" {{ sel($job['hide_job_after_last_date'], 0) }}>{{ __('message.no') }}</option>
                                        </select>
                                    </div>
                                </div>                                
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>
                                        {{ __('message.departments') }}
                                        <button type="button" 
                                            class="btn btn-xs btn-warning btn-blue create-or-edit-department" 
                                            data-id="" 
                                            title="Add New Department">
                                        <i class="fa fa-plus"></i>
                                        </button>
                                        </label>
                                        <select class="form-control select2" id="departments" name="department_id">
                                            <option value="">{{ __('message.none') }}</option>
                                            @foreach ($departments as $key => $value)
                                            <option value="{{ encode($value['department_id']) }}" {{ sel($job['department_id'], $value['department_id']) }}>{{ $value['title'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.status') }}</label>
                                        <select class="form-control" name="status">
                                        <option value="1" {{ sel($job['status'], 1) }}>{{ __('message.active') }}</option>
                                        <option value="0" {{ sel($job['status'], 0) }}>{{ __('message.inactive') }}</option>
                                        </select>
                                    </div>
                                    <br />
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ __('message.is_static_allowed') }}</label>
                                        <select class="form-control" name="is_static_allowed">
                                        <option value="0" {{ sel($job['is_static_allowed'], 0) }}>{{ __('message.no') }}</option>
                                        <option value="1" {{ sel($job['is_static_allowed'], 1) }}>{{ __('message.yes') }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>
                                            {{ __('message.description') }}
                                            <i class="fa fa-code valid-html" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="{{__('message.valid_html_msg')}}"></i>
                                        </label>
                                        <textarea id="description" name="description" rows="10" cols="80">
                                        {!! $job['description'] !!}
                                        </textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr />
                                </div>
                                @if ($job_filters)
                                @foreach ($job_filters as $filter)
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label>{{ $filter['title'] }}</label>
                                        <select class="form-control select2" id="{{ encode($filter['job_filter_id']) }}" 
                                            name="filters[{{ encode($filter['job_filter_id']) }}][]" multiple="multiple">
                                        @foreach ($filter['values'] as $v)
                                            @php $sel = sel2($filter['job_filter_id'], $job['job_filter_ids'], $v['id'], $job['job_filter_value_ids']) @endphp
                                            <option value="{{ encode($v['id']) }}" {{ $sel }}>{{ $v['title'] }}
                                            </option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <div class="row resume-item-edit-box-section">
                                    <div class="col-md-12 col-lg-12">
                                        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ __('message.no_job_filters') }}</p>
                                    </div>
                                </div>
                                @endif
                                <div class="col-sm-12">
                                    <hr />
                                    <div class="form-group">
                                        <label>{{ __('message.traites') }}</label>
                                        <select class="form-control select2" id="traites[]" name="traites[]" multiple="multiple">
                                        @foreach ($traites as $key => $value)
                                        @php $jobTraits = $job['traites'] ? explode(',', $job['traites']) : array(); @endphp
                                        <option value="{{ encode($value['traite_id']) }}" {{ sel($value['traite_id'], $jobTraits) }}>{{ $value['title'] }}</option>
                                        @endforeach
                                        </select>
                                        <br />
                                        <br />
                                        <b>{{ __('message.notes') }}</b><br />
                                        <ul>
                                            <li>{{ __('message.traites_can_not_be_assigned') }}</li>
                                            <li>{{ __('message.traites_can_only_be_answerd') }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <hr />
                                    <div class="form-group">
                                        <label>{{ __('message.job_tag') }}</label>
                                        <select class="form-control select2" id="jobTags[]" name="jobTags[]" multiple="multiple">
                                        @foreach($job_tags as $jobTags)
                                        @php $jobTagsData = $job['userJobTags'] ? explode(',', $job['userJobTags']) : array(); @endphp
                                        <option value="{{ encode($jobTags['id']) }}" {{ sel($jobTags['id'], $jobTagsData) }}>{{$jobTags['name']}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <hr />
                                    <div class="form-group">
                                        <label>{{ __('message.quizes') }}</label>
                                        <select class="form-control select2" id="quizes[]" name="quizes[]" multiple="multiple">
                                        @foreach ($quizes as $key => $value)
                                        @php $jobQuizes = $job['quizes'] ? explode(',', $job['quizes']) : array() @endphp
                                        <option value="{{ encode($value['quiz_id']) }}" {{ sel($value['quiz_id'], $jobQuizes) }}>{{ $value['title'] }}</option>
                                        @endforeach
                                        </select>
                                        <br />
                                        <br />
                                        <b>{{ __('message.notes') }}</b><br />
                                        <ul>
                                            <li>{{ __('message.quizes_can_be_assigned') }}</li>
                                            <li>{{ __('message.quizes_are_attached_to') }}</li>
                                            <li>{{ __('message.quizes_assigned_from_here') }}</li>
                                            <li>{{ __('message.additional_quizes_can_be') }}</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <hr />
                                    <b>{{ __('message.in_general') }}</b><br />
                                    <ul>
                                        <li>{{ __('message.traites_can_be_assigned') }}</li>
                                        <li>{{ __('message.traites_can_be_assigned_before_and_or') }}</li>
                                        <li>{{ __('message.traites_can_be_assigned_only_after') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary" id="company_job_create_update_form_button">
                            {{ __('message.save') }}
                            </button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
            @endif
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('company-assets')}}/js/cf/department.js"></script>
<script src="{{url('company-assets')}}/js/cf/job.js"></script>
@endsection