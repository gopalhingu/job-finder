@if ($errors->any())
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <ul class="url-validation-errors">
                @foreach ($errors->all() as $error2)
                    <li>{{ $error2 }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endif

@if(session()->has('message'))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ session()->get('message') }}
        </div>
    </div>
</div>
@endif

@if (isset($validation_errors))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <ul class="url-validation-errors">
                @foreach ($validation_errors as $errorDum)
                    @foreach ($errorDum as $err)
                    <li>{{ $err }}</li>
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
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $error }}
        </div>
    </div>
</div>
@endif

@if(isset($message))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $message }}
        </div>
    </div>
</div>
@endif

@if(isset($success))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $success }}
        </div>
    </div>
</div>
@endif
