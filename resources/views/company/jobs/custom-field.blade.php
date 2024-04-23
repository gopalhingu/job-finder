<div class="row custom-value-box">
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <input type="hidden" name="custom_field_ids[]" value="{{ encode($field['custom_field_id']) }}">
            <label>
            {{ __('message.label') }} 
            <i class="fa fa-trash text-red remove-custom-field" title="{{__('message.remove_custom_field')}}" data-id="{{ encode($field['custom_field_id']) }}"></i>
            </label>
            <input type="text" class="form-control" placeholder="{{__('message.enter_label')}}" name="labels[]" value="{{ $field['label'] }}" />
        </div>
    </div>
    <div class="col-md-9 col-sm-12">
        <div class="form-group">
            <label>{{ __('message.value') }}</label>
            <input type="text" class="form-control" placeholder="{{__('message.enter_value')}}" name="values[]" value="{{ $field['value'] }}" />
        </div>
    </div>
</div>