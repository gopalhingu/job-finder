function Main() {

    "use strict";

    var self = this;
    var job_filters = {};
    self.variables = [];

    this.initLanguageDropdown = function () {
        if ($(".flag-dropdown").length) {
            $(document).on("click", ".flag-menu .flag-item", function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                application.load('/set-front-language/'+id, '', function (result) {});
                setTimeout(function() { 
                    window.location.reload();
                }, 500);
            });
        }
    }

    this.animateAgainOnScroll = function (container, element, classToBeInserted) {
        if ($(container).length > 0) {
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    const square = entry.target.querySelector('.'+element);
                    if (entry.isIntersecting) {
                        square.classList.add(classToBeInserted);
                        return;
                    }
                    square.classList.remove(classToBeInserted);
                });
            });
            observer.observe(document.querySelector(container));        
        }
    }

    this.initPricingSwitch = function () {
        $('.section-pricing-switch-handle').on('click', function() {
            var selected = $(this).data('value');
            if (selected == 'monthly') {
                $(this).data('value', 'yearly');
                $('.section-pricing-item-price-amount-monthly').hide();
                $('.section-pricing-item-price-amount-yearly').addClass('slide-down');
                $('.section-pricing-item-price-amount-yearly').show();
                $('.section-pricing-item-price-duration').html('/yr');
            } else {
                $(this).data('value', 'monthly');
                $('.section-pricing-item-price-amount-monthly').addClass('slide-down');
                $('.section-pricing-item-price-amount-monthly').show();
                $('.section-pricing-item-price-amount-yearly').hide();
                $('.section-pricing-item-price-duration').html('/mo');
            }
        });
    }

    this.initOwlCarousel = function () {
        var owl = $('.owla-carousel');
        owl.owlCarousel({
            // stagePadding: 100,
            // rtl:true,
            items : 1,
            loop  : true,
            margin : 20,
            smartSpeed :900,
            responsive: {
              0: {items: 1},
              600: {items: 1},
              1000: {items: 2}
        }
        });
        $('.customNextBtn').on('click', function() {
            owl.trigger('next.owl.carousel', [300]);
        })
        $('.customPrevBtn').on('click', function() {
            owl.trigger('prev.owl.carousel', [300]);
        });
    }

    this.initTestimonialSlider = function () {
        var owl = $('.testimonial-slider');
        owl.owlCarousel({
            // stagePadding: 100,
            // rtl:true,
            items : 1,
            loop  : true,
            margin : 20,
            smartSpeed :900,
            responsive: {
              0: {items: 1},
              600: {items: 1},
              1000: {items: 3}
        }
        });
        $('.customNextBtn').on('click', function() {
            owl.trigger('next.owl.carousel', [300]);
        })
        $('.customPrevBtn').on('click', function() {
            owl.trigger('prev.owl.carousel', [300]);
        });
    }

    this.initRangeSlider = function () {
        var originalVal;
        $('.profile-item-handle').on('change', function() {
            var selected = $(this).val();
            $(this).parent().find('.profile-item-value').html('('+selected+')');
        });
        $('.profile-item-handle').each(function(i, v) {
            var selected = $(v).val();
            $(v).parent().find('.profile-item-value').html('('+selected+')');
        });
    }

    this.initColorsSidePanelOld = function () {
        $('.section-sidepanel-handle').on('click', function() {
            let spWidth = $('.section-sidepanel').width() + 2;
            let spMarginLeft = parseInt($('.section-sidepanel').css('margin-left'),10);
            let w = (spMarginLeft >= 0 ) ? spWidth * - 1 : 0;
            let cw = (w < 0) ? -w : spWidth-22;
            $('.section-sidepanel').animate({marginLeft:w});
            $('.section-sidepanel-handle').animate({},  function() {});
        });

        $('.section-sidepanel-content-item').on('click', function() {
            var ct = $(this).data('ct');
            let last_ct = Cookies.get('color-theme') ? Cookies.get('color-theme') : 'ct-blue';
            let link = application.url+'/f-assets/beta/css/';
            let old_link = application.url+'/f-assets/beta/css/'+last_ct+'.css';
            let new_link = application.url+'/f-assets/beta/css/ct-'+ct+'.css';
            $('link[href="'+old_link+'"]').attr('href',new_link);
            last_ct = 'ct-'+ct;
            Cookies.set('color-theme', 'ct-'+ct);
            $('.section-dark-mode-switch').find('input[type=checkbox]').prop('checked', false);
        })
        let link = application.url+'/f-assets/beta/css/';
        let ct_selected = Cookies.get('color-theme') ? Cookies.get('color-theme') : 'ct-blue';
        var dct = 'ct-'+$('#default-color-theme').val();
        var panel_allowed = $('#color-panel-allowed').val();
        if (panel_allowed == 'yes') {
            $('link[href="'+link+dct+'.css"]').attr('href', link+ct_selected+'.css');
        }
    }

    this.initDarkModeSwitchOld = function () {
        $('.section-dark-mode-switch-handle').on('click', function() {
            var selected = $(this).data('value');
            let last_ct = Cookies.get('color-theme') ? Cookies.get('color-theme') : 'ct-blue';
            let link = application.url+'/f-assets/beta/css/';
            let old_link = application.url+'/f-assets/beta/css/'+last_ct+'.css';
            let new_link = application.url+'/f-assets/beta/css/ct-night.css';
            if (selected == 'light') {
                $('link[href="'+old_link+'"]').attr('href', new_link);
                $(this).data('value', 'night');
                Cookies.set('color-theme', 'ct-night');
                $('.section-dark-mode-switch').find('input[type=checkbox]').prop('checked', false);
            } else {
                $('link[href="'+old_link+'"]').attr('href',link+'ct-blue.css');
                $(this).data('value', 'light');
                Cookies.set('color-theme', 'ct-blue');
                $('.section-dark-mode-switch').find('input[type=checkbox]').prop('checked', true);
            }

        });
        if (Cookies.get('color-theme') == 'ct-night') {
            $('.section-dark-mode-switch-handle').data('value', 'night');
            $('.section-dark-mode-switch').find('input[type=checkbox]').prop('checked', true);
        } else if (typeof Cookies.get('color-theme') !== 'undefined') {
            $('.section-dark-mode-switch-handle').data('value', 'light');
            $('.section-dark-mode-switch').find('input[type=checkbox]').prop('checked', false);
        }
    }

    this.initColorsSidePanel = function () {
        $('.section-sidepanel-handle').on('click', function() {
            let spWidth = $('.section-sidepanel').width() + 2;
            let spMarginLeft = parseInt($('.section-sidepanel').css('margin-left'),10);
            let w = (spMarginLeft >= 0 ) ? spWidth * - 1 : 0;
            let cw = (w < 0) ? -w : spWidth-22;
            $('.section-sidepanel').animate({marginLeft:w});
            $('.section-sidepanel-handle').animate({},  function() {});
        });

        $('.section-sidepanel-content-item').on('click', function() {
            let default_color = $('#default-color-theme').val();
            let current_color = $('#current-color-theme').val();
            var selected = $(this).data('ct');
            $('link[href="'+self.cssLink(current_color)+'"]').attr('href', self.cssLink(selected));
            application.load('/set-color-theme/'+selected, '', function (result) {});
            $('#current-color-theme').val(selected);
            $('.section-dark-mode-switch').find('input[type=checkbox]').prop('checked', false);
        })
    }

    this.initDarkModeSwitch = function () {
        let default_color = $('#default-color-theme').val();
        $('.section-dark-mode-switch-handle').on('click', function() {
            let current_color = $('#current-color-theme').val();
            var selected = $(this).data('value');
            if (selected == 'light') {
                $(this).data('value', 'night');
                $('#current-color-theme').val('night');
                $('link[href="'+self.cssLink(current_color)+'"]').attr('href', self.cssLink('night'));
                application.load('/set-color-theme/night', '', function (result) {});
            } else {
                $(this).data('value', 'light');
                $('#current-color-theme').val(default_color);
                $('link[href="'+self.cssLink(current_color)+'"]').attr('href', self.cssLink(default_color));
                application.load('/set-color-theme/'+default_color, '', function (result) {});
            }
        });
    }

    this.cssLink = function (color) {
        let link = application.url+'/f-assets/beta/css/';
        link = link+'ct-'+color+'.css';
        console.log(link);
        return link;
    }

    this.mobileMenuLevelAdjustment = function () {
        var mobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        const element = document.getElementById("navbar-mobile");
        if (element != null) {        
            if (mobile === true) {
                document.body.style.paddingTop = element.offsetHeight + 'px';
            } else {
                document.body.style.paddingTop = element.offsetHeight + 'px';
            }
        }
        $('.navbar-main li ul li.dropdown').on('click', function(e) {
            if (mobile === true) {
                e.preventDefault();
                var link = $(this).find('.dropdown-item').attr('href');
                return false;
            }
        });
        $('.navbar-main li ul li.dropdown .dropdown-item').on('click', function(e) {
            if (mobile === true) {
                e.preventDefault();
                var link = $(this).attr('href');
                window.location = link;
                return false;
            }
        });        
    }

    this.initHomeFeatures = function () {
        self.animateAgainOnScroll('.section-features-element-1', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-1', 'section-features-element-right', 'slide-from-right');
        self.animateAgainOnScroll('.section-features-element-2', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-2', 'section-features-element-right', 'slide-from-right');
        self.animateAgainOnScroll('.section-features-element-3', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-3', 'section-features-element-right', 'slide-from-right');
        self.animateAgainOnScroll('.section-features-element-4', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-4', 'section-features-element-right', 'slide-from-right');
        self.animateAgainOnScroll('.section-features-element-5', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-5', 'section-features-element-right', 'slide-from-right');
        self.animateAgainOnScroll('.section-features-element-6', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-6', 'section-features-element-right', 'slide-from-right');
        self.animateAgainOnScroll('.section-features-element-7', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-7', 'section-features-element-right', 'slide-from-right');
        self.animateAgainOnScroll('.section-features-element-8', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-8', 'section-features-element-right', 'slide-from-right');
        self.animateAgainOnScroll('.section-features-element-9', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-9', 'section-features-element-right', 'slide-from-right');
        self.animateAgainOnScroll('.section-features-element-10', 'section-features-element-left', 'slide-from-left');
        self.animateAgainOnScroll('.section-features-element-10', 'section-features-element-right', 'slide-from-right');
    }

    this.starRating = function (star) {
        star.click(function(){
            var stars = $('.star-rating-beta').find('li')
            stars.removeClass('on');
            var thisIndex = $(this).parents('li').index();
            for(var i=0; i <= thisIndex; i++){
                stars.eq(i).addClass('on');
            }
            //$('.scoreNow').html(thisIndex+1);
            self.starRatingScore(thisIndex+1);
        });
    }

    this.starRatingScore = function () {
        $('.scoreNow').html(i);      
    }

    this.initStarRating = function () {
        if ($('.star-rating-beta').length > 0) {
            self.starRating($('.star-rating-beta li a'));
        }        
    }


    this.initContactForm = function () {
        application.onSubmit('#main_contact_form', function (result) {
            application.showLoader('main_contact_form_button');
            application.post('/contact-form-submit', '#main_contact_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success == 'true') {
                    $('#main_contact_form').trigger("reset");
                }
                application.hideLoader('main_contact_form_button');
                application.showMessages(result.messages, 'main_contact_form');
            });
        });
    };

    //For Alpha
    this.initEmployerRegisterForm = function () {
        application.onSubmit('#employer_register_form', function (result) {
            application.showLoader('employer_register_form_button');
            application.post('/employer-register-free', '#employer_register_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success == 'true') {
                    $('#employer_register_form').trigger("reset");
                }
                application.hideLoader('employer_register_form_button');
                application.showMessages(result.messages, 'employer_register_form');
            });
        });
    };

    //For Alpha
    this.initCandidateRegisterForm = function () {
        application.onSubmit('#candidate_register_form', function (result) {
            application.showLoader('candidate_register_form_button');
            application.post('/candidate-register', '#candidate_register_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success == 'true') {
                    $('#candidate_register_form').trigger("reset");
                }
                application.hideLoader('candidate_register_form_button');
                application.showMessages(result.messages, 'candidate_register_form');
            });
        });
    };

    //For Alpha
    this.initEmployerForgot = function () {
        application.onSubmit('#employer_forgot_form', function (result) {
            application.showLoader('employer_forgot_form_button');
            application.post('/send-password-link', '#employer_forgot_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('employer_forgot_form_button');
                application.showMessages(result.messages, 'employer_forgot_form');
            });
        });
    };

    //For Alpha
    this.initCandidateForgot = function () {
        application.onSubmit('#candidate_forgot_form', function (result) {
            application.showLoader('candidate_forgot_form_button');
            application.post('/send-password-link', '#candidate_forgot_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('candidate_forgot_form_button');
                application.showMessages(result.messages, 'candidate_forgot_form');
            });
        });
    };

    this.initBothPasswordReset = function () {
        application.onSubmit('#front_password_reset_form', function (result) {
            application.showLoader('front_password_reset_form_button');
            application.post('/reset-password', '#front_password_reset_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#front_password_reset_form').find("input[type=text], textarea").val("");
                }
                application.hideLoader('front_password_reset_form_button');
                application.showMessages(result.messages, 'front_password_reset_form');
            });
        });
    };

    this.initNewsSearch = function () {
        $("#news-category-dd").on('change', function() {
            self.doNewsSearch();
        });

        $('.news-search-value').on('keypress', function (event) {
            if(event.which == 13) {
                self.doNewsSearch();
            }
        });
        $('#news-search-btn').on('click', function (event) {
            self.doNewsSearch();
        });
    };

    this.doNewsSearch = function () {
        var page = 'page='+$('#news-page').val();
        var search = '&search='+$('.news-search-value').val();
        var category = '&category='+($('#news-category-dd').val() ? $('#news-category-dd').val() : '');
        window.location = application.url+'/news?'+page+search+category;
    };

    this.initCompaniesSortAndSearch = function () {
        $(".companies-list-select-sort, #industry-dd, #no-of-employees-dd").on('change', function() {
            self.doCompaniesSearch();
        });

        $('.companies-search-value').on('keypress', function (event) {
            if(event.which == 13) {
                self.doCompaniesSearch();
            }
        });
        $('#companies-search-btn').on('click', function (event) {
            self.doCompaniesSearch();
        });
    };

    this.doCompaniesSearch = function () {
        var page = 'page='+$('#companies-page').val();
        var sort = '&sort='+$('.companies-list-select-sort').val();        
        var search = '&search='+$('.companies-search-value').val();
        var industry = '&industry='+($('#industry-dd').val() ? $('#industry-dd').val() : '');
        var no_of_employees = '&no_of_employees='+($('#no-of-employees-dd').val() ? $('#no-of-employees-dd').val() : '');
        window.location = application.url+'/companies?'+page+sort+search+industry+no_of_employees;
    };

    this.initJobsSort = function () {
        $('.jobs-list-select-sort').on('change', function() {
            self.doJobSearch();
        });
    };

    this.initJobsKeywordSearch = function () {
        $('.job-search-value').on('keypress', function (event) {
            if(event.which == 13) {
                self.doJobSearch();
            }
        });
        $('.job-search-btn').on('click', function (event) {
            self.doJobSearch();
        });
    };

    this.initJobsViewType = function () {
        $('.jobs-view-type').on('click', function (event) {
            $('#jobs-view-type').val($(this).data('type'));
            self.doJobSearch();
        })        
    }

    this.initJobsCompanySearchOn = function () {
        $("input.jobs-company-check").on('change', function() {
            self.doJobSearch();
        });
    };

    this.initJobsDepartmentSearchOn = function () {
        $("input.jobs-department-check").on('change', function() {
            self.doJobSearch();            
        });
    };

    this.initJobFilterSearch = function () {
        var selected_job_filters = $('#job_filters_sel').val() ? JSON.parse($('#job_filters_sel').val()) : {};
        $(".job-filter").each(function(i, v) {
            var key = $(this).data("id");
            if (selected_job_filters[key] !== undefined) {
                job_filters[key] = selected_job_filters[key];
            } else {
                job_filters[key] = [];
            }
        });

        $('.job-filter-check').on('change', function(e) {
            e.preventDefault();
            var val = $(this).val();
            var id = $(this).data("id");
            if ($(this).is(':checked') && job_filters[id].includes(val) == false) {
                job_filters[id].push(val);
            } else {
                const index = job_filters[id].indexOf(val);
                job_filters[id].splice(index, 1);
            }            
            self.doJobSearch();            
        });

        $('.job-filter-dd').on('change', function() {
            var val = $(this).val();
            var id = $(this).data("id");
            job_filters[id] = [];
            job_filters[id].push(val);
            self.doJobSearch();
        });
    };

    this.initKeywordHistorySearch = function () {
        $('.keyword-history-search').on('click', function(e) {
            e.preventDefault();
            $('.job-search-value').val($(this).data('value'));
            self.doJobSearch();
        });
    }

    this.doJobSearch = function () {
        var page = 'page=1';
        var sort = '&sort='+($('.jobs-list-select-sort').val() ? $('.jobs-list-select-sort').val() : '');
        var search = '&search='+($('.job-search-value').val() ? $('.job-search-value').val() : '');
        var filters = '&filters='+JSON.stringify(job_filters);
        var departments = '&departments=';
        var companies = '&companies=';
        var view = '&view='+($('#jobs-view-type').val() ? $('#jobs-view-type').val() : 'list');
        $('.jobs-department-check').each(function (i, v) {
            if ($(this).is(':checked')) {
                departments += $(this).val()+',';
            }
        });
        $('.jobs-company-check').each(function (i, v) {
            if ($(this).is(':checked')) {
                companies += $(this).val()+',';
            }
        });
        if ($('#job-department-home').length > 0) {
            departments += $('#job-department-home').val() ? $('#job-department-home').val() : '';
        }
        departments = departments.substring(0,departments.length-1);
        window.location = application.url+'/jobs?'+page+sort+view+search+departments+companies+filters;
    };

    this.initCandidatesSort = function () {
        $('.candidates-list-select-sort').on('change', function() {
            self.doCandidatesSearch();
        });
    };

    this.initCandidatesKeywordSearch = function () {
        $('.candidates-search-value').on('keypress', function (event) {
            if(event.which == 13) {
                self.doCandidatesSearch();
            }
        });
        $('.candidates-search-btn').on('click', function (event) {
            self.doCandidatesSearch();
        });
    };

    this.initCandidatesViewType = function () {
        $('.candidates-view-type').on('click', function (event) {
            $('#candidates-view-type').val($(this).data('type'));
            self.doCandidatesSearch();
        })        
    }    

    this.initCandidatesResumeSections = function () {
        $('.candidates-experiences-btn, .candidates-qualifications-btn, .candidates-achievements-btn, .candidates-skills-btn, .candidates-languages-btn').on('click', function (event) {
            self.doCandidatesSearch();
        })        
    }

    this.doCandidatesSearch = function () {
        var page = 'page=1';
        var sort = '&sort='+$('.candidates-list-select-sort').val();        
        var search = '&search='+$('.candidates-search-value').val();
        var view = '&view='+$('#candidates-view-type').val();
        var all = '';
        all += '&candidates_experiences_value='+$('.candidates-experiences-value').val();
        all += '&candidates_experiences_range='+$('.candidates-experiences-range').val();
        all += '&candidates_qualifications_value='+$('.candidates-qualifications-value').val();
        all += '&candidates_qualifications_range='+$('.candidates-qualifications-range').val();
        all += '&candidates_achievements_value='+$('.candidates-achievements-value').val();
        all += '&candidates_achievements_range='+$('.candidates-achievements-range').val();
        all += '&candidates_skills_value='+$('.candidates-skills-value').val();
        all += '&candidates_skills_range='+$('.candidates-skills-range').val();
        all += '&candidates_languages_value='+$('.candidates-languages-value').val();
        all += '&candidates_languages_range='+$('.candidates-languages-range').val();
        window.location = application.url+'/candidates?'+page+sort+view+search+all;
    };

    this.initHomeEmployersCarousel = function() {
        $('.employers-carousel').owlCarousel({
            autoplay:false,
            margin:0,
            responsiveClass:true,
            responsive:{
                0:{items:1, nav:false},
                600:{items:3, nav:false},
                1000:{items:6, nav:false, loop:false}
            }
        });
    }

    this.initHomeTestimonialCarousel = function() {
        $('.testimonial-carousel').owlCarousel({
            loop: true,
            nav: false,
            dots: true,
            autoplay: true,
            responsive: {
                0: {items: 1},
                768: {items: 1},
                1000: {items: 1}
            }
        });
    }

    this.initJobDetailSimilarJobsCarousel = function() {
        var owl = $('.similar-jobs-carousel');
        owl.owlCarousel({
            loop: true,
            autoplay:true,
            margin:20,
            responsiveClass:true,
            responsive:{
                0:{items:1, nav:false},
                600:{items:2, nav:false},
                1000:{items:2, nav:false, loop:false}
            }
        });
        $('.customNextBtn').on('click', function() {
            owl.trigger('next.owl.carousel', [300]);
        })
        $('.customPrevBtn').on('click', function() {
            owl.trigger('prev.owl.carousel', [300]);
        });        
    }

    this.initCandidateDetailSimilarCandidatesCarousel = function() {
        var owl = $('.similar-candidates-carousel');
        owl.owlCarousel({
            loop: true,
            autoplay:true,
            margin:20,
            responsiveClass:true,
            responsive:{
                0:{items:1, nav:false},
                600:{items:2, nav:false},
                1000:{items:2, nav:false, loop:false}
            }
        });
        $('.customNextBtn').on('click', function() {
            owl.trigger('next.owl.carousel', [300]);
        })
        $('.customPrevBtn').on('click', function() {
            owl.trigger('prev.owl.carousel', [300]);
        });        
    }

    this.initBackToTop = function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.back-to-top').fadeIn('slow');
            } else {
                $('.back-to-top').fadeOut('slow');
            }
        });
    }

    this.initRegisterBtn = function() {
        $('.front-register-btn').on('click', function() {
            window.location = application.url+'/register';
        });
    }

    this.initPillRating = function () {
        $('.pill-rating').barrating('show', {
            theme: 'bars-pill',
            initialRating: 'A',
            showValues: true,
            showSelectedRating: false,
            allowEmpty: true,
            emptyValue: '-- no rating selected --',
            onSelect: function(value, text) {
                self.traits.push($(this).data('id'))
            }
        });
    };    

    //Function for alpha
    this.initShowLoginModal = function () {
        var modal = '#login-modal';
        $('.global-login-btn').on('click', function(e) {
            e.preventDefault();
            application.load('/login', modal+' .modal-body-container', function (result) {
                $(modal).modal('show');
                self.initLogin();
            });
        });
    }

    //Function for alpha and beta
    this.initLogin = function () {
        application.onSubmit('#front_login_form', function (result) {
            application.showLoader('front_login_form_button');
            application.post('/login-post', '#front_login_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#modal-front').modal('hide');
                    window.location.reload();
                } else {
                    application.hideLoader('front_login_form_button');
                    application.showMessages(result.messages, 'front_login_form');
                }
            });
        });
    };

    //Function for beta
    this.initShowLoginRegisterModal = function () {
        var modal = '#modal-alpha';
        $('.global-login-btn').on('click', function(e) {
            e.preventDefault();
            application.load('/login-register-modal/login', modal+' .modal-body-container', function (result) {
                $(modal).modal('show');
                self.initLogin();
                self.initShowRegisterForm();
                self.initShowPasswordResetForm();                
                self.initActiveUserTypeSelector();
            });
        });
        $('.global-register-btn').on('click', function(e) {
            e.preventDefault();
            application.load('/login-register-modal/register', modal+' .modal-body-container', function (result) {
                $(modal).modal('show');
                self.initBothRegister();
                self.initShowLoginForm();
                self.initActiveUserTypeSelector();
            });
        });
    };

    //Function for beta
    this.initShowRegisterForm = function () {
        $('.modal-back-to-register-btn').on('click', function(e) {
            e.preventDefault();
            $('.right-shadow-div').show();
            application.load('/register-form', '', function (result) {
                var result = application.response;
                $('.modal-title-login-register').html(lang['register']);
                $('.modal-body-login-register').html(result);
                $('.right-shadow-div').hide();
                self.initBothRegister();
                self.initShowLoginForm();
                self.initActiveUserTypeSelector();
            });
        });
    };

    //Function for beta
    this.initShowLoginForm = function () {
        $('.modal-back-to-login-btn').on('click', function(e) {
            e.preventDefault();
            $('.right-shadow-div').show();
            application.load('/login', '', function (result) {
                var result = application.response;
                $('.modal-title-login-register').html(lang['login']);
                $('.modal-body-login-register').html(result);
                $('.right-shadow-div').hide();
                self.initLogin();
                self.initShowRegisterForm();
                self.initShowPasswordResetForm();
                self.initActiveUserTypeSelector();
            });
        });
    };

    //Function for beta
    this.initShowPasswordResetForm = function () {
        $('.modal-forgot-password-btn').on('click', function(e) {
            e.preventDefault();
            $('.right-shadow-div').show();
            application.load('/forgot-password-form', '', function (result) {
                var result = application.response;
                $('.modal-title-login-register').html(lang['forgot_password']);
                $('.modal-body-login-register').html(result);
                $('.right-shadow-div').hide();
                self.initBothForgot();
                self.initShowLoginForm();
                self.initActiveUserTypeSelector();
            });
        });
    };

    //For Beta
    this.initBothRegister = function () {
        application.onSubmit('#both_register_form', function (result) {
            var type = $('input[name="type"]:checked').val() ? $('input[name="type"]:checked').val() : $('#login-type').val();
            console.log(type);

            if(type == 'candidate'){
                type = 'candidate-register';
            }else if(type == 'company'){
                type = 'company-register';
            }else{
                type = 'employer-register-free';
            }
            // type = type == 'candidate' ? 'candidate-register' : 'employer-register-free';
            application.showLoader('both_register_form_button');
            application.post('/'+type, '#both_register_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success == 'true') {
                    $('#both_register_form').trigger("reset");
                }
                application.hideLoader('both_register_form_button');
                application.showMessages(result.messages, 'both_register_form');
            });
        });
    };

    //For Beta
    this.initBothForgot = function () {
        application.onSubmit('#both_forgot_password_form', function (result) {
            application.showLoader('both_forgot_password_form_button');
            application.post('/send-password-link', '#both_forgot_password_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('both_forgot_password_form_button');
                application.showMessages(result.messages, 'both_forgot_password_form');
            });
        });
    };    

    this.initActiveUserTypeSelector = function () {
        var login_type = $('#login-type').val();
        if (login_type == 'both' || login_type == 'candidate' || login_type == 'company') {
            $('#register-company-field-container').hide();
            $('#register-company-field').hide();
        }
        $('.btn-login-type-label').on('click', function() {
            $(this).parent().find('.btn').removeClass('active');
            $(this).addClass('active');
            var type = $(this).prev('input[type="radio"]').val();
            if (type == 'candidate' ) {
                $('#register-company-field-container').show();
                $('#register-company-field').show();
                $('#register-company-field').prop('disabled', false);
                $('#linkedin-link').prop('href', $('#linkedin-link-employer').val());
                $('#google-link').prop('href', $('#google-link-employer').val());
            } else if( type == 'company') {
                $('#register-company-field-container').show();
                $('#register-company-field').show();
                $('#register-company-field').prop('disabled', false);
                $('#linkedin-link').prop('href', $('#linkedin-link-employer').val());
                $('#google-link').prop('href', $('#google-link-employer').val());
            }else {
                $('#register-company-field-container').hide();
                $('#register-company-field').hide();
                $('#register-company-field').prop('disabled', true);
                $('#linkedin-link').prop('href', $('#linkedin-link-candidate').val());
                $('#google-link').prop('href', $('#google-link-candidate').val());
            }
        });
    }

    this.initReferAndFavoriteRibbons = function () {
        const ribbons = document.querySelectorAll(".job-list-ribbon");
        ribbons.forEach((ribbon) => {
            ribbon.addEventListener('mouseover', (e) => {
                let target = e.target;
                while (target !== ribbon) { target = target.parentNode; }
                target.className = 'job-list-ribbon ' +target.dataset.id+ ' down';
            });
            ribbon.addEventListener('mouseout', (e) => {
                let target = e.target;
                while (target !== ribbon) { target = target.parentNode; }
                target.className = 'job-list-ribbon ' +target.dataset.id+ ' up';
            });
        });
    }

    this.initMarkUnmarkJobFavorite = function () {
        $('.mark-favorite').off();
        $('.mark-favorite').on('click', function() {
            var item = $(this);
            if (item.hasClass('favorited')) {
                application.load('/unmark-favorite/'+$(this).data('id'), '', function (result) {
                    var result = JSON.parse(application.response);
                    if (result.success == 'true') {
                        item.removeClass('favorited');
                        item.removeClass('fa-solid');
                        item.addClass('fa-regular');
                        item.attr('title', lang['mark_favorite']);
                    }
                });
            } else {
                application.load('/mark-favorite/'+$(this).data('id'), '', function (result) {
                    var result = JSON.parse(application.response);
                    if (result.success == 'true') {
                        item.addClass('favorited');
                        item.addClass('fa-solid');
                        item.removeClass('fa-regular');
                        item.attr('title', lang['unmark_favorite']);
                    } else {
                        $('.global-login-btn').trigger('click');
                    }
                });
            }
        });
    };

    this.initFollowUnfollowJob = function () {
        $('.follow-job').off();
        $('.follow-job').on('click', function() {
            var item = $('.follow-job .fa-heart');
            var data_id = $(this).data('id');
            if (item.hasClass('fa-solid')) {
                application.load('/unfollow-job/'+$(this).data('id'), '', function (result) {
                    var result = JSON.parse(application.response);
                    if (result.success == 'true') {
                        item.removeClass('fa-solid');
                        item.addClass('fa-regular');
                        item.attr('title', lang['follow_job']);
                    }
                });
            } else {
                var modal = '.follow-Job-modal';
                $(modal).modal('show');
                application.load('/follow-job-view', modal + ' .modal-body-container', function (result) {
                    setTimeout(() => {
                        $('#follow_job_id').val(data_id);
                    }, 1000);
                    self.initSaveJobFollow();
                });
            }
        });
    };

    this.initSaveJobFollow = function () {
        application.onSubmit('#job_follow_form', function (result) {
            application.showLoader('job_follow_form_button');
            application.post('/follow-job', '#job_follow_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success == 'false' ) {
                    application.hideLoader('job_follow_form_button');
                    application.showMessages(result.messages, 'job_follow_form');
                } else {
                    if (result.success == 'true') {
                        setTimeout(function() { 
                            $('.follow-Job-modal').modal('hide');
                        }, 1000);
                        var item = $('.follow-job .fa-heart');
                        item.addClass('fa-solid');
                        item.removeClass('fa-regular');
                        item.attr('title', lang['unfollow_job']);
                    }
                    application.hideLoader('job_follow_form_button');
                    application.showMessages(result.messages, 'job_follow_form');
                }
            });
        });
    };

    this.initMarkUnmarkCandidateFavorite = function () {
        $('.mark-candidate-favorite').off();
        $('.mark-candidate-favorite').on('click', function() {
            var item = $(this);
            if (item.hasClass('favorited')) {
                application.load('/unmark-candidate-favorite/'+$(this).data('id'), '', function (result) {
                    var result = JSON.parse(application.response);
                    if (result.success == 'true') {
                        item.removeClass('favorited');
                        item.removeClass('fa-solid');
                        item.addClass('fa-regular');
                        item.attr('title', lang['mark_favorite']);
                    }
                });
            } else {
                application.load('/mark-candidate-favorite/'+$(this).data('id'), '', function (result) {
                    var result = JSON.parse(application.response);
                    if (result.success == 'true') {
                        item.addClass('favorited');
                        item.addClass('fa-solid');
                        item.removeClass('fa-regular');
                        item.attr('title', lang['unmark_favorite']);
                    } else {
                        $('.global-login-btn').trigger('click');
                    }
                });
            }
        });
    };

    this.initJobRefer = function () {
        $('.refer-job').off();
        $('.refer-job').on('click', function() {
            var modal = '.modal-refer-job';
            $(modal).modal('show');
            var button = $(this);
            application.load('/refer-job-view', modal + ' .modal-body-container', function (result) {
                $('#job_id').val(button.data('id'));
                self.initSaveJobRefer();
            });
        });
    };

    this.initSaveJobRefer = function () {
        application.onSubmit('#job_refer_form', function (result) {
            application.showLoader('job_refer_form_button');
            application.post('/refer-job', '#job_refer_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success == 'false' ) {
                    application.hideLoader('job_refer_form_button');
                    application.showMessages(result.messages, 'job_refer_form');
                } else {
                    if (result.success == 'true') {
                        setTimeout(function() { 
                            $('.modal-refer-job').modal('hide');
                        }, 2000);
                    }
                    application.hideLoader('job_refer_form_button');
                    application.showMessages(result.messages, 'job_refer_form');
                }
            });
        });
    };

    this.preventStarRatingClick = function () {
        $('.star-icon').on('click', function (e) {
            e.preventDefault();
            return false;
        });
    }

}

