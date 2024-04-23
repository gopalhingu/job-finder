<div class="row">
    <div class="col-md-12">
        <div class="box box-solid">
            <!-- /.box-header -->
            <div class="box-body">      
                @if ($type == 'detailed')
                {!! $resume !!}
                @else
                <a class="btn btn-warning" href="{{ resumeThumb($file) }}"  title="{{ __('message.download') }}">
                    <i class="fa fa-file"></i> {{ __('message.download') }}
                </a>
                <br />
                {{ __('message.file_based_resume') }}
                @endif
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <!-- ./col -->
</div>