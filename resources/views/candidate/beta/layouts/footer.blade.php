<!-- Modal Refer Job - Globally Accessible -->
<div id="modal-beta" class="modal-beta modal fade modal-refer-job">
    <div class="modal-dialog">
        <div class="modal-content modal-body-container">
        </div>
    </div>
</div>

@php $footer = footerColumns(); @endphp
<div class="section-footer-alpha">
    @if($footer['columns'])
    <div class="container">
        <div class="row">
            @foreach ($footer['columns'] as $column)
            <div class="col-lg-{{ $footer['column_count'] }} col-md-12 col-sm-12">
                <div class="section-footer-alpha-col-1">
                    {!! $column !!}
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>