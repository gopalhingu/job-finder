@extends('admin.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">{{__('message.jobs')}}</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{route('admin-dashboard')}}">{{__('message.home')}}</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin-jobs-create-or-edit')}}">{{__('message.jobs')}}</a></li>
                        <li class="breadcrumb-item active">{{ __('message.create') }}</li>
                    </ol>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{__('message.edit')}}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            @if(empAllowedTo('create_jobs') || empAllowedTo('edit_jobs'))
                            <form id="admin_job_create_update_form">
                                <input type="hidden" name="job_id" value="{{ encode($job['job_id']) }}" />
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__('message.title') }}</label>
                                                <input type="text" class="form-control" placeholder="{{__('message.enter_title')}}" 
                                                    name="title" value="{{ $job['title'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{__('message.slug') }}</label>
                                                <input type="text" class="form-control" 
                                                        placeholder="{{__('message.will_auto_generate_if_blank')}}" 
                                                        name="slug" value="{{ $job['slug'] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('message.last_date') }}</label>
                                                <input type="date" class="form-control" name="last_date" value="{{ $job['last_date'] ? date('Y-m-d', strtotime($job['last_date'])) : '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('message.hide_job_after_last_date') }}</label>
                                                <select class="form-control" name="hide_job_after_last_date">
                                                <option value="1" {{ sel($job['hide_job_after_last_date'], 1) }}>{{ __('message.yes') }}</option>
                                                <option value="0" {{ sel($job['hide_job_after_last_date'], 0) }}>{{ __('message.no') }}</option>
                                                </select>
                                            </div>
                                        </div>                                
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('message.departments') }}</label>
                                                <select class="form-control select2" id="departments" name="department_id">
                                                    <option value="">{{ __('message.none') }}</option>
                                                    @foreach ($departments as $key => $value)
                                                    <option value="{{ encode($value['department_id']) }}" {{ sel($job['department_id'], $value['department_id']) }}>{{ $value['title'].' ('.($value['company'] ? $value['company'] : 'admin').')' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('message.employer') }}</label>
                                                <select class="form-control select2" id="employers" name="employer_id">
                                                    @foreach ($employers as $key => $value)
                                                    <option value="{{ $value['employer_id'] }}" {{ sel($job['employer_id'], $value['employer_id']) }}>{{ $value['company'].' ('.$value['first_name'].' '.$value['last_name'].')' }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ __('message.status') }}</label>
                                                <select class="form-control" name="status">
                                                    <option value="0" {{ sel($job['status'], '0') }}>{{ __('message.no') }}</option>
                                                    <option value="1" {{ sel($job['status'], '1') }}>{{ __('message.yes') }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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
                                                    {{__('message.description')}}
                                                </label>
                                                <textarea id="job-description" name="description">{{$job['description']}}</textarea>
                                            </div>
                                        </div>
                                        @if ($job_filters)
                                        @foreach ($job_filters as $filter)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>{{ $filter['title'].' ('.($filter['company'] ? $filter['company'] : 'admin').')' }}</label>
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
                                        @endif
                                        <div class="col-md-12">
                                            <hr />
                                            <div class="form-group">
                                                <label>{{ __('message.traites') }}</label>
                                                <select class="form-control select2" id="traites[]" name="traites[]" multiple="multiple">
                                                @foreach ($traites as $key => $value)
                                                @php $jobTraits = $job['traites'] ? explode(',', $job['traites']) : array(); @endphp
                                                <option value="{{ encode($value['traite_id']) }}" {{ sel($value['traite_id'], $jobTraits) }}>{{ $value['title'].' ('.($value['company'] ? $value['company'] : 'admin').')' }}</option>
                                                @endforeach
                                                </select>
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
                                                <label>{{ __('message.quizes') }}</label>
                                                <select class="form-control select2" id="quizes[]" name="quizes[]" multiple="multiple">
                                                @foreach ($quizes as $key => $value)
                                                @php $jobQuizes = $job['quizes'] ? explode(',', $job['quizes']) : array() @endphp
                                                <option value="{{ encode($value['quiz_id']) }}" {{ sel($value['quiz_id'], $jobQuizes) }}>{{ $value['title'].' ('.($value['company'] ? $value['company'] : 'admin').')' }}</option>
                                                @endforeach
                                                </select>
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


                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-blue" id="admin_job_create_update_form_button">{{__('message.save') }}</button>
                                </div>
                            </form>
                            @endif
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('a-assets')}}/js/cf/job.js"></script>
@endsection