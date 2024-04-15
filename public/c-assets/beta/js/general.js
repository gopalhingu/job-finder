function General() {

    "use strict";

    var self = this;
    var job_filters = {};
    self.traits = [];

    this.initJobSearch = function () {
        $('.job-search-button').off();
        $('.job-search-button').on('click', function (event) {
            self.doJobSearch();
        });
        $('.job-search-value').on('keypress', function (event) {
            if(event.which == 13) {
                self.doJobSearch();
            }
        });
        $('.job-search-btn').on('click', function (event) {
            self.doJobSearch();
        });        
    };

    this.initDepartmentSearch = function () {
        //For Alpha
        $('input.department-check').on('ifChecked', function(event){
            self.doJobSearch();            
        });
        //For Alpha
        $('input.department-check').on('ifUnchecked', function(event){
            self.doJobSearch();            
        });
        //For Beta
        $("input.department-check").on('change', function() {
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

        //For Alpha
        $('input.job-filter-check').on('ifChecked', function(event){
            var val = $(this).val();
            var id = $(this).data("id");
            if ($(this).is(':checked') && job_filters[id].includes(val) == false) {
                job_filters[id].push(val);
            }
            self.doJobSearch();            
        });

        //For Alpha
        $('input.job-filter-check').on('ifUnchecked', function(event){
            var val = $(this).val();
            var id = $(this).data("id");
            const index = job_filters[id].indexOf(val);
            if (index > -1) {
              job_filters[id].splice(index, 1);
            }
            self.doJobSearch();     
        });

        //For Beta
        $('.job-filter-check').on('change', function(e) {
            e.preventDefault();
            var val = $(this).val();
            var id = $(this).data("id");
            const index = job_filters[id].indexOf(val);
            if ($(this).is(':checked') && job_filters[id].includes(val) == false) {
                job_filters[id].push(val);
            } else if (index > -1) {
                job_filters[id].splice(index, 1);
            }
            self.doJobSearch();            
        });      

        $('.job-filter-dd, .job-filter-radio').on('change', function() {
            var val = $(this).val();
            var id = $(this).data("id");
            job_filters[id] = [];
            job_filters[id].push(val);
            self.doJobSearch();
        });
    };

    this.doJobSearch = function () {
        var search = $('.job-search-value').val();
        var departments = '&departments=';
        var filters = '&filters='+JSON.stringify(job_filters);
        $('.department-check').each(function (i, v) {
            if ($(this).is(':checked')) {
                departments += $(this).val()+',';
            }
        });
        departments = departments.substring(0,departments.length-1);
        window.location = application.url+'jobs?search='+search+departments+filters;
    }; 

    this.initBlogSearch = function () {
        $('.blog-search-button').off();
        $('.blog-search-button').on('click', function (event) {
            self.doBlogSearch();
        });
        $('.blog-search-value').on('keypress', function (event) {
            if(event.which == 13) {
                self.doBlogSearch();
            }
        });
    };

    this.initBlogCategorySearch = function () {
        //For Alpha
        $('input.category-check').on('ifChecked', function(event){
            self.doBlogSearch();            
        });
        //For Alpha
        $('input.category-check').on('ifUnchecked', function(event){
            self.doBlogSearch();            
        });
        //For Beta
        $("#blog-category-dd").on('change', function() {
            self.doBlogSearch();
        });        
    };

    this.doBlogSearch = function () {
        var search = $('.blog-search-value').val();
        var categories = '&categories=';
        $('.category-check').each(function (i, v) {
            if ($(this).is(':checked')) {
                categories += $(this).val()+',';
            }
        });
        if ($('#blog-category-dd').length > 0) {
            categories += $('#blog-category-dd').val() ? $('#blog-category-dd').val() : '';
        }
        window.location = application.url+'blogs?search='+search+categories;
    }; 

    this.initIcheck = function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
        });
    };

    this.initMarkFavorite = function () {
        $('.mark-favorite').off();
        $('.mark-favorite').on('click', function() {
            var item = $(this);
            if (item.hasClass('favorited')) {
                application.load('unmark-favorite/'+$(this).data('id'), '', function (result) {
                    var result = JSON.parse(application.response);
                    if (result.success == 'true') {
                        item.removeClass('favorited');
                        item.removeClass('fa-solid');
                        item.addClass('fa-regular');
                        item.attr('title', lang['mark_favorite']);
                    }
                });
            } else {
                application.load('mark-favorite/'+$(this).data('id'), '', function (result) {
                    var result = JSON.parse(application.response);
                    if (result.success == 'true') {
                        item.addClass('favorited');
                        item.addClass('fa-solid');
                        item.removeClass('fa-regular');                        
                        item.attr('title', lang['unmark_favorite']);
                    } else {
                        window.location = application.url+'login';
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
            application.load('refer-job-view', modal + ' .modal-body-container', function (result) {
                $('#job_id').val(button.data('id'));
                self.initSaveJobRefer();
            });
        });
    };

    this.initSaveJobRefer = function () {
        application.onSubmit('#job_refer_form', function (result) {
            application.showLoader('job_refer_form_button');
            application.post('refer-job', '#job_refer_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success == 'false' ) {
                    //window.location = application.url+'login';
                    application.hideLoader('job_refer_form_button');
                    application.showMessages(result.messages, 'job_refer_form');
                } else {
                    if (result.success == 'true') {
                        setTimeout(function() { 
                            $('#modal-default').modal('hide');
                        }, 2000);
                    }
                    application.hideLoader('job_refer_form_button');
                    application.showMessages(result.messages, 'job_refer_form');
                }
            });
        });
    };

    this.initJobApply = function () {
        application.onSubmit('#job_apply_form', function (result) {
            application.showLoader('job_apply_form_button');
            application.post('apply-job', '#job_apply_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('job_apply_form_button');
                application.showMessages(result.messages, 'job_apply_form');
                if (result.success == 'true') {
                    setTimeout(function() { 
                        window.location = application.url+'account/job-applications';
                    }, 1000);
                }
            });
        });
    };

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
                //alert('Selected rating: ' + value);
            }
        });
    };

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
            var ct = $(this).data('ct');
            var main_url = $('#main-url').val();
            let last_ct = Cookies.get('color-theme') ? Cookies.get('color-theme') : 'ct-blue';
            let link = main_url+'/c-assets/beta/css/';
            let old_link = main_url+'/c-assets/beta/css/'+last_ct+'.css';
            let new_link = main_url+'/c-assets/beta/css/ct-'+ct+'.css';
            $('link[href="'+old_link+'"]').attr('href',new_link);
            last_ct = 'ct-'+ct;
            Cookies.set('color-theme', 'ct-'+ct);
            $('.section-dark-mode-switch').find('input[type=checkbox]').prop('checked', false);
        })
        var main_url = $('#main-url').val();
        let link = main_url+'/c-assets/beta/css/';
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
            var main_url = $('#main-url').val();
            let last_ct = Cookies.get('color-theme') ? Cookies.get('color-theme') : 'ct-blue';
            let link = main_url+'/c-assets/beta/css/';
            let old_link = main_url+'/c-assets/beta/css/'+last_ct+'.css';
            let new_link = main_url+'/c-assets/beta/css/ct-night.css';
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

    this.initDarkModeSwitch = function () {
        let default_color = $('#default-color-theme').val();
        $('.section-dark-mode-switch-handle').on('click', function() {
            let current_color = $('#current-color-theme').val();
            var selected = $(this).data('value');
            if (selected == 'light') {
                $(this).data('value', 'night');
                $('#current-color-theme').val('night');
                $('link[href="'+self.cssLink(current_color)+'"]').attr('href', self.cssLink('night'));
                application.load('set-color-theme/night', '', function (result) {});
            } else {
                $(this).data('value', 'light');
                $('#current-color-theme').val(default_color);
                $('link[href="'+self.cssLink(current_color)+'"]').attr('href', self.cssLink(default_color));
                application.load('set-color-theme/'+default_color, '', function (result) {});
            }
        });
    }    

    this.cssLink = function (color) {
        let link = $('#site-full-url').val()+'c-assets/beta/css/';
        link = link+'ct-'+color+'.css';
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


    this.initJobFunctions = function () {
      self.initJobSearch();
      self.initDepartmentSearch();
      self.initJobFilterSearch();
      self.initMarkFavorite();
      self.initJobRefer();
    };

    this.initBlogFunctions = function () {
      self.initBlogSearch();
      self.initBlogCategorySearch();
    };

    this.initBackToTopScrollAndHeaderFunctions = function () {
        // Preloader (if the #preloader div exists)
        $(window).on('load', function () {
            if ($('#preloader').length) {
                $('#preloader').delay(100).fadeOut('slow', function () {
                    $(this).remove();
                });
            }
        });

        // Back to top button
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.back-to-top').fadeIn('slow');
            } else {
                $('.back-to-top').fadeOut('slow');
            }
        });

        $('.back-to-top').click(function(){
            $('html, body').animate({scrollTop : 0},1500, 'easeInOutExpo');
            return false;
        });

        // Header scroll class
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('#header').addClass('header-scrolled');
            } else {
                $('#header').removeClass('header-scrolled');
            }
        });

        if ($(window).scrollTop() > 100) {
            $('#header').addClass('header-scrolled');
        }
    };

    this.initLanguageDropdown = function () {
        if ($(".flag-dropdown").length) {
            $(document).on("click", ".flag-menu .flag-item", function (e) {
                e.preventDefault();
                var id = $(this).data('id');
                application.load('set-front-language/'+id, '', function (result) {});
                setTimeout(function() { 
                    window.location.reload();
                }, 500);
            });
        }
    }

}

$(document).ready(function() {
    var general = new General();
    general.initJobRefer();
    general.initMarkFavorite();
    general.initDarkModeSwitch();
    general.initColorsSidePanel();
    //general.initIcheck();
    general.mobileMenuLevelAdjustment();
    general.initBlogFunctions();

    //Job Apply page
    general.initJobFunctions();
    //general.initPillRating();
    general.initJobApply();
    general.initBackToTopScrollAndHeaderFunctions();
    general.initLanguageDropdown();
});
