<!-- Candidate Section Sidebar Starts -->
<ul>
    <li>
        <a href="{{ empUrl() }}account" {!! acActiveCan($menu, 'resumes') !!}>
            <i class="fa-regular fa-file"></i> &nbsp; 
            @if (setting('enable_multiple_resume') == 'yes')
                {{__('message.my_resumes')}}
            @else
                {{__('message.my_resume')}}
            @endif
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/memberships" {!! acActiveCan($menu, 'memberships') !!}>
            <i class="fas fa-id-card-alt"></i> &nbsp; {{__('message.memberships')}}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/profile" {!! acActiveCan($menu, 'profile') !!}>
            <i class="fa-regular fa-user"></i> &nbsp; {{__('message.profile')}}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/password" {!! acActiveCan($menu, 'password') !!}>
            <i class="fa fa-key"></i> &nbsp; {{__('message.password')}}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/quizes" {!! acActiveCan($menu, 'quizes') !!}>
            <i class="fa fa-list"></i> &nbsp; {{__('message.quizes')}}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/job-applications" {!! acActiveCan($menu, 'applications') !!}>
            <i class="fa fa-check"></i> &nbsp; {{__('message.job_applications')}}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/job-referred" {!! acActiveCan($menu, 'referred') !!}>
            <i class="fa fa-user-plus"></i> &nbsp; {{ __('message.referred_jobs') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/job-favorites" {!! acActiveCan($menu, 'favorites') !!}>
            <i class="fa-regular fa-heart"></i> &nbsp; {{ __('message.favorite_jobs') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}logout" {!! acActiveCan($menu, '') !!}>
            <i class="fas fa-sign-out-alt"></i> &nbsp; {{__('message.logout')}}
        </a>
    </li>
</ul>
<!-- Candidate Section Sidebar Ends -->