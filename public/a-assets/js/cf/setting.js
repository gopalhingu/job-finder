function Setting() {

    "use strict";

    var self = this;
    self.templates = [
        "candidate_signup",
        "candidate_verify_email",
        "candidate_reset_password",
        "employer_signup",
        "employer_verify_email",
        "employer_reset_password",
        "employer_refer_job",
        "banner_text",
        "before_blogs_text",
        "after_blogs_text",
        "before_how_text",
        "after_how_text",
        "footer_col_1",
        "footer_col_2",
        "footer_col_3",
        "footer_col_4",
        "candidate_job_app",
        "employer_job_app",
        "employer_interview_assign",
        "candidate_interview_assign",
        "candidate_quiz_assign",
        "team_creation",
    ];
    self.editorFront = '';
    self.editorCandidate = '';

    this.initGeneralSettingUpdate = function () {
        application.onSubmit('#admin_settings_form', function (result) {
            application.showLoader('admin_settings_form_button');
            application.post('/admin/settings/general', '#admin_settings_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_settings_form_button');
                application.showMessages(result.messages, 'admin_settings_form');
            });
        });
    };

    this.initGeneralImageSettingUpdate = function () {
        application.onSubmit('#admin_general_images_settings_form', function (result) {
            application.showLoader('admin_general_images_settings_form_button');
            application.post('/admin/settings/general-images', '#admin_general_images_settings_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_general_images_settings_form_button');
                application.showMessages(result.messages, 'admin_general_images_settings_form');
            });
        });
    };

    this.initDisplaySettingUpdate = function () {
        application.onSubmit('#admin_display_settings_form', function (result) {
            $('#editor_hidden_front').val(self.editorFront.getValue());
            $('#editor_hidden_candidate').val(self.editorCandidate.getValue());
            application.showLoader('admin_display_settings_form_button');
            application.post('/admin/settings/display', '#admin_display_settings_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_display_settings_form_button');
                application.showMessages(result.messages, 'admin_display_settings_form');
            });
        });
    };

    this.initEmployerSettingUpdate = function () {
        application.onSubmit('#admin_employer_settings_form', function (result) {
            application.showLoader('admin_employer_settings_form_button');
            application.post('/admin/settings/employer', '#admin_employer_settings_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_employer_settings_form_button');
                application.showMessages(result.messages, 'admin_employer_settings_form');
            });
        });
    };

    this.initEmailSettingUpdate = function () {
        application.onSubmit('#admin_email_form', function (result) {
            application.showLoader('admin_email_form_button');
            application.post('/admin/settings/email', '#admin_email_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_email_form_button');
                application.showMessages(result.messages, 'admin_email_form');
            });
        });
    };

    this.initEmailTemplatesUpdate = function () {
        application.onSubmit('#admin_email_templates_form', function (result) {
            application.showLoader('admin_email_templates_form_button');
            application.post('/admin/settings/save-templates', '#admin_email_templates_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_email_templates_form_button');
                application.showMessages(result.messages, 'admin_email_templates_form');
            });
        });
    };

    this.initApisSettingUpdate = function () {
        application.onSubmit('#admin_apis_form', function (result) {
            application.showLoader('admin_apis_form_button');
            application.post('/admin/settings/apis', '#admin_apis_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_apis_form_button');
                application.showMessages(result.messages, 'admin_apis_form');
            });
        });
    };

    this.initHomeSettingUpdate = function () {
        application.onSubmit('#admin_home_form', function (result) {
            application.showLoader('admin_home_form_button');
            application.post('/admin/settings/home', '#admin_home_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('admin_home_form_button');
                application.showMessages(result.messages, 'admin_home_form');
            });
        });
    };

    this.initJobPortalVsSaasSettingUpdate = function () {
        application.onSubmit('#portal_vs_multitenancy_settings_form', function (result) {
            application.showLoader('portal_vs_multitenancy_settings_form_button');
            application.post('/admin/settings/job-portal-vs-saas', '#portal_vs_multitenancy_settings_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('portal_vs_multitenancy_settings_form_button');
                application.showMessages(result.messages, 'portal_vs_multitenancy_settings_form');
            });
        });
    };    

    this.uploadAdapter = function () {
        editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
            return new MyUploadAdapter( loader );
        };
    }

    this.initCKEditorFiveClassic = function (element_id) {
        var elementExists = document.getElementById(element_id);
        if (elementExists) {
            ClassicEditor.create(document.querySelector('#'+element_id), {
                extraPlugins: [ MyCustomUploadAdapterPlugin ],
                htmlSupport: {
                    allow: [{name: /.*/, attributes: true, classes: true, styles: true}],
                },
            }).then( editor => {
            }).catch( err => {
                console.error( err.stack );
            });
        }
    };

    this.initCKEditorFiveDecoupled = function (element_id) {
        var elementExists = document.getElementById(element_id);
        if (elementExists) {
            DecoupledEditor.create(document.querySelector('#'+element_id), {
                ckfinder: {
                    uploadUrl: application.url+'/ckeditor/image?command=QuickUpload&type=Files&responseType=json&_token='+application._token
                }
            }).then( editor => {
                var toolbarContainer = document.querySelector('.toolbar-'+element_id);
                toolbarContainer.prepend(editor.ui.view.toolbar.element);
                self.myEditors.push({'id' : element_id, 'editor' : editor});
            }).catch( error => {
                console.error( error );
            });
        }
    };

    this.initCKEditorFour = function (element_id) {
        var elementExists = document.getElementById(element_id);
        if (elementExists) {
            CKEDITOR.replace(element_id, {
                allowedContent : true,
                filebrowserUploadUrl: application.url+'/ckeditor/image?CKEditorFuncNum=1&_token='+application._token,
                filebrowserUploadMethod: 'form',
            });
        }
    };

    this.initPlugins = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
        //dropify
        $('.dropify').dropify();
        $('[data-toggle="tooltip"]').tooltip()
        //color picker with addon
        $('.colorpicker2').colorpicker();
        $('.colorpicker2').on('colorpickerChange', function(event) {
            $(this).find('.fa-square').css('color', event.color.toString());
        });
    };

    this.initCssSave = function () {
        var element = document.getElementById("css_editor_front");
        if (element) {
            self.editorFront = CodeMirror.fromTextArea(element, {
                lineNumbers: true,
                matchBrackets: true,
                lineWrapping: true,
                tabSize: 4
            });
        }
        var element = document.getElementById("css_editor_candidate");
        if (element) {
            self.editorCandidate = CodeMirror.fromTextArea(element, {
                lineNumbers: true,
                matchBrackets: true,
                lineWrapping: true,
                tabSize: 4
            });
        }
    };

}

$(document).ready(function() {
    var setting = new Setting();
    setting.initGeneralSettingUpdate();
    setting.initGeneralImageSettingUpdate();
    setting.initDisplaySettingUpdate();
    setting.initEmployerSettingUpdate();
    setting.initEmailSettingUpdate();
    setting.initEmailTemplatesUpdate();
    setting.initApisSettingUpdate();
    setting.initJobPortalVsSaasSettingUpdate();
    setting.initPlugins();
    setting.initCKEditorFiveClassic("home-banner-text");
    setting.initHomeSettingUpdate();
    setting.initCssSave();
    if ($('#enable_editor_for_email_templates').val() == 'yes') {
        $(setting.templates).each(function(i, v) {
            setting.initCKEditorFiveClassic(v);
        });
    }
});