function Menu() {

    "use strict";

    var self = this;

    this.initAlignmentDropDown = function() {
        $('#alignment-dropdown').off();
        $('#alignment-dropdown').on('change', function() {
            var value = $(this).val();
            self.loadList(value);
            self.loadItemsForDelete(value);
        });
    }

    this.initMenusDropDown = function() {
        $('#menu-dropdown').off();
        $('#menu-dropdown').on('change', function() {
            var value = $(this).val();
            if (value == 'select_page' || value == 'select_news') {
                $('.submenu-dropdown-container').show();
                self.loadSubItems(value);
                $('.static-link-container').hide();
            } else if (value == 'static_external') {
                $('.static-link-container').show();
                $('.submenu-dropdown-container').hide();
                $('#submenu-dropdown').val('');
            } else {
                $('.static-link-container').hide();
                $('.submenu-dropdown-container').hide();
                $('#submenu-dropdown').val('');                
            }
        });
    }

    this.loadSubItems = function(menu_item_id) {
        application.load('/admin/menus/sub-menu/'+menu_item_id, '', function (result) {
            var options = '';
            var result = application.response;
            $(result).each(function(i, v) {
                var id = menu_item_id == 'select_page' ? v.page_id : v.news_id;
                options += '<option value="'+id+'">'+v.title+'</option>';
            });
            $('#submenu-dropdown').html(options);
        });        
    }

    this.loadList = function(alignment) {
        application.load('/admin/menus/list/'+alignment, '', function (result) {
            $('#menu-list').html(application.response);
            self.initMenuSorting();
        });        
    }

    this.loadItemsForDelete = function(alignment) {
        application.load('/admin/menus/list-for-delete/'+alignment, '', function (result) {
            var options = '';
            var result = application.response;
            $('#delete-dropdown').html(result);
        });        
    }

    this.initAddToMenu = function() {
        $('#add-to-menu').off();
        $('#add-to-menu').on('click', function(){
            var data = {
                menu_item_id: $('#menu-dropdown').val(),
                sub_item_id: $('#submenu-dropdown').val(),
                title: $('#static-external-title').val(),
                link: $('#static-external-link').val(),
                alignment: $('#alignment-dropdown').val()
            };
            application.post('/admin/menus/save', data, function (result) {
                var result = JSON.parse(application.response);
                $('#message-container').html(result.messages);
                self.loadList($('#alignment-dropdown').val());
            }, function (error) {
            });
        });
    }

    this.initMenuSorting = function (type) {
        var updateOutput = function (e) {
            var list = e.length ? e : $(e.target), output = list.data('output');
            application.post('/admin/menus/order-update', {list: list.nestable('serialize')}, function (result) {
            }, function (error) {
            });
        };

        $('#nestable').nestable({
            group: 1,
            maxDepth: 3,
        }).on('change', updateOutput);
    };

    this.initMenuDelete = function() {
        $('#delete-from-menu').on('click', function(){
            var menu_item_id = $('#delete-dropdown').val();
            var status = confirm(lang['are_u_sure']);
            if (status === true) {
                application.load('/admin/menus/delete/'+menu_item_id, '', function (result) {
                    self.loadList($('#alignment-dropdown').val());
                    self.loadItemsForDelete($('#alignment-dropdown').val());
                });
            }
        });
    }

    this.initSelect2 = function() {
        $('.select2').select2();
        $('.submenu-dropdown-container').hide();
        $('.static-link-container').hide();
        $('[data-toggle="tooltip"]').tooltip();
    }

}

$(document).ready(function() {
    var menu = new Menu();
    menu.initAlignmentDropDown();
    menu.initMenusDropDown();
    menu.initSelect2();
    menu.initAddToMenu();
    menu.initMenuDelete();
    menu.loadList('left');
    menu.loadItemsForDelete('left');
});
