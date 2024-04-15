@if (setting('home_features_section') == 'enabled')
@php $error = url('/g-assets').'/essentials/images/general-not-found.png'; @endphp
<!-- Home Features Section Starts -->
<div class="section-features-heading">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12">
                <div class="section-heading-style-alpha">
                    <div class="section-heading">
                        <h2>{{__('message.features')}}</h2>
                    </div>
                    <div class="section-intro-text">
                        <p>{{__('message.main_features_msg')}}</p>
                    </div>                    
                </div>                
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-1">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <h1>{{__('message.quizes')}}</h1>
                        <p>{{__('message.quiz_feature_msg')}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <img src="{{route('uploads-view', setting('general_feature_quizes'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-2">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <img src="{{route('uploads-view', setting('general_feature_interviews'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <h1>{{__('message.interviews')}}</h1>
                        <p>{{__('message.interview_feature_msg')}}</p>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-3">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <h1>{{__('message.self_assesment')}}</h1>
                        <p>{{__('message.assesment_feature_msg')}}</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <img src="{{route('uploads-view', setting('general_feature_selfassesment'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-4">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <img src="{{route('uploads-view', setting('general_feature_jobboard'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <h1>{{__('message.job_board')}}</h1>
                        <p>{{__('message.job_board_feature_msg')}}</p>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-5">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <h1>{{__('message.resume_builder')}}</h1>
                        <p>{{__('message.resume_feature_msg')}}</p>                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <img src="{{route('uploads-view', setting('general_feature_resume'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-6">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <img src="{{route('uploads-view', setting('general_feature_filters'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <h1>{{__('message.job_filters')}}</h1>
                        <p>{{__('message.filter_feature_msg')}}</p>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-7">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <h1>{{__('message.job_referrals')}}</h1>
                        <p>{{__('message.referral_feature_msg')}}</p>                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <img src="{{route('uploads-view', setting('general_feature_referjob'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-8">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <img src="{{route('uploads-view', setting('general_feature_personalized'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <h1>{{__('message.personalized_settings')}}</h1>
                        <p>{{__('message.setting_feature_msg')}}</p>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-9">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <h1>{{__('message.role_permissions')}}</h1>
                        <p>{{__('message.roles_feature_msg')}}</p>                        
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <img src="{{route('uploads-view', setting('general_feature_rolepermissions'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="section-features">
    <div class="container">
        <div class="section-features-element-10">
            <div class="row align-items-center h-100">
                <div class="col-md-6">
                    <div class="section-features-element-left slide-from-left">
                        <img src="{{route('uploads-view', setting('general_feature_reports'))}}"  onerror="this.src='{{$error}}'" class="container_img"/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-features-element-right slide-from-right">
                        <h1>{{__('message.reports')}}</h1>
                        <p>{{__('message.reports_feature_msg')}}</p>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Home Features Section Ends -->
@endif