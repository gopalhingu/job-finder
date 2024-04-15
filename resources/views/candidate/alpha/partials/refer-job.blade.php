<div class="container">
    <form class="form" id="job_refer_form">
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="form-group form-group-account">
                    <label>{{ __('message.person_name') }} *</label>
                    <input type="hidden" name="job_id" id="job_id">
                    <input type="text" class="form-control" placeholder="John Doe" name="name" id="name">
                    <small class="form-text text-muted">{{ __('message.enter_person_name') }}</small>
                </div>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="form-group form-group-account">
                    <label>{{ __('message.person_email') }}  *</label>
                    <input type="text" class="form-control" placeholder="john.doe@example.com" name="email" id="email">
                    <small class="form-text text-muted">{{ __('message.enter_person_email') }}</small>
                </div>
            </div>
            <div class="col-md-12 col-lg-12">
                <div class="form-group form-group-account">
                    <label>{{ __('message.phone') }}</label>
                    <input type="text" class="form-control" placeholder="1234567890" name="phone" id="phone">
                    <small class="form-text text-muted">{{ __('message.enter_person_phone') }}.</small>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12">
                <div class="form-group form-group-account">
                    <button type="submit" class="btn btn-success" title="Save" id="job_refer_form_button">
                    <i class="fa fa-floppy-o"></i>{{ __('message.refer') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>