@extends('front'.viewPrfx().'layouts.master')

@section('content')
    
    <input type="hidden" id="home-page" value="1" />
    @if(setting('home_banner') == 'yes')	
    <!-- Home Banner Section Starts -->
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    {!!removeUselessLineBreaks(setting('home_banner_text'))!!}
                </div>
            </div>
        </div>
    </div>
    <!-- Home Banner Section Ends -->
    @endif

    @if(setting('enable_feature_section') == 'yes')
    <!-- Home Features Section Starts -->
    <div id="features"><br /></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center p-5">
                <div class="section-headline text-center">
                    <h2>{{__('message.features')}}</h2>
                </div>
            </div>
            @if(setting('quiz_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-clipboard-list"></i></a>
                    <h4>{{__('message.quizes')}}</h4>
                    <p>
                        {{__('message.quiz_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('interview_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-diagnoses"></i></a>
                    <h4>{{__('message.interviews')}}</h4>
                    <p>
                        {{__('message.interview_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('assesment_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-star-half-alt"></i></a>
                    <h4>{{__('message.self_assesment')}}</h4>
                    <p>
                        {{__('message.assesment_feature_msg')}}s
                    </p>
                </div>
            </div>
            @endif
            @if(setting('job_board_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-balance-scale"></i></a>
                    <h4>{{__('message.job_board')}}</h4>
                    <p>
                        {{__('message.job_board_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('resume_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-book-reader"></i></a>
                    <h4>{{__('message.resume_builder')}}</h4>
                    <p>
                        {{__('message.resume_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('filter_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-filter"></i></a>
                    <h4>{{__('message.job_filters')}}</h4>
                    <p>
                        {{__('message.filter_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('referral_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-retweet"></i></a>
                    <h4>{{__('message.job_referrals')}}</h4>
                    <p>
                        {{__('message.referral_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('oauth_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-at"></i></a>
                    <h4>{{__('message.third_party_oauth')}}</h4>
                    <p>
                        {{__('message.oauth_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('translation_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-language"></i></a>
                    <h4>{{__('message.translation')}}</h4>
                    <p>
                        {{__('message.translation_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('setting_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-cogs"></i></a>
                    <h4>{{__('message.personalized_settings')}}</h4>
                    <p>
                        {{__('message.setting_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('roles_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="fas fa-user-cog"></i></a>
                    <h4>{{__('message.role_permissions')}}</h4>
                    <p>
                        {{__('message.roles_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
            @if(setting('reports_feature') == 'yes')
            <div class="col-md-4 text-center">
                <div class="single-features">
                    <a class="features-icon" href="#"><i class="far fa-chart-bar"></i></a>
                    <h4>{{__('message.excel_reports')}}</h4>
                    <p>
                        {{__('message.reports_feature_msg')}}
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- Home Features Section Ends -->
    @endif

    @if(setting('home_pricing') == 'yes' && $packages)
    <!-- Home Pricing Section Starts -->
    <div id="pricing"><br /></div>
    <div class="pricing-section">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center p-5">
                                <div class="section-headline text-center">
                                    <h2>{{__('message.pricing')}}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            @foreach($packages as $package)
                            <div class="col-md-4 col-sm-4 col-xs-12">
                                <div class="pricing_table_list @if($package['is_top_sale'] == 1) active @endif">
                                    @if($package['is_top_sale'] == 1)<span class="pricing-topsale">{{__('message.top_sale')}}</span>@endif
                                    <h3>
                                        {{$package['title']}} <br/> 
                                        <span>{{$package['currency']}}{{$package['monthly_price']}} / {{__('message.month')}}</span> 
                                        | 
                                        <span><span>{{$package['currency']}}{{$package['yearly_price']}} / {{__('message.year')}}</span> </span>
                                    </h3>
                                    <ol class="scrollbar" id="style-3">
                                        <li>
                                            <i class="{{packageItemBullet($package['active_jobs'])}} fas fa-check-circle"></i> {{packageItem(__('message.active_jobs'), $package['active_jobs'])}} 
                                        </li>
                                        <li>
                                            <i class="{{packageItemBullet($package['active_users'])}} fas fa-check-circle"></i> {{packageItem(__('message.active_users'), $package['active_users'])}} 
                                        </li>
                                        <li>
                                            <i class="{{packageItemBullet($package['active_custom_filters'])}} fas fa-check-circle"></i> {{packageItem(__('message.active_custom_filters'), $package['active_custom_filters'])}} 
                                        </li>
                                        <li>
                                            <i class="{{packageItemBullet($package['active_quizes'])}} fas fa-check-circle"></i> {{packageItem(__('message.active_quizes'), $package['active_quizes'])}} 
                                        </li>
                                        <li>
                                            <i class="{{packageItemBullet($package['active_interviews'])}} fas fa-check-circle"></i> {{packageItem(__('message.active_interviews'), $package['active_interviews'])}} 
                                        </li>
                                        <li>
                                            <i class="{{packageItemBullet($package['active_traites'])}} fas fa-check-circle"></i> {{packageItem(__('message.active_traites'), $package['active_traites'])}} 
                                        </li>
                                        @if(setting('enable_separate_employer_site') == 'only_for_employers_with_separate_site')
                                        <li>
                                            <i class="{{packageItemBullet($package['separate_site'], true)}} fas fa-check-circle"></i> {{packageItem(__('message.separate_site'), $package['separate_site'], true)}} 
                                        </li>
                                        @endif
                                        <li>
                                            <i class="{{packageItemBullet($package['branding'], true)}} fas fa-check-circle"></i> {{packageItem(__('message.branding'), $package['branding'], true)}} 
                                        </li>
                                        <li>
                                            <i class="{{packageItemBullet($package['role_permissions'], true)}} fas fa-check-circle"></i> {{packageItem(__('message.role_permissions'), $package['role_permissions'], true)}} 
                                        </li>
                                        <li>
                                            <i class="{{packageItemBullet($package['custom_emails'], true)}} fas fa-check-circle"></i> {{packageItem(__('message.custom_emails'), $package['custom_emails'], true)}} 
                                        </li>
                                    </ol>
                                    <button class="front-register-btn">Sign Up Now</button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Home Pricing Section Ends -->
    @endif

    @if(setting('home_news') == 'yes' && $news)
    <!-- Home News Section Starts -->
    <div id="news"><br /></div>
    <div class="news-section">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center p-5">
                                <div class="section-headline text-center">
                                    <a href="{{url('/news')}}"><h2>{{__('message.latest_news')}}</h2></a>
                                </div>
                            </div>
                            @foreach($news as $new)
                            <div class="col-md-4">
                                <div class="news-single">
                                    <h4><a href="{{route('front-news-detail', $new['slug'])}}">{{$new['title']}}</a></h4>
                                    <div class="news-date">
                                        <i class="far fa-calendar"></i> {{date('d M, Y', strtotime($new['created_at']))}}<br />
                                    </div>
                                    <p>
                                        {{$new['summary']}}
                                    </p>
                                    <a href="{{route('front-news-detail', $new['slug'])}}" class="btn news-btn">{{__('message.read_more')}}</a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Home News Section Ends -->
    @endif

    @if(setting('home_portfolio') == 'yes')
    <!-- Home News Section Starts -->
    <div id="portfolio"><br /></div>
    <div class="portfolio">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 text-center p-5">
                                <div class="section-headline text-center">
                                    <h2>{{__('message.portfolio_clients')}}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="row aos-init aos-animate" data-aos="fade-up">
                            <div class="col-lg-3 col-md-6 mt-5 mt-md-0">
                                <div class="portfolio-box">
                                    <i class="fa fa-suitcase"></i>
                                    <span data-toggle="counter-up">{{$jobs_count}}</span>
                                    <p><strong>{{__('message.jobs')}}</strong></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="portfolio-box">
                                    <i class="fas fa-user-tie"></i>
                                    <span data-toggle="counter-up">{{$employers_count}}</span>
                                    <p><strong>{{__('message.employers')}}</strong></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
                                <div class="portfolio-box">
                                    <i class="fas fa-user-graduate"></i>
                                    <span data-toggle="counter-up">{{$candidates_count}}</span>
                                    <p><strong>{{__('message.candidates')}}</strong></p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 mt-5 mt-lg-0">
                                <div class="portfolio-box">
                                    <i class="fa fa-check"></i>
                                    <span data-toggle="counter-up">{{$hired_count}}</span>
                                    <p><strong>{{__('message.hired')}}</strong></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Home News Section Ends -->

    <!-- Home employers Section Starts -->
    <div class="employers">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="owl-carousel owl-theme employers-carousel">
                        @foreach($companies as $emp)
                        @php $thumb = employerThumb($emp['logo'], true); @endphp
                        <img src="{{ $thumb['image'] }}" onerror="this.src='{{$thumb['error']}}'" alt="{{$emp['company']}}" />
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Home employers Section Ends -->
    @endif

    @if(setting('home_testimonial') == 'yes')
    <!-- Home testimonials Section Starts -->
    <div class="testimonials" id="testimonials">
        <div class="testimonial-inner area-padding">
            <div class="testimonial-overly"></div>
            <div class="container ">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="testimonial-content text-center">
                            <a class="testimonial-quote" href="#"><i class="fa fa-quote-right"></i></a>
                            <div class="owl-carousel owl-theme testimonial-carousel">
                                @foreach($testimonials as $test)
                                <div class="single-testi">
                                    <div class="testimonial-text">
                                        <p>{{$test['description']}}</p>
                                        <h6>{{$test['company']}}</h6>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <!-- End Right Feature -->
                </div>
            </div>
        </div>
    </div>
    <!-- Home testimonials Section Ends -->
    @endif


    @if(setting('home_contact') == 'yes')
    <!-- Start contact Area -->
    <div id="contact"><br /><br /></div>
    <div class="contact">
        <div class="contact-inner area-padding">
            <div class="contact-overly"></div>
            <div class="container ">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="section-headline text-center">
                            <h2>{{__('message.contact_us')}}</h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if(setting('contact_phone') || setting('contact_email') || setting('contact_address'))
                    
                    @php 
                    $column = 8 
                    @endphp

                    @if(setting('home_contact_form') == 'yes')
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="row">
                            @if(setting('contact_phone'))
                            <!-- Start contact icon column -->
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="contact-icon text-center">
                                    <div class="contact-single-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                        <p>{{__('message.phone')}}: {{setting('contact_phone')}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(setting('contact_email'))
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="contact-icon text-center">
                                    <div class="contact-single-icon">
                                        <i class="fas fa-envelope-square"></i>
                                        <p>{{__('message.email')}}: {{setting('contact_email')}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(setting('contact_address'))
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="contact-icon text-center">
                                    <div class="contact-single-icon">
                                        <i class="fas fa-search-location"></i>
                                        <p>{{__('message.location')}}: {{setting('contact_address')}}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <!-- Start contact icon column -->
                        @if(setting('contact_phone'))
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="contact-icon text-center">
                                <div class="contact-single-icon">
                                    <i class="fas fa-mobile-alt"></i>
                                    <p>{{__('message.phone')}}: {{setting('contact_phone')}}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(setting('contact_email'))
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="contact-icon text-center">
                                <div class="contact-single-icon">
                                    <i class="fas fa-envelope-square"></i>
                                    <p>{{__('message.email')}}: {{setting('contact_email')}}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(setting('contact_address'))
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="contact-icon text-center">
                                <div class="contact-single-icon">
                                    <i class="fas fa-search-location"></i>
                                    <p>{{__('message.location')}}: {{setting('contact_address')}}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif
                    
                    @else
                    @php 
                    $column = 12 
                    @endphp                    
                    @endif

                    @if(setting('home_contact_form') == 'yes')
                    <!-- Start  contact -->
                    <div class="col-md-{{$column}} col-sm-{{$column}} col-xs-12">
                        <div class="form contact-form">
                            <div id="errormessage"></div>
                            <form action="" method="post" role="form" class="contactForm" id="home_contact_form">
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" placeholder="{{__('message.name')}}" />
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="{{__('message.email')}}" />
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" name="subject" placeholder="{{__('message.subject')}}" />
                                </div>
                                <div class="form-group">
                                    <textarea class="form-control" name="message" rows="5" placeholder="{{__('message.message')}}"></textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" id="home_contact_form_button">{{__('message.send')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Left contact -->
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact Area -->
    @endif

@endsection
