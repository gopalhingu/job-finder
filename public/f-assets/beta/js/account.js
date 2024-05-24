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

    this.initSettingsUpdate = function () {
        application.onSubmit('#settings_update_form', function (result) {
            application.showLoader('settings_update_form_button');
            application.post('/account/settings-update', '#settings_update_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('settings_update_form_button');
                application.showMessages(result.messages, 'settings_update_form');
            });
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
            $('.modal-resume-create').modal('show');
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

    this.initResumeSaveSkill = function () {
        application.onSubmit('#resume_edit_skills_form', function (result) {
            application.showLoader('resume_edit_skills_form_button');
            application.post('/account/resume-save-skill', '#resume_edit_skills_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('resume_edit_skills_form_button');
                application.showMessages(result.messages, 'resume_edit_skills_form');
                $("html, body").animate({ scrollTop: $("#skills_heading").offset().top }, "slow");
                if (result.success == 'true') {
                    setTimeout(function() { 
                        $('#achievement-tab a').click();
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

    this.initResumePlusMinus = function () {
        $('.box-open-close').on('click', function() {
            var item = $(this).find('i');
            if (item.hasClass('fa-plus')) {
                item.addClass('fa-minus');
                item.removeClass('fa-plus');
            } else {
                item.addClass('fa-plus');
                item.removeClass('fa-minus');
            }
        })
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

    this.initQuizTimer = function () {
        if (document.getElementById('quiz_attempt_page')) {
            var quiz_page_time_max = document.getElementById('max');
            var quiz_page_time_now = document.getElementById('now');
            var quiz_page_time_now_value = quiz_page_time_now.value;
            var countDownDate = new Date(quiz_page_time_max.value).getTime();

            // Update the count down every 1 second
            var x = setInterval(function() {

                var now = new Date(quiz_page_time_now_value).getTime();
                var distance = countDownDate - now;
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                var timer = '';
                timer += '<span class="number-wrapper"><div class="line"></div><div class="caption">HOURS</div>';
                timer += '<span class="number hour">' + self.addZero(hours) + '</span></span> ';
                timer += '<span class="number-wrapper"><div class="line"></div><div class="caption">MINS</div>';
                timer += '<span class="number min">' + self.addZero(minutes) + '</span></span> ';
                timer += '<span class="number-wrapper"><div class="line"></div><div class="caption">SECS</div>';
                timer += '<span class="number sec">' + self.addZero(seconds) + '</span></span>';
                document.getElementById("CDT").innerHTML = timer;

                if (distance < 0) {
                    clearInterval(x);
                    document.getElementById("CDT").innerHTML = document.getElementById('timesup').value;
                }

                quiz_page_time_now_value = new Date(quiz_page_time_now_value);
                quiz_page_time_now_value.setSeconds( quiz_page_time_now_value.getSeconds() + 1 );
            }, 1000);
        }        
    }

    this.addZero = function(num) {
        return ('0' + num).slice(-2);
    }

    this.initMembershipRenewForm = function () {
        $('.renew-membership').off();
        $('.renew-membership').on('click', function () {
            var modal = '#modal-beta';
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['renew_membership']);
            application.load('/account/membership/renew', modal+' .modal-body-container', function (result) {
                self.initPaypalLink();
                self.initStripeForm();
                self.initPaystackPayment();
                self.initRazorpayPayment();
                self.initSelectMembershipRadio();
                self.initSelectMembershipTag();
                self.initOfflinePaymentForm();
            });
        });
    };

    this.initPaypalLink = function () {
        $('.paypal-link').off();
        $('.paypal-link').on('click', function (e) {
            e.preventDefault();
            var selected = $('input[name=selected_package]:checked').val();
            self.openInNewTab(application.url+'/account/paypal-payment/'+selected);
        });
    };

    this.initStripeForm = function () {
        application.onSubmit('#stripe_payment_form', function (e) {
            self.stripePay($('#stripe_key').val());
        });
    }

    this.stripePay = function(key) {
        application.showLoader('stripe_payment_button');
        var valid = self.stripeCardValidation();
        if(valid == true) {
            Stripe.setPublishableKey(key);
            Stripe.createToken({
                number: $('#card_number').val(),
                cvc: $('#cvc').val(),
                exp_month: $('#month').val(),
                exp_year: $('#year').val()
            }, self.stripeResponseHandler);
            return false;
        }
    };

    this.stripeCardValidation = function() {
        var valid = true;
        var cardNumber = $('#card_number').val();
        var month = $('#month').val();
        var year = $('#year').val();
        var cvc = $('#cvc').val();
        $(".errors-container").remove();
        if (cardNumber.trim() == "" || month.trim() == "" || year.trim() == "" || cvc.trim() == "") {
            valid = false;
        }
        if(valid == false) {
            console.log(self.stripeErrors('All Field are required'));
            $('#stripe_payment_form').prepend(self.stripeErrors('All Field are required'));
            application.hideLoader('stripe_payment_button');
        }
        return valid;
    };

    this.stripeErrors = function (msg) {
        var html = '';
        html += '<div class="row errors-container">';
        html += '<div class="col-sm-12">';
        html += '<div class="alert alert-danger">';
        html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
        html += msg;
        html += '</div>';
        html += '</div>';
        html += '</div>';
        return html;
    };

    this.stripeResponseHandler = function(status, response) {
        if (response.error) {
            $('#stripe_payment_form').prepend(self.stripeErrors(response.error.message));
            application.hideLoader('stripe_payment_button');
        } else {
            var selected = $('input[name=selected_package]:checked').val();
            var token = response['id'];
            $('#stripe_payment_form').append("<input type='hidden' name='token' value='" + token + "' />");
            $('#stripe_payment_form').append("<input type='hidden' name='selected' value='"+selected+"' />");
            $('#stripe_payment_form').append("<input type='hidden' name='_token' value='"+application.csrf_token+"' />");
            application.post('/account/stripe-payment', '#stripe_payment_form', function (res) {
                var result = JSON.parse(application.response);
                if (result.success === 'true') {
                    $('#stripe_payment_form').find("input[type=text], textarea").val("");
                    location.reload();
                }
                application.hideLoader('stripe_payment_button');
                application.showMessages(result.messages, 'stripe_payment_form');
            });
        }
    };

    this.initPaystackPayment = function () {
        $('.pay-with-paystack').off();
        $('.pay-with-paystack').on('click', function (e) {
            e.preventDefault();
            let handler = PaystackPop.setup({
                key: $('#paystack-key').val(),
                email: $('#paystack-email').val(),
                amount: $('input[name=selected_package]:checked').data('price') * 100,
                ref: ''+Math.floor((Math.random() * 1000000000) + 1),
                onClose: function() {},
                callback: function(response){
                    let reference = response.reference;
                    let selected = $('input[name=selected_package]:checked').val();
                    $('#paystack_payment_form').append("<input type='hidden' name='selected' value='"+selected+"' />");
                    $('#paystack_payment_form').append("<input type='hidden' name='response' value='"+reference+"' />");
                    $('#paystack_payment_form').append("<input type='hidden' name='_token' value='"+application.csrf_token+"' />");
                    application.post('/candidate-paystack-payment', '#paystack_payment_form', function (result) {                    
                        var result = JSON.parse(application.response);
                        $('.paystack-success-container').html(result.messages);
                        if (result.success == 'true') {
                            setTimeout(location.reload(), 5000);
                        }
                    });                    
                }
            });
            handler.openIframe();
        });
    }

    this.initRazorpayPayment = function () {
        $('.pay-with-razorpay').off();
        $('.pay-with-razorpay').on('click', function (e) {
            var amount = $('input[name=selected_package]:checked').data('price'); //Multiply by hundred from paisa to rupees
            var description = $('input[name=selected_package]:checked').data('title');
            var selected = $('input[name=selected_package]:checked').val();
            var data = {amount:amount, _token:$('#razorpay-token').val(), selected: selected};
            $('#razorpay_payment_form').append("<input type='hidden' name='amount' value='"+amount+"' />");
            $('#razorpay_payment_form').append("<input type='hidden' name='description' value='"+description+"' />");
            $('#razorpay_payment_form').append("<input type='hidden' name='selected' value='"+selected+"' />");
            $('#razorpay_payment_form').append("<input type='hidden' name='amount' value='"+amount+"' />");
            $('#razorpay_payment_form').append("<input type='hidden' name='_token' value='"+application.csrf_token+"' />");
            application.post('/candidate-razorpay-order', '#razorpay_payment_form', function (res) {
                var result = application.response;
                var options = {
                    "key": result.other_key,
                    "amount": result.order_amount,
                    "currency": result.order_currency,
                    "name": result.other_name,
                    "description": description,
                    "image": result.other_image,
                    "order_id": result.order_id,
                    "_token": application.csrf_token,
                    "callback_url": application.url+'/candidate-razorpay-verify',
                    "prefill": {
                        "name": result.other_prefill_name,
                        "email": result.other_prefill_email,
                        "contact": result.other_prefill_phone
                    },
                    "notes": {"selected": selected},
                    "theme": {"color": "#3399cc"}
                };
                const razor_request = new Razorpay(options);
                razor_request.open();
                e.preventDefault();
            });
        });
    }

    this.initSelectMembershipRadio = function () {
        $('input.membership-radio').on('change', function(event){
            $('.em-title').removeClass('em-selected');
            var item = $(this).data('key');
            $('.'+item).addClass('em-selected');
        });
        var pageLoadKey = $('input[name=selected_package]:checked').data('key');
        $('.'+pageLoadKey).addClass('em-selected');
        $("#"+pageLoadKey+'-key').prop("checked", true);
        $("#"+pageLoadKey+'-key').attr("checked", true);        
    };

    this.initSelectMembershipTag = function () {
        $('.renew-package').on('click', function(e) {
            var key = $(this).data('key');
            $("#"+key).prop("checked", true);
            $("#"+key).attr("checked", true);
            $('.em-title').removeClass('em-selected');
            $(this).addClass('em-selected');
        });
    };

    this.initOfflinePaymentForm = function () {
        application.onSubmit('#offline_payment_form', function (result) {
            application.showLoader('offline_payment_form_button');
            var selected = $('input[name=selected_package]:checked').val();
            $('#offline_payment_form').append("<input type='hidden' name='selected' value='"+selected+"' />");
            $('#offline_payment_form').append("<input type='hidden' name='_token' value='"+application.csrf_token+"' />");
            application.post('/account/offline-payment', '#offline_payment_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('offline_payment_form_button');
                application.showMessages(result.messages, 'offline_payment_form');
                if (result.success == 'true') {
                    setTimeout(location.reload(), 2000);
                }
            });
        });
    };

    this.openInNewTab = function (url) {
        var win = window.open(url, '_blank');
    }    

    this.initDualListBox = function() {
        $('.select2').select2();
    }
}

$(document).ready(function() {
    var account = new Account();

    //General
    account.initDropify();
    account.initOpenCloseResumeSections();
    account.initDotMenu();
    account.initSettingsUpdate();
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
    account.initResumeSaveSkill();
    account.initResumeSaveAchievement();
    account.initResumeSaveReference();
    account.initRemoveSection();
    account.initAddSection();
    account.initResumePlusMinus();
    account.initDefaultFieldForResumeSections();    
    account.initJobApply();    
    account.initQuizTimer();    
    account.initMembershipRenewForm();    
    account.initDualListBox(); 
});
