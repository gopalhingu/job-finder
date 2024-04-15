<!-- Modal Register / Login / Forgot Password - Globally Accessible -->
<div id="modal-alpha" class="modal-alpha modal fade">
    @php 
        $modal_size = setting('display_info_in_login_modal') == 'yes' ? 'modal-lg' : '';
    @endphp
    <div class="modal-dialog {{ $modal_size }}">
        <div class="modal-content modal-body-container">
        </div>
    </div>
</div>

<!-- Modal Refer Job - Globally Accessible -->
<div id="modal-beta" class="modal-beta modal fade modal-refer-job">
    <div class="modal-dialog">
        <div class="modal-content modal-body-container">
        </div>
    </div>
</div>

<div class="section-footer-alpha">
    <div class="container">
        <div class="row">
            @php $width = footerColWidth() @endphp
            <div class="col-lg-{{$width}} col-md-12 col-sm-12">
                <div class="section-footer-alpha-col-1">
                    {!! setting('footer_column_1') !!}
                </div>
            </div>
            <div class="col-lg-{{$width}} col-md-12 col-sm-12">
                <div class="section-footer-alpha-col-2">
                    {!! setting('footer_column_2') !!}
                </div>
            </div>
            <div class="col-lg-{{$width}} col-md-12 col-sm-12">
                <div class="section-footer-alpha-col-3">
                    {!! setting('footer_column_3') !!}
                </div>
            </div>
            <div class="col-lg-{{$width}} col-md-12 col-sm-12">
                <div class="section-footer-alpha-col-4">
                    {!! setting('footer_column_4') !!}
                </div>
            </div>
        </div>
    </div>
</div>