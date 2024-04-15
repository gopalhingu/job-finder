/**
 * ************
 *  Application 
 * ************
 */
function Application() {

    "use strict";

    var self = this;
    var host = window.location.origin;
    var dev_mode = true;
    var base_url = "";

    this.url = document.head.querySelector("[property~=route][content]").content;
    this.csrf_token = document.head.querySelector("[property~=token][content]").content;
    this.buttonText = "";
    this.buttonWidth = "";
    this.response = "";

    this.setBaseUrl = function (url) {
        if (isEmpty(url) == true) {
            base_url = host + "/";
        } else {
            base_url = url;
        }
    };

    this.init = function (url) {
        if (isEmpty(url) == false) {
            this.setBaseUrl(url);
        } else {
            this.setBaseUrl();
        }
        window.onload = function () {
            if (isEmpty(window.jQuery) == true) {
                console.log("Error: jQuery library missing.");
            }
        };
    };

    this.log = function (msg) {
        if (dev_mode == true) {
            console.log(msg);
        }
    };

    this.getBaseUrl = function () {
        if (isEmpty(base_url) == true) {
            base_url = "";
        }
        return base_url;
    };

    this.post = function (url, data, onSuccess, onError) {
        this.apiCall(url, 'POST', data, onSuccess, onError);
    };

    this.get = function (url, onSuccess, onError) {
        this.apiCall(url, 'GET', null, onSuccess, onError);
    };

    this.delete = function (url, onSuccess, onError) {
        this.apiCall(url, 'DELETE', null, onSuccess, onError);
    };

    this.put = function (url, data, onSuccess, onError) {
        if (isString(data) == true) {
            data = $(data).serialize();
        }
        this.apiCall(url, 'PUT', data, onSuccess, onError);
    };

    /**
     * ajax call
     * @param {*} url Request relative URL
     * @param {*} method Request method (GET,POST,PUT,DELETE,PATCH)
     * @param {*} request_data Ajax Request data
     * @param {*} callback  Response handler function
     */
    this.request = function (url, method, request_data, onSuccess, onError, onProgress) {

        if (isFullUrl(url) == false) {
            url = self.url + url;
        }

        if (isEmpty(request_data) == true) {
            request_data = null;
        }

        if (isString(request_data) === true) {
            request_data = new FormData($(request_data)[0]);
            request_data.append('csrf_token', self.csrf_token);
        } else if (isObject(request_data) === true) {
            var json = request_data;
            request_data = new FormData();
            request_data.append('data', JSON.stringify(json));
            request_data.append('csrf_token', self.csrf_token);
        }

        var ajax_params = {
            url: url,
            method: method,
            data: request_data,
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function (request) {},
            success: function (data) {
                self.response = data;
                callFunction(onSuccess, new ApiResponse(data));
            },
            error: function (xhr, status, error) {
                self.log("Error\n");
                self.log("Request url: " + url + "\n");
                self.log("Error details: " + error);
                self.log("Response: " + xhr.responseText);
                response = new ApiResponse();
                response.addError(error);
                callFunction(onError, response.getErrors());
            }
        };

        $.ajax(ajax_params);
    };

    this.apiCall = function (url, method, request_data, onSuccess, onError, auth_type, onProgress) {
        this.request(url, method, request_data, function (response) {
            if (response.hasError() == false) {
                var result = response.getResult();
                callFunction(onSuccess, result);
            } else {
                callFunction(onError, response.getErrors());
            }
        }, function (errors) {
            callFunction(onError, errors);
        }, onProgress);
    };

    this.getLoader = function (loader_type) {
        var code = "";
        switch (loader_type) {
            case 'icon':
                code = "<i class='fa fa-spin fa-spinner fa-2x'></i>";
                break;
            default:
                code = "<div class='text-center mt-40 app-loader'><i class='fa fa-spin fa-spinner fa-2x'></i></div>";
                break;
        }
        return code;
    };

    this.showHtml = function(element_id,content,show_type) {
        show_type = getDefaultValue(show_type,null);
        switch (show_type) {
            case 'prepend':
                $(element_id).prepend(content);
                return;
            case 'append':
                $(element_id).append(content);
                return;
            case 'replace':
                $(element_id).replaceWith(content);
                return;
        }
        $(element_id).html(content);
        return;
    };

    this.load = function (url, element_id, onSuccess, loader_type, show_type) {
        if (isEmpty(element_id) == true) {
            element_id = '#page_content';
        }
        var loader = this.getLoader(loader_type);
        $(element_id).addClass('loader');
        this.showHtml(element_id, loader, show_type);

        this.apiCall(url, 'GET', null, function (result) {
            $(element_id).removeClass('loader');
            self.showHtml(element_id, result, show_type);
            callFunction(onSuccess, result);
        }, function (error) {
            console.log(error);
        });
    };

    this.showLoader = function (button_id, width) {
        $('.errors-container').remove();
        self.buttonText = $('#' + button_id).html();
        self.buttonWidth = $('#' + button_id).width();
        self.buttonWidth = width !== "" ? width : self.buttonWidth+27;
        $('#' + button_id).attr('disabled', true);
        $('#' + button_id).css('width', self.buttonWidth);
        $('#' + button_id).html("<i class='fa fa-spin fa-spinner form-button-spinner'></i>");
    };

    this.hideLoader = function (button_id) {
        $('#' + button_id).attr('disabled', false);
        $('#' + button_id).html(self.buttonText);
    };

    this.showMessages = function(messages, element) {
        $('#'+element).prepend(messages);
    };

    this.onSubmit = function (form_id, onSuccess, onError) {
        $(form_id).off();
        $(form_id).on('submit', function (e) {
            e.preventDefault();
            callFunction(onSuccess);
        });
    };
}