$(document).ready(function() {
    var main = new Main();
    main.initLanguageDropdown();
    main.initOwlCarousel();
    main.initTestimonialSlider();
    main.initJobDetailSimilarJobsCarousel();
    main.initCandidateDetailSimilarCandidatesCarousel();
    main.mobileMenuLevelAdjustment();
    main.initDarkModeSwitch();
    main.initColorsSidePanel();
    main.initRangeSlider();
    main.initPricingSwitch();
    main.initStarRating();
    main.initHomeFeatures();
    main.preventStarRatingClick();

    //Jobs list functions
    main.initJobsSort();
    main.initJobsKeywordSearch();
    main.initJobsViewType();
    main.initJobsCompanySearchOn();
    main.initJobsDepartmentSearchOn();
    main.initJobFilterSearch();
    main.initKeywordHistorySearch();

    //Candidates List functions
    main.initCandidatesSort();
    main.initCandidatesKeywordSearch();
    main.initCandidatesViewType();
    main.initCandidatesResumeSections();

    //Employers List function
    main.initCompaniesSortAndSearch();
    main.initNewsSearch();
    main.initContactForm();

    //Login and Register function
    main.initShowLoginModal();
    main.initShowLoginRegisterModal();
    main.initBothPasswordReset();

    main.initMarkUnmarkCandidateFavorite();
    main.initMarkUnmarkJobFavorite();
    main.initFollowUnfollowJob();
    main.initJobRefer();

});
