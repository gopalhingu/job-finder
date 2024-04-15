function General() {

    "use strict";

    var self = this;

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
    };

    this.initiCheckLogin = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass   : 'iradio_minimal-blue'
        });
    };

    this.initCKEditor = function (element) {
        element = element == '' ? 'description' : element;
        var elementExist = document.getElementById(element);
        if (elementExist) {
            CKEDITOR.replace(element, {
                allowedContent : true,
                filebrowserUploadUrl: application.url+'/ckeditor/image?CKEditorFuncNum=1&_token='+application._token,
                filebrowserUploadMethod: 'form',
            });
        }
    };

    this.initSettings = function () {
        self.initiCheck();
        $('.dropify').dropify();
        $('[data-toggle="tooltip"]').tooltip()
    };

    this.initSidebarToggle = function () {
        $('.sidebar-toggle').on('click', function () {
            application.load('/employer/sidebar-toggle', '', function (result) {});
        });
        $('.prevent-sidebar-toggle').on('click', function (e) {
            e.preventDefault();
            window.location = application.url+'/employer/memberships';
        });
    }

    this.initSelect2FlagDropdown = function () {
        //https://codepen.io/antonandoff/pen/PmQvBz
        var langArray = [];
        var selected = '';
        $('.alpha-language-selector-select option').each(function(){
            var img = $(this).attr("data-thumbnail");
            var text = this.innerText;
            var value = $(this).val();
            var item = '<li><img src="'+ img +'" alt="" value="'+value+'"/><span>'+ text +'</span></li>';
            langArray.push(item);
            if ($(this).is(':selected')) {
                selected = item;
            }
        });
        $('.alpha-language-selector-ul').html(langArray);
        $('.alpha-language-selector-btn').html(selected);
        $('.alpha-language-selector-btn').attr('value', 'en');
        $('.alpha-language-selector-ul li').click(function(){
            var img = $(this).find('img').attr("src");
            var value = $(this).find('img').attr('value');
            var text = this.innerText;
            var item = '<li><img src="'+ img +'" alt="" /><span>'+ text +'</span></li>';
            $('.alpha-language-selector-btn').html(item);
            $('.alpha-language-selector-btn').attr('value', value);
            $(".alpha-language-selector-b").toggle();
        });
        $(".alpha-language-selector-btn").click(function(){
            $(".alpha-language-selector-b").toggle();
        });
        $('.employer-lang-select li').on('click', function() {
            var id = $(this).find('img').attr('value');
            application.load('/set-employer-language/'+id, '', function (result) {});
            setTimeout(function() { 
                window.location.reload();
            }, 500);
        });
    }
}

$(document).ready(function() {
    var general = new General();
    general.initSidebarToggle();
    general.initSettings();
    general.initiCheckLogin();
    general.initSelect2FlagDropdown();
    general.initCKEditor('before-how');
    general.initCKEditor('after-how');
    general.initCKEditor('before-news');
    general.initCKEditor('after-news');
    general.initCKEditor('banner-text');

});
