function GeneralFunctions() {

    var self = this;

    this.initDatabaseForm = function () {
        application.onSubmit('#database_form', function (result) {
            application.showLoader('database_form_button');
            application.post(application.url+'/install/setupenv', '#database_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('database_form_button');
                application.showMessages(result.messages, 'message_container');
                if (result.success == 'true') {
                    window.location = application.url+'/install/setupdb';
                }
            });
        });
    };

    this.initUserForm = function () {
        application.onSubmit('#user_form', function (result) {
            application.showLoader('user_form_button');
            application.post(application.url+'/install/setupuser', '#user_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('user_form_button');
                application.showMessages(result.messages, 'message_container');
                if (result.success == 'true') {
                    window.location = $('#url').val();
                }
            });
        });
    };

}
var general = new GeneralFunctions();
general.initDatabaseForm();
general.initUserForm();