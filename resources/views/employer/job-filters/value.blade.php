@php
    $v = isset($val['title']) ? $val['title'] : '';
    $id = isset($val['job_filter_value_id']) ? encode($val['job_filter_value_id']) : '';
@endphp
<div>
    <div class="col-md-11">
        <div class="form-group">
            <input type="text" name="values[]" class="form-control" value="{{ $v }}" placeholder="{{__('message.enter_value')}}" />
            <input type="hidden" name="ids[]" class="form-control" value="{{ $id }}" />
        </div>
    </div>
    <div class="col-md-1 text-center">
        <div class="form-group">
            <i class="fa fa-trash text-red remove-value" data-id="{{ $id }}" title="{{__('message.remove_value')}}"></i>
        </div>
    </div>
</div>