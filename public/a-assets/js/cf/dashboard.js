function Dashboard() {

    "use strict";

    var self = this;

    this.initFilterDropDowns = function () {
        $('#sales-data-dd').on('change', function() {
            self.initSalesChart($(this).val());
        });
        $('#signups-data-dd').on('change', function() {
            self.initSignupsChart($(this).val());
        });
    };

    this.initSalesChart = function (selected) {
        var data = {selected : selected ? selected : 'this_month'};
        var existing = document.getElementById('sales-chart');
        if (existing) {
            application.post("/admin/dashboard/sales-data", data, function (res) {
                var res = JSON.parse(application.response);
                self.loadSalesChart(res.labels, res.values);
            });
        }
    };

    this.loadSalesChart = function(labels, values) {
        var ticksStyle = {fontColor: '#495057', fontStyle: 'bold'};
        var mode = 'index';
        var intersect = true;        
        var signupsChart = new Chart($('#sales-chart'), {
            data: {
                labels: labels,
                datasets: [{
                    type: 'line',
                    data: values,
                    backgroundColor: 'transparent',
                    borderColor: '#007bff',
                    pointBorderColor: '#007bff',
                    pointBackgroundColor: '#007bff',
                    fill: false
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: mode,
                    intersect: intersect
                },
                hover: {mode: mode, intersect: intersect},
                legend: {display: false},
                scales: {
                    yAxes: [{
                        gridLines: {display: true, lineWidth: '4px', color: 'rgba(0, 0, 0, .2)', zeroLineColor: 'transparent'},
                        ticks: $.extend({beginAtZero: true, suggestedMax: 200}, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {display: false},
                        ticks: ticksStyle
                    }]
                }
            }
        });
    };

    this.initSignupsChart = function (selected) {
        var data = {selected : selected ? selected : 'this_month'};
        var existing = document.getElementById('signups-chart');
        if (existing) {
            application.post("/admin/dashboard/signups-data", data, function (res) {
                var res = JSON.parse(application.response);
                self.loadSignupsChart(res.labels, res.values.employers, res.values.candidates);
            });
        }
    };

    this.loadSignupsChart = function(labels, values_employers, values_candidates) {
        var ticksStyle = {fontColor: '#495057', fontStyle: 'bold'};
        var mode = 'index';
        var intersect = true;
        var salesChart = new Chart($('#signups-chart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {backgroundColor: '#007bff', borderColor: '#007bff', data: values_employers}, 
                    {backgroundColor: '#ced4da', borderColor: '#ced4da', data: values_candidates}
                ]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {mode: mode, intersect: intersect},
                hover: {mode: mode, intersect: intersect},
                legend: {display: false},
                scales: {
                    yAxes: [{
                        gridLines: {
                            display: true,
                            lineWidth: '4px',
                            color: 'rgba(0, 0, 0, .2)',
                            zeroLineColor: 'transparent'
                        },
                        ticks: $.extend({
                            beginAtZero: true,
                            callback: function (value) {
                                if (value >= 1000) {
                                    value /= 1000
                                    value += 'k'
                                }
                                return  value
                            }
                        }, ticksStyle)
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                        display: false
                    },
                        ticks: ticksStyle
                    }]
                }
            }
        });

    };
}

$(document).ready(function() {
    var dashboard = new Dashboard();
    dashboard.initSalesChart();
    dashboard.initSignupsChart();
    dashboard.initFilterDropDowns();
});
