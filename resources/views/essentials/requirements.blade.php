@extends('essentials.layout')
@section('content')
<section id="contain">
    <div class="install-block-lp">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 install-block-in-lp">
                    <div class="install-box-lp">
                        <div class="install-box-in-lp">
                            <form id="install_form">
                                @csrf
                                <div class="install-title-lp requirement-heading">Application Requirements</div>
                                <div class="requirement-label">
                                    @if (phpversion() >= 7.3)
                                        <?php sessionVariables('php_version', 'true'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/found.png" title="Found" height="20"/> PHP Version : (version <?php echo phpversion(); ?>)
                                        <br />
                                    @else
                                        <?php sessionVariables('php_version', 'false'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/not-found.png" title="Found" height="20"/> PHP Version should be 5.6 or greater. (Your version <?php echo phpversion(); ?>)
                                    @endif
                                    <span class="requirement-info-text">For overall application functioning.</span>
                                </div>
                                <div class="requirement-label">
                                    @if (extension_loaded('pdo'))
                                        <?php sessionVariables('pdo', 'true'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/found.png" title="Found" height="20"/> PDO extension enabled
                                    @else
                                        <?php sessionVariables('pdo', 'false'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/not-found.png" title="Found" height="20"/> PDO extension disabled
                                    @endif
                                    <span class="requirement-info-text">For database connections.</span>
                                </div>
                                <div class="requirement-label">
                                    @if (extension_loaded('gd'))
                                        <?php sessionVariables('gd', 'true'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/found.png" title="Found" height="20"/> GD Enabled
                                    @else
                                        <?php sessionVariables('gd', 'false'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/not-found.png" title="Found" height="20"/> GD Disabled
                                    @endif
                                    <span class="requirement-info-text">For image manipulation.</span>
                                </div>
                                <div class="requirement-label">
                                    @if (extension_loaded('openssl'))
                                        <?php sessionVariables('openssl', 'true'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/found.png" title="Found" height="20"/> OpenSSL Enabled
                                    @else
                                        <?php sessionVariables('openssl', 'false'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/not-found.png" title="Found" height="20"/> OpenSSL Disabled
                                    @endif
                                    <span class="requirement-info-text">For latest security standards.</span>
                                </div>
                                <div class="requirement-label">
                                    @if (function_exists('curl_version'))
                                        <?php sessionVariables('curl', 'true'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/found.png" title="Found" height="20"/> CURL Enabled (version <?php echo curl_version()['version']; ?>)
                                    @else
                                        <?php sessionVariables('curl', 'false'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/not-found.png" title="Found" height="20"/> CURL Disabled
                                    @endif
                                    <span class="requirement-info-text">For cross site requests.</span>
                                </div>
                                <div class="requirement-label">
                                    @if (is_writable(base_path().'/public/'))
                                        <?php sessionVariables('uploads_writeable', 'true'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/found.png" title="Found" height="20"/> Uploads folder writeable
                                    @else
                                        <?php sessionVariables('uploads_writeable', 'false'); ?>
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/not-found.png" title="Found" height="20"/> Uploads folder not writeable
                                    @endif
                                    <span class="requirement-info-text">For uploading images and files.</span>
                                </div>
                                @if (allVariablesTrue())
                                    <div class="install-btn-lp">
                                        <a href="<?php echo base_url(true); ?>/install/database" style="color: white" type="submit" class="btn-common">Proceed to database credentials</a>
                                    </div>
                                @else
                                    <div class="requirement-label">
                                        <img src="{{base_url(true)}}/g-assets/essentials/images/not-found.png" title="Found" height="20"/> Please make sure all the requirements are fulfilled
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</section>
@endsection