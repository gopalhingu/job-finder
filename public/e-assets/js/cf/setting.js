function Setting() {

    "use strict";

    var self = this;

    this.initGeneralSettingUpdate = function () {
        application.onSubmit('#employer_settings_general_form', function (result) {
            application.showLoader('employer_settings_general_form_button');
            application.post('/employer/settings/save-general', '#employer_settings_general_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('employer_settings_general_form_button');
                application.showMessages(result.messages, 'employer_settings_general_form');
            });
        });
    };

    this.initBrandingSettingUpdate = function () {
        application.onSubmit('#employer_settings_branding_form', function (result) {
            application.showLoader('employer_settings_branding_form_button');
            application.post('/employer/settings/save-branding', '#employer_settings_branding_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('employer_settings_branding_form_button');
                application.showMessages(result.messages, 'employer_settings_branding_form');
            });
        });
    };

    this.initEmailsSettingUpdate = function () {
        application.onSubmit('#employer_settings_emails_form', function (result) {
            application.showLoader('employer_settings_emails_form_button');
            application.post('/employer/settings/save-emails', '#employer_settings_emails_form', function (res) {
                var result = JSON.parse(application.response);
                application.hideLoader('employer_settings_emails_form_button');
                application.showMessages(result.messages, 'employer_settings_emails_form');
            });
        });
    };

    this.initCssSettingUpdate = function () {
        var element = document.getElementById("css-editor");
        if (element) {
            var editor = CodeMirror.fromTextArea(element, {
                lineNumbers: true,
                matchBrackets: true,
                lineWrapping: true,
                tabSize: 4
            });
            application.onSubmit('#employer_css_form', function (result) {
                $('#editor-hidden').val(editor.getValue());
                application.showLoader('employer_css_form_button');
                application.post('/employer/settings/save-css', '#employer_css_form', function (res) {
                    var result = JSON.parse(application.response);
                    application.hideLoader('employer_css_form_button');
                    application.showMessages(result.messages, 'employer_css_form');
                });
            });
        }        
    };

    this.initPlugins = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
        $('.dropify').dropify();
    };

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
}

$(document).ready(function() {
    var setting = new Setting();
    setting.initGeneralSettingUpdate();
    setting.initBrandingSettingUpdate();
    setting.initEmailsSettingUpdate();
    setting.initCssSettingUpdate();
    setting.initPlugins();
    setting.initCKEditorFiveClassic("banner-text");

    if ($('#enable_editor_for_email_templates').val() == 'yes') {
        setting.initCKEditorFiveClassic("candidate_job_app");
        setting.initCKEditorFiveClassic("employer_job_app");
        setting.initCKEditorFiveClassic("employer_interview_assign");
        setting.initCKEditorFiveClassic("candidate_interview_assign");
        setting.initCKEditorFiveClassic("candidate_quiz_assign");
        setting.initCKEditorFiveClassic("team_creation");
    }
});