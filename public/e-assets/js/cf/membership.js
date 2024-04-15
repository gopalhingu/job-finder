function Membership() {

    "use strict";

    var self = this;

    this.initMembershipsDatatable = function () {
        $('#memberships_datatable').DataTable({
            "aaSorting": [[ 5, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0,7]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/employer/memberships/data',
                "data": function ( d ) {
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initMembershipRenewForm();
                    $('.table-bordered').parent().attr('style', 'overflow:auto'); //For responsive
                },
            },
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'info': true,
            'autoWidth': false,
            'destroy':true,
            'stateSave': true,
            'responsive': false
        });
    };

    this.initPaypalLink = function () {
        $('.paypal-link').off();
        $('.paypal-link').on('click', function (e) {
            e.preventDefault();
            var selected = $('input[name=selected_package]:checked').val();
            self.openInNewTab(application.url+'/employer/paypal-payment/'+selected);
        });
    };

    this.initSelectMembershipRadio = function () {
        $('input.membership-radio').on('ifChecked', function(event){
            $('.em-title').removeClass('em-selected');
            var item = $(this).data('key');
            $('.'+item).addClass('em-selected');
        });
        var selectedOnLoad = $('input[name=selected_package]:checked').data('key');
        $('.'+selectedOnLoad).addClass('em-selected');
    };

    this.initSelectMembershipTag = function () {
        $('.renew-package').on('click', function() {
            var key = $(this).data('key');
            $('input.'+key).iCheck('check');
            $(this).addClass('em-selected');
        });
    };

    this.openInNewTab = function (url) {
        var win = window.open(url, '_blank');
    }

    this.initMembershipRenewForm = function () {
        $('.renew-membership').off();
        $('.renew-membership').on('click', function () {
            var modal = '#modal-default';
            $(modal).modal('show');
            $(modal+' .modal-title').html(lang['renew_membership']);
            application.load('/employer/memberships/renew', modal+' .modal-body-container', function (result) {
                self.initPaypalLink();
                self.initStripeForm();
                self.initPaystackPayment();
                self.initRazorpayPayment();
                self.initiCheck();
                self.initSelectMembershipRadio();
                self.initSelectMembershipTag();
                self.initOfflinePaymentForm();
            });
        });
    };

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
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
            application.post('/employer/stripe-payment', '#stripe_payment_form', function (res) {
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

    this.initOfflinePaymentForm = function () {
        application.onSubmit('#offline_payment_form', function (result) {
            application.showLoader('offline_payment_form_button');
            var selected = $('input[name=selected_package]:checked').val();
            $('#offline_payment_form').append("<input type='hidden' name='selected' value='"+selected+"' />");
            application.post('/employer/offline-payment', '#offline_payment_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('offline_payment_form_button');
                application.showMessages(result.messages, 'offline_payment_form');
                setTimeout(location.reload(), 2000);
            });
        });
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
                    $('#stripe_payment_form').append("<input type='hidden' name='selected' value='"+selected+"' />");
                    $('#stripe_payment_form').append("<input type='hidden' name='response' value='"+reference+"' />");
                    application.post('/paystack-payment', '#stripe_payment_form', function (result) {                    
                        var result = JSON.parse(application.response);
                        $('.paystack-success-container').html(result.messages);
                        setTimeout(location.reload(), 5000);
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
            application.post('/razorpay-order', data, function (res) {
                var result = application.response;
                var options = {
                    "key": result.other_key,
                    "amount": result.order_amount,
                    "currency": result.order_currency,
                    "name": result.other_name,
                    "description": description,
                    "image": result.other_image,
                    "order_id": result.order_id,
                    "callback_url": application.url+'/razorpay-verify',
                    "prefill": {
                        "name": result.other_prefill_name,
                        "email": result.other_prefill_email,
                        "contact": result.other_prefill_phone
                    },
                    "notes": {"selected": selected},
                    "theme": {"color": "#3399cc"}
                };
                const rzp1 = new Razorpay(options);
                rzp1.open();
                e.preventDefault();
            });
        });
    }
}

$(document).ready(function() {
    var membership = new Membership();
    membership.initMembershipsDatatable();
});