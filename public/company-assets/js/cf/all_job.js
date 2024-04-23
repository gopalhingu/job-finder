function Job() {

    "use strict";

    var self = this;
    var job_filters = {};

    this.initFilters = function () {
        $("#category, #company").off();
        $("#category, #company").on('change', function () {
            self.initJobsDatatable();
        });
        /* $('.job-filter').each(function(i,v) {
            $(this).on('change', function () {
                job_filters[$(this).attr('id')] = $(this).val();
                self.initJobsDatatable();
            });
        }); */
        $('.select2').select2();
    };

    this.initJobsDatatable = function () {
        $('#jobs_datatable').DataTable({
            "aaSorting": [[ 5, 'desc' ]],
            "columnDefs": [{"orderable": false, "targets": [0]}],
            "lengthMenu": [[10, 25, 50, 100000000], [10, 25, 50, "All"]],
            "searchDelay": 2000,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "type": "POST",
                "url": application.url+'/company/all-jobs/data',
                "data": function ( d ) {
                    d.category = $('#category').val();
                    d.company = $('#company').val();
                    d._token = application._token;
                },
                "complete": function (response) {
                    self.initiCheck();
                    self.initAllCheck();
                    $('.table-bordered').parent().attr('style', 'overflow:auto'); //For responsive
                },
            },
            'paging': true,
            'lengthChange': true,
            'searching': true,
            'info': true,
            'autoWidth': true,
            'destroy':true,
            'stateSave': true
        });
    };

     this.initAllCheck = function () {
        $('input.all-check').on('ifChecked', function(event){
            $('input.single-check').iCheck('check');
        });
        $('input.all-check').on('ifUnchecked', function(event){
            $('input.single-check').iCheck('uncheck');
        });
    }; 

    this.initJobsListBulkActions = function () {
        $('.job-bulk-post').off();
        $('.job-bulk-post').on('click', function (e) {
            e.preventDefault();
            var ids = [];
            var idss = [];
            var addPostJobArray = [];
            $('.addedPostJob').each(function (i, v) {
                addPostJobArray.push($(this).data('id'));
            });
            $('.single-check').each(function (i, v) {
                if ($(this).is(':checked')) {
                    var jobArrayChecking = $.inArray($(this).data('id'), addPostJobArray);
                    if(jobArrayChecking === -1){
                        ids.push($(this).data('id'));
                    }else{
                        idss.push($(this).data('id'));
                    }
                }
            });
            if (ids.length === 0) {

                if(idss.length != 0){
                    alert(['This selected field already added']);
                }else{
                    alert(lang['please_select_some_records_first']);
                }
                $('.job-bulk-post').val('');
                return false;
                
            } else {
                added(ids);
            }
        });
    }; 

    this.initiCheck = function () {
        $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
          checkboxClass: 'icheckbox_minimal-blue',
          radioClass   : 'iradio_minimal-blue'
        });
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

}

$(document).ready(function() {
    var job = new Job();

    //For both screens
    job.initiCheck();

    //For listing screen
    job.initFilters();
    job.initJobsDatatable();
    job.initJobsListBulkActions();
});

function added(id) {
// console.log(id.length);
// return;
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to add this job!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, add it!"
    }).then((result) => {
        if (result.isConfirmed) {
            id.forEach(function(id) {
                callTransfer(id);
            });
        }
    });
}

function callTransfer(id) {

    $.ajax({
        type: "POST",
        url: application.url+'/company/job/transfer',
        data: {id},
        complete: function (response) {
            console.log(response);
            var job = new Job();
            job.initJobsDatatable();
            return response.status;
        },
    });
}