/**
 * **************
 *  ApiResponse 
 * **************
 */
function ApiResponse(response) {

    "use strict";

    var status = "ok";
    var errors = [];
    var result = "";

    this.createEmpty = function () {
        status = "ok";
        errors = [];
        result = "";
    };

    this.init = function (response) {

        if (isEmpty(response) == true) {
            data = this.createEmpty();
            return;
        }
        if (isObject(response) == true) {
            data = response;
        } else {
            if (isJSON(response) == false) {
                errors = [];
                status = 'ok';
                result = response;
                return;
            }
            data = JSON.parse(response);
        }

        if (isEmpty(data.status) == false) {
            status = data.status;
        }
        if (isEmpty(data.errors) == false) {
            errors = data.errors;
        }
        if (isEmpty(data.result) == false) {
            result = data.result;
        }
    };

    var data = this.init(response);

    this.getResult = function () {
        return result;
    };

    this.getErrors = function () {
        return errors;
    };

    this.getError = function (callback) {
        for (var index = 0; index < errors.length; ++index) {
            callFunction(callback, errors[index]);
            if (isNaN(callback) == false) {
                if (isEmpty(errors[callback]) == false) {
                    return errors[callback];
                } else {
                    return false;
                }
            }
        }
        return true;
    };

    this.getStatus = function () {
        return status;
    };

    this.addError = function (error) {
        errors.push(error);
    };

    this.hasError = function () {
        return (isEmpty(errors) == false);
    };
}

var application = new Application();
var response = new ApiResponse();
$(document).ready(function () {
    application.init();
});

/**
 * ************************
 *  Miscellaneous Functions
 * ************************
 */
"use strict";
function isEmpty(variable) {
    if (variable === undefined) return true;
    if (variable === null) return true;
    if (variable === "") return true;
    if (isObject(variable) == true) {
        return $.isEmptyObject(variable);
    }
    if (isArray(variable) == true) {
        return (variable.length == 0);
    }
    return false;
}

function callFunction(function_name, params) {
    if (isFunction(function_name) == true) {
        return function_name(params);
    }
    return null;
}

function isJSON(json_string) {
    try {
        JSON.parse(json_string);
    } catch (e) {
        return false;
    }
    return true;
}

function getDefaultValue(variable, default_value) {
    if (isEmpty(variable) == true) {
        return default_value;
    }
    return variable;
}

function isFunction(variable) {
    if (typeof variable === 'function') return true;
    return false;
}

function isArray(variable) {
    return (variable.constructor === Array);
}

function isObject(variable) {
    if (variable === undefined) return true;
    if (variable === null) return true;
    return (typeof variable === 'object');
}

function isFullUrl(url) {
    return (url.indexOf('://') > 0 || url.indexOf('//') === 0);
}

function isString(variable) {
    if (typeof variable === 'string' || variable instanceof String) {
        return true;
    }
    return false;
}