<ul>
    <li>
        <a href="{{route('front-acc-resume-listing')}}" {!! acActiveCan($menu, 'resumes') !!}>
            <i class="fa-regular fa-file"></i> &nbsp; {{__('message.my_resume')}}
        </a>
    </li>
    @if(setting('enable_candidate_packages') == 'yes')
    <li>
        <a href="{{ route('front-acc-memberships-view') }}" {!! acActiveCan($menu, 'memberships') !!}>
            <i class="fas fa-id-card-alt"></i> &nbsp; {{__('message.memberships')}}
        </a>
    </li>    
    <li>
        <a href="{{route('front-settings')}}" {!! acActiveCan($menu, 'settings') !!}>
            <i class="fas fa-cog"></i> &nbsp; {{__('message.settings')}}
        </a>
    </li>
    @endif
    <li>
        <a href="{{route('front-profile')}}" {!! acActiveCan($menu, 'profile') !!}>
            <i class="fa-regular fa-user"></i> &nbsp; {{__('message.profile')}}
        </a>
    </li>
    <li>
        <a href="{{route('front-password')}}" {!! acActiveCan($menu, 'password') !!}>
            <i class="fa fa-key"></i> &nbsp; {{__('message.password')}}
        </a>
    </li>
    <li>
        <a href="{{route('front-acc-quizes')}}" {!! acActiveCan($menu, 'quizes') !!}>
            <i class="fa fa-list"></i> &nbsp; {{__('message.quizes')}}
        </a>
    </li>
    <li>
        <a href="{{route('front-acc-job-apps')}}" {!! acActiveCan($menu, 'applications') !!}>
            <i class="fa fa-check"></i> &nbsp; {{__('message.job_applications')}}
        </a>
    </li>
    <li>
        <a href="{{route('front-acc-job-referred')}}" {!! acActiveCan($menu, 'referred') !!}>
            <i class="fa fa-user-plus"></i> &nbsp; {{ __('message.referred_jobs') }}
        </a>
    </li>
    <li>
        <a href="{{route('front-acc-job-favs')}}" {!! acActiveCan($menu, 'favorites') !!}>
            <i class="fa-regular fa-heart"></i> &nbsp; {{ __('message.favorite_jobs') }}
        </a>
    </li>
    <li>
        <a href="{{route('front-logout')}}" {!! acActiveCan($menu, '') !!}>
            <i class="fas fa-sign-out-alt"></i> &nbsp; {{__('message.logout')}}
        </a>
    </li>
</ul>
