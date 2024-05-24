<div class="modal-body">
    <form id="job_tags_create_update_form">
    @csrf
    <input type="hidden" name="id" id="id" value="{{$jobTags['id']}}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.job_tag_name') }}</label>
                    <input type="text" class="form-control" name="name" value="{{$jobTags['name']}}">
                </div>
            </div>          
            <div class="col-md-12">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
                <button type="submit" class="btn btn-primary btn-blue" id="job_tags_create_update_form_button">
                {{ __('message.save') }}
                </button>
            </div>
        </div>
    </div>
    </form>
</div>
<div class="modal-footer">
</div>