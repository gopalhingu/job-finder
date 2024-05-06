
<div class="modal-body">
    <form id="job_follow_form">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="input-group">
                    <input type="hidden" name="follow_job_id" id="follow_job_id">
                    <textarea class="form-control" id="description" name="description" rows="5" placeholder="{{ __('message.enter_person_name') }}" required="required"></textarea>
                </div>
            </div>
        </div>
        <div class="row text-center mt-2">
            <div class="col-md-12">
                <button id="job_follow_form_button" type="submit" class="btn btn-primary btn-sm">{{ __('message.submit') }}</button>
            </div>
        </div>
    </form>
</div>
