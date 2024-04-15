<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{url('/')}}/admin" class="brand-link">
    <img 
        src="{{setting('site_favicon')}}" 
        alt="{{setting('site_name')}}" 
        class="brand-image brand-favicon img-circle elevation-3" 
    />
    &nbsp;&nbsp;&nbsp;
    <span class="brand-text font-weight-light">
        <img src="{{setting('site_logo')}}" height="25" />
    </span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if(allowedTo('view_dashboard_stats', 'view_sales_chart', 'view_signups_chart'))
                <li class="nav-item">
                    <a href="{{route('admin-dashboard')}}" class="nav-link {{acActive($menu, 'dashboard')}}">
                        <i class="fas fa-tachometer-alt"></i>
                        <p>&nbsp;{{__('message.dashboard')}}</p>
                    </a>
                </li>
                @endif
                @if(allowedTo('view_user_listing'))
                <li class="nav-item">
                    <a href="{{route('admin-users')}}" class="nav-link {{acActive($menu, 'users')}}">
                        <i class="fas fa-user"></i>
                        <p>&nbsp;{{__('message.users')}}</p>
                    </a>
                </li>
                @endif
                @if(allowedTo('view_employer_listing'))
                <li class="nav-item">
                    <a href="{{route('admin-employers')}}" class="nav-link {{acActive($menu, 'employers')}}">
                        <i class="fas fa-user-tie"></i>
                        <p>&nbsp;{{__('message.employers')}}</p>
                    </a>
                </li>
                @endif
                @if(allowedTo('view_candidate_listing'))
                <li class="nav-item">
                    <a href="{{route('admin-candidates')}}" class="nav-link {{acActive($menu, 'candidates')}}">
                        <i class="fas fa-user-graduate"></i>
                        <p>&nbsp;{{__('message.candidates')}}</p>
                    </a>
                </li>
                @endif
                @if(allowedTo(array('view_packages', 'view_memberships', 'view_candidate_packages', 'view_candidates_memberships')))
                <li class="nav-item {{treeActive($menu, array('packages', 'memberships', 'candidate-packages', 'candidate-memberships'))}}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-hand-holding-usd"></i>
                        <p>{{__('message.finances')}}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(allowedTo('view_packages'))
                        <li class="nav-item">
                            <a href="{{route('admin-packages')}}" class="nav-link {{acActive($menu, 'packages')}}">
                                <i class="fas fa-cube"></i>
                                <p>&nbsp;{{__('message.employer').' '.__('message.packages')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_memberships'))
                        <li class="nav-item">
                            <a href="{{route('admin-memberships')}}" class="nav-link {{acActive($menu, 'memberships')}}">
                                <i class="fas fa-cube"></i>
                                <p>&nbsp;{{__('message.employer').' '.__('message.memberships')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_candidate_packages'))
                        <li class="nav-item">
                            <a href="{{route('admin-candidate-packages')}}" class="nav-link {{acActive($menu, 'candidate-packages')}}">
                                <i class="fas fa-cube"></i>
                                <p>&nbsp;{{__('message.candidate').' '.__('message.packages')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_candidates_memberships'))
                        <li class="nav-item">
                            <a href="{{route('admin-candidate-memberships')}}" class="nav-link {{acActive($menu, 'candidate-memberships')}}">
                                <i class="fas fa-cube"></i>
                                <p>&nbsp;{{__('message.candidate').' '.__('message.memberships')}}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(allowedTo(array('view_jobs')))
                <li class="nav-item {{treeActive($menu, array('jobs'))}}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-sitemap"></i>
                        <p>{{__('message.contents')}}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(allowedTo('view_jobs'))
                        <li class="nav-item">
                            <a href="{{route('admin-jobs')}}" class="nav-link {{acActive($menu, 'jobs')}}">
                                &nbsp;<i class="fas fa-briefcase"></i>
                                <p>{{__('message.jobs')}}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(allowedTo(array('portal_vs_multitenancy', 'admin_view_departments', 'admin_view_job_filters')))
                <li class="nav-item {{treeActive($menu, array('departments', 'job_filters', 'setting-jpvssaas'))}}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cube"></i>
                        <p>{{__('message.portal_vs_multitenancy')}}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(allowedTo('portal_vs_multitenancy'))
                        <li class="nav-item">
                            <a href="{{route('admin-settings-jpvssaas')}}" class="nav-link {{acActive($menu, 'setting-jpvssaas')}}">
                                &nbsp;<i class="fas fa-cube"></i>
                                <p>{{__('message.settings')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('admin_view_departments'))
                        <li class="nav-item">
                            <a href="{{route('admin-depa')}}" class="nav-link {{acActive($menu, 'departments')}}">
                                &nbsp;<i class="fas fa-cube"></i>
                                <p>{{__('message.departments')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('admin_view_job_filters'))
                        <li class="nav-item">
                            <a href="{{route('admin-job-fil')}}" class="nav-link {{acActive($menu, 'job_filters')}}">
                                &nbsp;<i class="fas fa-cube"></i>
                                <p>{{__('message.job_filters')}}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(allowedTo(array('home_page_settings', 'view_pages', 'view_faqs_listing', 'view_faqs_categories', 'view_news_listing', 'view_news_categories', 'menu_settings', 'view_testimonials', 'view_messages_listing')))
                <li class="nav-item {{treeActive($menu, array('cms', 'setting-home', 'pages', 'news', 'news-categories', 'faqs', 'faqs-categories', 'menus', 'testimonials', 'messages'))}}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-project-diagram"></i>
                        <p>{{__('message.cms')}}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(allowedTo('home_page_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-settings-home')}}" class="nav-link {{acActive($menu, 'setting-home')}}">
                                &nbsp;<i class="fas fa-home"></i>
                                <p>{{__('message.home')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_pages'))
                        <li class="nav-item">
                            <a href="{{route('admin-pages')}}" class="nav-link {{acActive($menu, 'pages')}}">
                                &nbsp;<i class="fas fa-pager"></i>
                                <p>{{__('message.pages')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_news_listing'))
                        <li class="nav-item">
                            <a href="{{route('admin-news')}}" class="nav-link {{acActive($menu, 'news')}}">
                                &nbsp;<i class="fas fa-newspaper"></i>
                                <p>{{__('message.news')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_news_categories'))
                        <li class="nav-item">
                            <a href="{{route('admin-news-categories')}}" class="nav-link {{acActive($menu, 'news-categories')}}">
                                &nbsp;<i class="fas fa-newspaper"></i>
                                <p>{{__('message.news_categories')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_faqs_listing'))
                        <li class="nav-item">
                            <a href="{{route('admin-faqs')}}" class="nav-link {{acActive($menu, 'faqs')}}">
                                &nbsp;<i class="fas fa-question"></i>
                                <p>{{__('message.faqs')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_faqs_categories'))
                        <li class="nav-item">
                            <a href="{{route('admin-faqs-categories')}}" class="nav-link {{acActive($menu, 'faqs-categories')}}">
                                &nbsp;<i class="fas fa-question"></i>
                                <p>{{__('message.faqs_categories')}}</p>
                            </a>
                        </li>
                        @endif                        
                        @if(allowedTo('menu_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-menus-list')}}" class="nav-link {{acActive($menu, 'menus')}}">
                                &nbsp;<i class="fas fa-ellipsis-h"></i>
                                <p>{{__('message.menu')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_testimonials'))
                        <li class="nav-item">
                            <a href="{{route('admin-testimonials')}}" class="nav-link {{acActive($menu, 'testimonials')}}">
                                &nbsp;<i class="fas fa-comment-alt"></i>
                                <p>{{__('message.testimonials')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('view_messages_listing'))
                        <li class="nav-item">
                            <a href="{{route('admin-messages')}}" class="nav-link {{acActive($menu, 'messages')}}">
                                &nbsp;<i class="fas fa-envelope-square"></i>
                                <p>{{__('message.messages')}}</p>
                            </a>
                        </li>
                        @endif
                    </ul>
                </li>
                @endif
                @if(allowedTo(array('general_settings', 'general_images_settings', 'display_settings', 'email_settings', 'email_template_settings', 'apis_settings',   
                    'languages_settings', 'roles_settings', 'refresh_memberships', 'employer_override_settings')))                
                <li class="nav-item {{treeActive($menu, array('profile', 'password', 'setting-email', 'setting-email-templates', 'roles', 'setting-apis', 'setting-general', 
                    'setting-general-images', 'setting-display', 'setting-employers', 'languages'))}}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cogs"></i>
                        <p>{{__('message.settings')}}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(allowedTo('general_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-settings-general')}}" class="nav-link {{acActive($menu, 'setting-general')}}">
                                &nbsp;<i class="fas fa-cog"></i>
                                <p>{{__('message.general')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('general_images_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-settings-general-images')}}" class="nav-link {{acActive($menu, 'setting-general-images')}}">
                                &nbsp;<i class="fas fa-image"></i>
                                <p>{{__('message.general_images')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('display_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-settings-display')}}" class="nav-link {{acActive($menu, 'setting-display')}}">
                                &nbsp;<i class="fas fa-desktop"></i>
                                <p>{{__('message.display')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('email_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-settings-email')}}" class="nav-link {{acActive($menu, 'setting-email')}}">
                                &nbsp;<i class="fas fa-envelope"></i>
                                <p>{{__('message.email')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('email_template_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-settings-templates')}}" class="nav-link {{acActive($menu, 'setting-email-templates')}}">
                                &nbsp;<i class="fas fa-envelope"></i>
                                <p>{{__('message.email_templates')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('apis_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-settings-apis')}}" class="nav-link {{acActive($menu, 'setting-apis')}}">
                                &nbsp;<i class="fas fa-bezier-curve"></i>
                                <p>{{__('message.apis')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('languages_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-languages')}}" class="nav-link {{acActive($menu, 'languages')}}">
                                &nbsp;<i class="fas fa-language"></i>
                                <p>{{__('message.languages')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('roles_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-roles-list')}}" class="nav-link {{acActive($menu, 'roles')}}">
                                &nbsp;<i class="fas fa-users-cog"></i>
                                <p>{{__('message.roles')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('refresh_memberships'))
                        <li class="nav-item">
                            <a target="_blank" href="{{route('refresh-memberships')}}" class="nav-link"
                                title="{{__('message.refresh_memberships_msg')}}">
                                &nbsp;<i class="fas fa-redo-alt"></i>
                                <p>{{__('message.refresh_memberships')}}</p>
                            </a>
                        </li>
                        @endif
                        @if(allowedTo('employer_override_settings'))
                        <li class="nav-item">
                            <a href="{{route('admin-settings-employer')}}" class="nav-link {{acActive($menu, 'setting-employers')}}"
                                title="{{__('message.refresh_memberships_msg')}}">
                                &nbsp;<i class="fas fa-cog"></i>
                                <p>{{__('message.employer_settings')}}</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{route('admin-profile')}}" class="nav-link {{acActive($menu, 'profile')}}">
                                &nbsp;<i class="fas fa-user"></i>
                                <p>{{__('message.profile')}}</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin-password')}}" class="nav-link {{acActive($menu, 'password')}}">
                                &nbsp;<span class="fas fa-lock"></span>
                                <p>{{__('message.password')}}</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{route('admin-logout')}}" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i>
                        <p>&nbsp;{{__('message.logout')}}</p>
                    </a>
                </li>                        
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
