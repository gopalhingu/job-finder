<!-- Menu Section Starts -->
<span class="screen-darken"></span>
<div id="navbar-mobile" class="container-fluid d-lg-none fixed-top navbar-mobile">
    <div class="row">
        <div class="col-md-12 mobile-menu">
            <img src="{{ settingEmpSlug('site_logo') }}" onerror="this.src='{{setting('site_logo')}}'" class="logo-main-menu" />
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
                <a class="" href="{{ rtrim(empUrl(), '/') }}">
                    <img src="{{ settingEmpSlug('site_logo') }}" onerror="this.src='{{setting('site_logo')}}'" class="logo-main-menu" />
                </a>
            </li>
        </ul>
        <ul class="navbar-nav me-auto navbar-left">            
        </ul>
        <ul class="navbar-nav ml-auto navbar-left">
        </ul>
        <ul class="navbar-nav ms-auto navbar-right">
            @if(setting('enable_candidate_language_selector') == 'yes')
                {!! activeLanguages() !!}
            @endif
            @if (candidateSession())
                @php $img = candidateOrEmployerThumb(); @endphp
                <li class="nav-item dropdown">
                    <a class="nav-link user-dropdown" href="#" data-bs-toggle="dropdown">
                        <img class="menu-avatar" src="{{$img['image']}}" onerror="this.src='{{$img['error']}}'" alt="Employer" />
                    </a>
                    <ul class="dropdown-menu shadow user-dropdown-list">
                        @if (candidateSession())
                        <li>
                            <a class="dropdown-item" href="{{ empUrl() }}account/profile">
                                <i class="fa-solid fa-user"></i> {{__('message.profile')}}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ empUrl() }}account/password">
                                <i class="fa-solid fa-key"></i> {{__('message.password')}}
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ empUrl() }}logout">
                                <i class="fa-solid fa-sign-out"></i> {{__('message.logout')}}
                            </a>
                        </li>
                        @else
                        <li><a class="dropdown-item" href="{{route('employer-dashboard')}}">{{__('message.dashboard')}}</a></li>
                        <li><a class="dropdown-item" href="{{route('employer-logout')}}">{{__('message.logout')}}</a></li>
                        @endif
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link btn header-btn-login main-navbar-btn navbar-main-btn global-login-btn" href="{{empUrl()}}account">
                        <i class="fa fa-sign-in"></i> {{__('message.login')}}
                    </a>
                </li>            
            @endif
            @if(setting('enable_candidate_dark_mode_button') == 'yes')
            <li class="nav-item">
                <div class="section-dark-mode-switch">
                    <label class="switch">
                        <input type="checkbox" {{defaultColorTheme() == 'night' ? 'checked="checked"' : 'false';}}>
                        <span class="section-dark-mode-switch-handle slider round" data-value="{{defaultColorTheme() == 'night' ? 'night' : 'light';}}"></span>
                    </label>
                    <div class="section-dark-mode-switch-labels"><i class="dark fa-solid fa-moon"></i></div>
                </div>        
            </li>            
            @endif
        </ul>
    </div>
</nav>
<!-- Menu Section Ends -->
<input type="hidden" id="current-color-theme" value="{{defaultColorTheme()}}">
<input type="hidden" id="default-color-theme" value="{{setting('default_front_color_theme')}}">
<input type="hidden" id="color-panel-allowed" value="{{setting('display_front_color_theme_selector_panel')}}">
<input type="hidden" id="site-full-url" value="{{env('APP_URL')}}">

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
