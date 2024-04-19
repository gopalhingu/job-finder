<header class="main-header">
    <!-- Logo -->
    <a href="{{url('/')}}/company" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">
            <img src="{{settingEmp('site_favicon')}}" onerror="this.src='{{setting('site_favicon')}}'">
        </span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img src="{{settingEmp('site_logo')}}" onerror="this.src='{{setting('site_logo')}}'" alt="" class="img-fluid">
        </span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <i class="fas fa-align-justify"></i>
        </a>
        <a href="{{url('/')}}/company/memberships" class="sidebar-toggle prevent-sidebar-toggle">
            <i class="fas fa-id-card-alt"></i>&nbsp;
            @php $days = dateInDays(empMembership(companyId(), 'expiry')); @endphp
            @if ($days >= 7)
                <strong>{{__('message.membership_expiry')}}</strong>&nbsp;
                <span class="pull-right badge bg-green">
                    {{ date('d M, Y' , strtotime(empMembership(companyId(), 'expiry'))) }}
                    ({{ __('message.in').' '.$days.' '.__('message.days') }})
                </span>            
            @elseif ($days >= 1)
                <strong>{{__('message.membership_expiry')}}</strong>&nbsp;
                <span class="pull-right badge bg-red">
                    {{ date('d M, Y' , strtotime(empMembership(companyId(), 'expiry'))) }}
                    ({{ __('message.in').' '.$days.' '.__('message.days') }})
                </span>
            @elseif ($days <= 0)
                <span class="pull-right badge bg-red">
                    {{__('message.membership_expired')}}
                </span>
            @endif
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                @if(setting('enable_company_language_selector') == 'yes')
                <li class="">
                    {!! activeLanguages('company') !!}
                </li>
                @endif                
                <li class="dropdown user user-menu">
                    @php $thumb = companyThumb(companySession('image')); @endphp
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <img src="{{$thumb['image']}}" onerror='this.src="{{$thumb['error']}}"'
                    class="user-image">
                    <span class="hidden-xs">{{ companySession('first_name') }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{$thumb['image']}}" onerror='this.src="{{$thumb['error']}}"' class="img-circle">
                            <p>
                                {{ companySession('first_name').' '.companySession('last_name') }}
                                <small></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{url('/')}}/company/profile" class="btn btn-default btn-flat">{{ __('message.profile') }}</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{url('/')}}/company/logout" class="btn btn-default btn-flat">{{ __('message.logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>