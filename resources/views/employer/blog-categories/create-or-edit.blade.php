<form id="employer_blog_category_create_update_form">
    <input type="hidden" name="blog_category_id" value="{{ encode($blog_category['blog_category_id']) }}" />
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('message.title') }}</label>
                    <input type="text" class="form-control" name="title" value="{{ ($blog_category['title']) }}">
                </div>
            </div>
            <div class="col-md-12">
                <label>{{ __('message.status') }}</label>
                <select class="form-control" name="status">
                    <option value="1" {{ sel($blog_category['status'], 1) }}>{{ __('message.active') }}</option>
                    <option value="0" {{ sel($blog_category['status'], 0) }}>{{ __('message.inactive') }}</option>
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="submit" class="btn btn-primary btn-blue" id="employer_blog_category_create_update_form_button">
        {{ __('message.save') }}
        </button>
    </div>
</form>