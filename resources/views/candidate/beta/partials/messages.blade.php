@if ($errors->any())
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <ul class="url-validation-errors">
                @foreach ($errors->all() as $error2)
                    <li>{!! $error2 !!}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@if(session()->has('message'))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {!! session()->get('message') !!}
        </div>
    </div>
</div>
@endif

@if (isset($validation_errors))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <ul class="url-validation-errors">
                @foreach ($validation_errors as $errorDum)
                    @foreach ($errorDum as $err)
                    <li>{!! $err !!}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@if(isset($error))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {!! $error !!}
        </div>
    </div>
</div>
@endif

@if(isset($message))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {!! $message !!}
        </div>
    </div>
</div>
@endif

@if(isset($success))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {!! $success !!}
        </div>
    </div>
</div>
@endif
