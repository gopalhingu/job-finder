@php
    $success = isset($success) ? $success : session()->get('success');
    $info = isset($info) ? $info : session()->get('info');
    $warning = isset($warning) ? $warning : session()->get('warning');
    $error = isset($error) ? $error : session()->get('error');
@endphp
@if($success || $info || $warning || $error)
<div class="row errors-container">
    <div class="col-md-12">
        @if($success)
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $success }}
        </div>
        @endif
        @if($info)
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $info }}
        </div>
        @endif
        @if($warning)
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $warning }}
        </div>
        @endif
        @if($error)
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            {{ $error }}
        </div>
        @endif
    </div>
</div>
@endif

@if ($errors->any())
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <ul>
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

@if(isset($validation_errors))
<div class="row errors-container">
    <div class="col-md-12">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <ul>
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

@if(isset($error) && !isset($validation_errors))
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
