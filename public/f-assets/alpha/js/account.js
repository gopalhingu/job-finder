function Account() {

    "use strict";

    var self = this;

    this.initDropify =  function () {
      $('.dropify').dropify();
    };

    this.initOpenCloseResumeSections = function () {
        $(".account-open-close-icon").click(function () {
            var state = $(this).data('state');
            $(this).html(function(i, html){
                var open = '<i class="fa-solid fa-circle-plus"></i>';
                var close = '<i class="fa-solid fa-circle-minus"></i>';
                if (state == 'closed') {
                    $(this).data('state', 'open')
                    return close;
                } else if (state == 'open') {
                    $(this).data('state', 'closed')
                    return open;
                }
            });
        });
    };

    this.initDotMenu = function() {
        $('.dotmenuicons').on('click', function() {
            var id = $(this).data('id');
            document.getElementById(id).classList.toggle("dotmenu-show");
        });
    };

    this.initProfileUpdate = function () {
        application.onSubmit('#profile_update_form', function (result) {
            application.showLoader('profile_update_form_button');
            application.post('/account/profile-update', '#profile_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('profile_update_form_button');
                application.showMessages(result.messages, 'profile_update_form');
            });
        });
    };

    this.initPasswordUpdate = function () {
        application.onSubmit('#password_update_form', function (result) {
            application.showLoader('password_update_form_button');
            application.post('/account/password-update', '#password_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('password_update_form_button');
                application.showMessages(result.messages, 'password_update_form');
            });
        });
    };

    this.initResumeCreateForm = function () {
        $('.add-resume').on('click', function() {
            $('#modal-resume-create').modal('show');
            self.initResumeCreate();
        });
    };

    this.initResumeCreate = function () {
        application.onSubmit('#resume_create_form', function (result) {
            application.showLoader('resume_create_form_button');
            application.post('/account/create-resume', '#resume_create_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('resume_create_form_button');
                application.showMessages(result.messages, 'resume_create_form');
                if (result.success === 'true') {
                    window.location = application.url+'/account/resume/'+result.id;
                }                
            });
        });
    };

    this.initResumeSaveGeneral = function () {
        application.onSubmit('#resume_edit_general_form', function (result) {
            application.showLoader('resume_edit_general_form_button');
            application.post('/account/resume-save-general', '#resume_edit_general_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('resume_edit_general_form_button');
                application.showMessages(result.messages, 'resume_edit_general_form');
                if (result.success == 'true') {
                    setTimeout(function() { 
                        $('#experience-tab a').click();
                    }, 1000);
                }
            });
        });
    };

    this.initResumeSaveExperience = function () {
        application.onSubmit('#resume_edit_experiences_form', function (result) {
            application.showLoader('resume_edit_experiences_form_button');
            application.post('/account/resume-save-experience', '#resume_edit_experiences_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('resume_edit_experiences_form_button');
                application.showMessages(result.messages, 'resume_edit_experiences_form');
                $("html, body").animate({ scrollTop: $("#experiences_heading").offset().top }, "fast");
                if (result.success == 'true') {
                    setTimeout(function() { 
                        $('#qualification-tab a').click();
                    }, 1000);
                }
            });
        });
    };

    this.initResumeSaveQualification = function () {
        application.onSubmit('#resume_edit_qualifications_form', function (result) {
            application.showLoader('resume_edit_qualifications_form_button');
            application.post('/account/resume-save-qualification', '#resume_edit_qualifications_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('resume_edit_qualifications_form_button');
                application.showMessages(result.messages, 'resume_edit_qualifications_form');
                $("html, body").animate({ scrollTop: $("#qualifications_heading").offset().top }, "slow");
                if (result.success == 'true') {
                    setTimeout(function() { 
                        $('#language-tab a').click();
                    }, 1000);
                }
            });
        });
    };

    this.initResumeSaveLanguage = function () {
        application.onSubmit('#resume_edit_languages_form', function (result) {
            application.showLoader('resume_edit_languages_form_button');
            application.post('/account/resume-save-language', '#resume_edit_languages_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('resume_edit_languages_form_button');
                application.showMessages(result.messages, 'resume_edit_languages_form');
                $("html, body").animate({ scrollTop: $("#languages_heading").offset().top }, "slow");
                if (result.success == 'true') {
                    setTimeout(function() { 
                        $('#achievement-tab a').click();
                    }, 1000);
                }
            });
        });
    };

    this.initResumeSaveAchievement = function () {
        application.onSubmit('#resume_edit_achievements_form', function (result) {
            application.showLoader('resume_edit_achievements_form_button');
            application.post('/account/resume-save-achievement', '#resume_edit_achievements_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('resume_edit_achievements_form_button');
                application.showMessages(result.messages, 'resume_edit_achievements_form');
                $("html, body").animate({ scrollTop: $("#achievements_heading").offset().top }, "slow");
                if (result.success == 'true') {
                    setTimeout(function() { 
                        $('#reference-tab a').click();
                    }, 1000);
                }
            });
        });
    };

    this.initResumeSaveReference = function () {
        application.onSubmit('#resume_edit_references_form', function (result) {
            application.showLoader('resume_edit_references_form_button');
            application.post('/account/resume-save-reference', '#resume_edit_references_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('resume_edit_references_form_button');
                application.showMessages(result.messages, 'resume_edit_references_form');
                $("html, body").animate({ scrollTop: $("#references_heading").offset().top }, "slow");
            });
        });
    };

    this.initRemoveSection = function () {
        $('.remove-section').off();
        $('.remove-section').on('click', function () {
            var button = $(this);
            var id = $(this).data('id');
            var type = $(this).data('type');
            var status = confirm(lang['are_u_sure']);
            if (status === true) {
                if (id != '') {
                    application.load('/account/resume-remove-section/'+id+'/'+type, '', function (result) {
                        button.parent().parent().parent().remove();
                    });
                } else {
                    button.parent().parent().parent().remove();
                }
            }
        });
    }

    this.initAddSection = function () {
        $('.add-section').off();
        $('.add-section').on('click', function (event) {
            event.preventDefault();
            var button = $(this);
            var type = $(this).data('type');
            var id = $(this).data('id');
            application.load('/account/resume-add-section/'+id+'/'+type, '', function (result) {
                button.parent().parent().parent().parent().find('.section-container').append(application.response);
                self.initRemoveSection();
                self.initDropify();
            });
        });
    };

    this.initDefaultFieldForResumeSections = function () {
        if ($('#no_experience_found').length > 0) {
            $('#no_experience_found').remove();
            $('.add-section-experience').trigger('click');
        }

        if ($('#no_qualification_found').length > 0) {
            $('#no_qualification_found').remove();
            $('.add-section-qualification').trigger('click');
        }
                
        if ($('#no_language_found').length > 0) {
            $('#no_language_found').remove();
            $('.add-section-language').trigger('click');
        }
                
        if ($('#no_achievement_found').length > 0) {
            $('#no_achievement_found').remove();
            $('.add-section-achievement').trigger('click');
        }
                
        if ($('#no_reference_found').length > 0) {
            $('#no_reference_found').remove();
            $('.add-section-reference').trigger('click');
        }
        
    }

    this.initDocResumeUpdate = function () {
        application.onSubmit('#resume_update_form', function (result) {
            application.showLoader('resume_update_form_button');
            application.post('/account/resume-update-doc', '#resume_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('resume_update_form_button');
                application.showMessages(result.messages, 'resume_update_form');
            });
        });
    };

    this.initJobApply = function () {
        application.onSubmit('#job_apply_form', function (result) {
            application.showLoader('job_apply_form_button');
            application.post('/account/apply-job', '#job_apply_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('job_apply_form_button');
                application.showMessages(result.messages, 'job_apply_form');
                if (result.success == 'true') {
                    setTimeout(function() { 
                        window.location = application.url+'/account/job-applications';
                    }, 1000);
                }
            });
        });
    };

}

$(document).ready(function() {
    var account = new Account();

    //General
    account.initDropify();
    account.initOpenCloseResumeSections();
    account.initDotMenu();
    account.initProfileUpdate();
    account.initPasswordUpdate();

    //Create modal on the resume listing page
    account.initResumeCreateForm();

    //Doc resume update
    account.initDocResumeUpdate();

    //Detailed resume update
    account.initResumeSaveGeneral();
    account.initResumeSaveExperience();
    account.initResumeSaveQualification();
    account.initResumeSaveLanguage();
    account.initResumeSaveAchievement();
    account.initResumeSaveReference();
    account.initRemoveSection();
    account.initAddSection();
    account.initDefaultFieldForResumeSections();    
    account.initJobApply();    
});
