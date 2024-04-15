function General() {

    "use strict";

    var self = this;

    this.initShowHideFavicon = function () {
        $('.header-bar-btn').on('click', function() {
            var collapsed = $(document).find('.sidebar-collapse');
            if (collapsed.length > 0) {
                $('.brand-favicon').hide();
            } else {
                $('.brand-favicon').show();
            }
        });
    };

    this.showHideFaviconOnPageLoad = function () {
        var collapsed = $(document).find('.sidebar-collapse');
        if (collapsed.length > 0) {
            $('.brand-favicon').show();
        } else {
            $('.brand-favicon').hide();
        }
    };

}

$(document).ready(function() {
    var general = new General();
    general.initShowHideFavicon();
    general.showHideFaviconOnPageLoad();
});
