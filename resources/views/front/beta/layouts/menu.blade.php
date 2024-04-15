<!-- Menu Section Starts -->
<span class="screen-darken"></span>
<div id="navbar-mobile" class="container-fluid d-lg-none fixed-top navbar-mobile">
    <div class="row">
        <div class="col-md-12 mobile-menu">
            <img src="{{ setting('site_logo') }}" class="logo-main-menu" />
            <a data-trigger="navbar-main" class="mobile-menu-trigger"><i class="fa-solid fa-bars"></i></a>
        </div>
    </div>
</div>
<nav id="navbar-main" class="mobile-offcanvas navbar navbar-expand-lg navbar-main">
    <div class="container{{setting('display_main_menu_as_full_width') == 'yes' ? '-fluid' : ''}}">
        <div class="offcanvas-header">  
            <button class="btn-close mobile-menu-btn-close float-end"><i class="fa fa-close"></i></button>
        </div>

        <!-- me-auto : for left, ms-auto : for right, ml-auto : for middle -->
        <ul class="navbar-nav align-items-lg-center ml-auto">
            <li class="nav-item">
                <a class="" href="{{url('/')}}"><img src="{{ setting('site_logo') }}" class="logo-main-menu" /></a>
            </li>
        </ul>
        <ul class="navbar-nav me-auto navbar-left">            
            @php $items = mainFrontMenu(); @endphp
            @include('front'.viewPrfx().'partials.main-menu')           
        </ul>
        <ul class="navbar-nav ml-auto navbar-left">
            @php $items = mainFrontMenu('middle'); @endphp
            @include('front'.viewPrfx().'partials.main-menu')           
        </ul>
        <ul class="navbar-nav ms-auto navbar-right">
            @php $items = mainFrontMenu('right'); @endphp
            @include('front'.viewPrfx().'partials.main-menu')           
        </ul>
    </div>
</nav>
<!-- Menu Section Ends -->
<input type="hidden" id="current-color-theme" value="{{defaultColorTheme()}}">
<input type="hidden" id="default-color-theme" value="{{setting('default_front_color_theme')}}">
<input type="hidden" id="color-panel-allowed" value="{{setting('display_front_color_theme_selector_panel')}}">
<input type="hidden" id="site-full-url" value="{{url_with_port()}}">

@if(setting('display_front_color_theme_selector_panel') == 'yes')
<!-- Sidepanel Section Starts -->
<div class="section-sidepanel">
    <div class="section-sidepanel-handle"><i class="fa-solid fa-palette"></i></div>
    <div class="section-sidepanel-content">
        <p>{{__('message.select').' '.__('message.color')}}</p>
        <div class="section-sidepanel-content-item" data-ct="blue"></div>
        <div class="section-sidepanel-content-item" data-ct="green"></div>
        <div class="section-sidepanel-content-item" data-ct="orange"></div>
        <div class="section-sidepanel-content-item" data-ct="magenta"></div>
        <div class="section-sidepanel-content-item" data-ct="brown"></div>
        <div class="section-sidepanel-content-item" data-ct="maldives"></div>
    </div>
</div>
<!-- Sidepanel Section Ends -->
@endif
