<ul>
    <li>
        <a href="{{ empUrl() }}account" {!! acActiveCan($menu, 'resumes') !!}>
        @if (setting('enable_multiple_resume') == 'yes')
        <i class="fa fa-file"></i> {{ __('message.my_resumes') }}
        @else
        <i class="fa fa-file"></i> {{ __('message.my_resume') }}
        @endif
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/job-applications" {!! acActiveCan($menu, 'applications') !!}>
        <i class="fa fa-check"></i> {{ __('message.job_applications') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/job-favorites" {!! acActiveCan($menu, 'favorites') !!}>
        <i class="fa fa-heart"></i> {{ __('message.favorite_jobs') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/job-referred" {!! acActiveCan($menu, 'referred') !!}>
        <i class="fa fa-user-plus"></i> {{ __('message.referred_jobs') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/quizes" {!! acActiveCan($menu, 'quizes') !!}>
        <i class="fa fa-list"></i> {{ __('message.quizes') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/profile" {!! acActiveCan($menu, 'profile') !!}>
        <i class="fa fa-user"></i> {{ __('message.profile') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}account/password" {!! acActiveCan($menu, 'password') !!}>
        <i class="fa fa-key"></i> {{ __('message.password') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}blogs">
        <i class="fas fa-blog"></i> {{ __('message.news_announcements') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}jobs">
        <i class="fa fa-briefcase"></i> {{ __('message.jobs') }}
        </a>
    </li>
    <li>
        <a href="{{ empUrl() }}logout">
        <i class="fas fa-sign-out-alt"></i> {{ __('message.logout') }}
        </a>
    </li>
</ul>