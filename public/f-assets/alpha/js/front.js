function Front() {

    "use strict";

    var self = this;
    var job_filters = {};

    this.initContactForm = function () {
        application.onSubmit('#home_contact_form', function (result) {
            application.showLoader('home_contact_form_button');
            application.post('/contact-form-submit', '#home_contact_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success == 'true') {
                    $('#home_contact_form').trigger("reset");
                }
                application.hideLoader('home_contact_form_button');
                application.showMessages(result.messages, 'home_contact_form');
            });
        });
    };

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

    this.initNewsFilter = function () {
        $(".news-category-check").on('change', function() {
            if(this.checked) {
                var category = '&category='+$(this).val();
            } else {
                var category = '&category=';
            }
            var page = '?page='+$('#news-page').val();
            var keyword = '&keyword='+$('#news-search-input').val();
            window.location = application.url+'/news'+page+keyword+category;
        });
    };

    this.initNewsSearch = function () {
        $('.news-search-button').on('click', function (event) {
            var page = '?page='+$('#news-page').val();
            var keyword = '&keyword='+$('#news-search-input').val();
            var category = '&category='+$('#selected-category').val();
            window.location = application.url+'/news'+page+keyword+category;
        });
        $('#news-search-input').on('keypress', function (event) {
            var page = '?page='+$('#news-page').val();
            var keyword = '&keyword='+$('#news-search-input').val();
            var category = '&category='+$('#selected-category').val();
            var url = application.url+'/news'+page+keyword+category;
            if (event.which == 13) {
                window.location = url;
            }
        });
    };

    this.initEmployersSort = function () {
        $(".employers-list-select-sort").on('change', function() {
            var page = '?page='+$('#employers-page').val();
            var sort = '&sort='+$('.employers-list-select-sort').val();
            window.location = application.url+'/companies'+page+sort;
        });
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
    };

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

        $('.job-filter-check').on('change', function() {
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

    this.doJobSearch = function () {
        var page = 'page=1';
        var sort = '&sort='+$('.jobs-list-select-sort').val();        
        var search = '&search='+$('.job-search-value').val();
        var filters = '&filters='+JSON.stringify(job_filters);
        var departments = '&departments=';
        var companies = '&companies=';
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
        window.location = application.url+'/jobs?'+page+sort+search+departments+companies+filters;
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

    this.showLoginForm = function () {
        var modal = '#modal-front';
        application.load('/login', modal+' .modal-body-container', function (result) {
            $(modal).modal('show');
            self.initLogin();
        });        
    }

    this.initShowLoginForm = function () {
        $('.header-btn-login').on('click', function(e) {
            e.preventDefault();
            self.showLoginForm();
        });
    };

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

    this.initMarkUnmarkFavorite = function () {
        $('.mark-favorite').off();
        $('.mark-favorite').on('click', function() {
            var item = $(this);
            if (item.hasClass('favorited')) {
                application.load('/unmark-favorite/'+$(this).data('id'), '', function (result) {
                    var result = JSON.parse(application.response);
                    if (result.success == 'true') {
                        item.removeClass('favorited');
                        item.attr('title', lang['mark_favorite']);
                    }
                });
            } else {
                application.load('/mark-favorite/'+$(this).data('id'), '', function (result) {
                    var result = JSON.parse(application.response);
                    if (result.success == 'true') {
                        item.addClass('favorited');
                        item.attr('title', lang['unmark_favorite']);
                    } else {
                        self.showLoginForm();
                    }
                });
            }
        });
    };

    this.initSaveJobRefer = function () {
        console.log('initialized');
        application.onSubmit('#job_refer_form', function (result) {
            application.showLoader('job_refer_form_button');
            application.post('/refer-job', '#job_refer_form', function (res) {
                var result = JSON.parse(application.response);
                console.log(result);
                if (result.success == 'false' ) {
                    application.hideLoader('job_refer_form_button');
                    application.showMessages(result.messages, 'job_refer_form');
                } else {
                    if (result.success == 'true') {
                        setTimeout(function() { 
                            $('#modal-refer').modal('hide');
                        }, 2000);
                    }
                    application.hideLoader('job_refer_form_button');
                    application.showMessages(result.messages, 'job_refer_form');
                }
            });
        });
    };
    
    this.initJobRefer = function () {
        $('.refer-job').off();
        $('.refer-job').on('click', function() {
            var modal = '#modal-refer';
            $(modal).modal('show');
            var button = $(this);
            application.load('/refer-job-view', modal + ' .modal-body-container', function (result) {
                $('#job_id').val(button.data('id'));
                self.initSaveJobRefer();
            });
        });
    };
    
}

$(document).ready(function() {
    var front = new Front();
    front.initContactForm();
    front.initEmployerRegisterForm();
    front.initCandidateRegisterForm();
    front.initEmployerForgot();
    front.initCandidateForgot();
    front.initBothPasswordReset();
    front.initNewsFilter();
    front.initNewsSearch();

    //Jobs list functions
    front.initJobsSort();
    front.initJobsKeywordSearch();
    front.initJobsCompanySearchOn();
    front.initJobsDepartmentSearchOn();
    front.initJobFilterSearch();

    front.initEmployersSort();
    front.initHomeEmployersCarousel();
    front.initHomeTestimonialCarousel();
    front.initBackToTop();
    front.initRegisterBtn();
    front.initPillRating();
    front.initShowLoginForm();
    front.initReferAndFavoriteRibbons();
    front.initJobRefer();
    front.initMarkUnmarkFavorite();
});
