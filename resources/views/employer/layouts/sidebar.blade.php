<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar -->
    <section class="sidebar">
        <!-- sidebar menu -->
        <ul class="sidebar-menu" data-widget="tree">
            @php $l = url('/').'/employer/'; @endphp
            <li {{ selMenu($menu, 'dashboard')}}>
            <a href="{{ $l }}dashboard"><i class="fas fa-tachometer-alt"></i> <span>{{ __('message.dashboard') }}</span></a>
            </li>
            @if(empAllowedTo('view_job_board'))
            <li {{ selMenu($menu, 'job_board') }}>
            <a href="{{ $l }}job-board"><i class="fas fa-newspaper"></i> <span>{{ __('message.job_board') }}</span></a>
            </li>
            @endif
            @if(empAllowedTo('all_candidate_interviews'))
            <li {{ selMenu($menu, 'candidate_interviews') }}>
            <a href="{{ $l }}candidate-interviews"><i class="fas fa-gavel"></i> <span>{{ __('message.interviews') }}</span></a>
            </li>
            @endif
            @if(empAllowedTo(array('view_companys', 'import_companys', 'create_companys')))
            <li class="header">{{ __('message.companys_management') }}</li>
            <li class="treeview {{ selMenu($menu, array('company', 'companys', 'import_companys')) }}">
                <a href="#">
                <i class="fa fa-suitcase"></i> <span>{{ __('message.companys') }}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    @if(empAllowedTo('create_companys'))
                    <li {{ selMenu($menu, 'company') }}>
                    <a href="{{ $l }}companys/create-or-edit"><i class="fas fa-cube"></i> {{ __('message.create') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('view_companys'))
                    <li {{ selMenu($menu, 'companys') }}>
                    <a href="{{ $l }}companys"><i class="fas fa-cube"></i> {{ __('message.listing') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('import_companys'))
                    <li {{ selMenu($menu, 'import_companys') }}>
                    <a href="{{ $l }}companys/import"><i class="fas fa-cube"></i> {{ __('message.import') }}</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if(empAllowedTo(array('view_jobs', 'view_all_jobs', 'create_jobs', 'view_departments', 'view_job_filters')))
            <li class="header">{{ __('message.jobs_management') }}</li>
            <li class="treeview {{ selMenu($menu, array('job', 'jobs', 'all_job', 'departments', 'job_filters')) }}">
                <a href="#">
                <i class="fa fa-suitcase"></i> <span>{{ __('message.jobs') }}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    @if(empAllowedTo('create_jobs'))
                    <li {{ selMenu($menu, 'job') }}>
                    <a href="{{ $l }}jobs/create-or-edit"><i class="fas fa-cube"></i> {{ __('message.create') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('view_jobs'))
                    <li {{ selMenu($menu, 'jobs') }}>
                    <a href="{{ $l }}jobs"><i class="fas fa-cube"></i> {{ __('message.listing') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('view_all_jobs'))
                    <li {{ selMenu($menu, 'all_job') }}>
                    <a href="{{ $l }}all-jobs"><i class="fas fa-cube"></i> {{ __('message.all_listing') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('view_departments') && setting('departments_creation') != 'only_admin')
                    <li {{ selMenu($menu, 'departments') }}>
                    <a href="{{ $l }}departments"><i class="fas fa-cube"></i> {{ __('message.departments') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('view_job_filters') && setting('job_filters_creation') != 'only_admin')
                    <li {{ selMenu($menu, 'job_filters') }}>
                    <a href="{{ $l }}job-filters"><i class="fas fa-cube"></i> {{ __('message.job_filters') }}</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @if(empAllowedTo(array('view_quizes', 'view_interviews', 'view_traites')))
            <li class="header">{{ __('message.scaling_tools_management') }}</li>
            @if(empAllowedTo('view_quizes'))
            <li {{ selMenu($menu, 'quizes') }}>
            <a href="{{ $l }}quiz-designer"><i class="far fa-list-alt"></i> <span>{{ __('message.quiz_designer') }}</span></a>
            </li>
            @endif
            @if(empAllowedTo('view_interviews'))
            <li {{ selMenu($menu, 'interviews') }}>
            <a href="{{ $l }}interview-designer"><i class="fas fa-clipboard-list"></i> <span>{{ __('message.interview_designer') }}</span>
            </a>
            </li>
            @endif
            @if(empAllowedTo('view_traites'))
            <li {{ selMenu($menu, 'traites') }}>
            <a href="{{ $l }}traites"><i class="fas fa-star-half-alt"></i> <span>{{ __('message.traites') }}</span></a>
            </li>
            @endif
            @if(empAllowedTo(array('view_question_categories', 'view_quiz_categories', 'view_interview_categories')))
            <li class="treeview {{ selMenu($menu, array('question_categories', 'quiz_categories', 'interview_categories')) }}">
                <a href="#">
                <i class="fa fa-list"></i> <span>{{ __('message.categories') }}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    @if(empAllowedTo('view_question_categories'))
                    <li {{ selMenu($menu, 'question_categories') }}>
                    <a href="{{ $l }}question-categories"><i class="fas fa-cube"></i> {{ __('message.question_categories') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('view_quiz_categories'))
                    <li {{ selMenu($menu, 'quiz_categories') }}>
                    <a href="{{ $l }}quiz-categories"><i class="fas fa-cube"></i> {{ __('message.quiz_categories') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('view_interview_categories'))
                    <li {{ selMenu($menu, 'interview_categories') }}>
                    <a href="{{ $l }}interview-categories"><i class="fas fa-cube"></i> {{ __('message.interview_categories') }}</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            @endif
            @if(empAllowedTo(array('view_team_listing', 'view_candidate_listing')))
            <li class="header">{{ __('message.users_management') }}</li>
            @endif
            @if(empAllowedTo('view_team_listing'))
            <li {{ selMenu($menu, 'team') }}>
            <a href="{{ $l }}team"><i class="fa fa-users"></i> <span>{{ __('message.team') }}</span></a>
            </li>
            @endif
            @if(empAllowedTo('view_candidate_listing'))
            <li {{ selMenu($menu, 'candidates') }}>
            <a href="{{ $l }}candidates"><i class="fa fa-graduation-cap"></i> <span>{{ __('message.candidates') }}</span></a>
            </li>
            @endif
            <li class="header">{{ __('message.others') }}</li>
            @if(empAllowedTo(array('view_blog_listing', 'view_blog_categories')))
            @if(empAllowedTo('view_memberships'))
            <li {{ selMenu($menu, 'memberships') }}>
            <a href="{{ $l }}memberships"><i class="fas fa-id-card-alt"></i> <span>{{ __('message.memberships') }}</span></a>
            </li>
            @endif            
            <li class="treeview {{ selMenu($menu, array('blogs', 'blog_categories')) }}">
                <a href="#">
                <i class="fas fa-blog"></i> <span>{{ __('message.blogs') }}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    @if(empAllowedTo('view_blog_listing'))
                    <li {{ selMenu($menu, 'blogs') }}>
                    <a href="{{ $l }}blogs"><i class="fas fa-cube"></i> {{ __('message.listing') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('view_blog_categories'))
                    <li {{ selMenu($menu, 'blog_categories') }}>
                    <a href="{{ $l }}blog-categories"><i class="fas fa-cube"></i> {{ __('message.categories') }}</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif
            <li class="treeview {{ selMenu($menu, array('general', 'branding', 'emails', 'css', 'profile', 'password',)) }}">
                <a href="#">
                <i class="fa fa-cogs"></i> <span>{{ __('message.settings') }}</span>
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
                </a>
                <ul class="treeview-menu">
                    @if(empAllowedTo('general'))
                    <li {{ selMenu($menu, 'general') }}>
                    <a href="{{ $l }}settings/general"><i class="fas fa-cube"></i> {{ __('message.general') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('branding'))
                    <li {{ selMenu($menu, 'branding') }}>
                    <a href="{{ $l }}settings/branding"><i class="fas fa-cube"></i> {{ __('message.branding') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('emails'))
                    <li {{ selMenu($menu, 'emails') }}>
                    <a href="{{ $l }}settings/emails"><i class="fas fa-cube"></i> {{ __('message.emails') }}</a>
                    </li>
                    @endif
                    @if(empAllowedTo('css'))
                    <li {{ selMenu($menu, 'css') }}>
                    <a href="{{ $l }}settings/css"><i class="fas fa-cube"></i> {{ __('message.css') }}</a>
                    </li>
                    @endif
                    <li {{ selMenu($menu, 'profile') }}>
                    <a href="{{ $l }}profile"><i class="fas fa-cube"></i> {{ __('message.profile') }}</a>
                    </li>
                    <li {{ selMenu($menu, 'password') }}>
                    <a href="{{ $l }}password"><i class="fas fa-cube"></i> {{ __('message.password') }}</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ rtrim(empUrlBySlug(employerId('slug')), '/') }}" target="_blank">
                <i class="fas fa-external-link-alt"></i> <span>{{__('message.candidate_area')}}</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>