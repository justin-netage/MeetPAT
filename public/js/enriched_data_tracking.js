var months = new Array();

    months[0] = "January";
    months[1] = "February";
    months[2] = "March";
    months[3] = "April";
    months[4] = "May";
    months[5] = "June";
    months[6] = "July";
    months[7] = "August";
    months[8] = "September";
    months[9] = "October";
    months[10] = "November";
    months[11] = "December";

var year = new Date().getFullYear();
var month = new Date().getMonth() + 1;

var drawDataCountChartDay = function(data) {
    google.charts.load('current', {'packages':['bar']});

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() { 
        
        var result = Object.keys(data["data"]).map(function(key) {
            return [data["data"][key]["day"].toString(), parseInt(data["data"][key]["sent"]) ,parseInt(data["data"][key]["received"])];
        });
        result.unshift(['Day', 'Sent', 'Recieved']);
        
        var chart_data = new google.visualization.arrayToDataTable(result);

        var options = {
        title: 'Enriched Records From BSA (Day) ' + months[month -1] + ' ' + year,
        curveType: 'function',
        legend: { position: 'bottom' },
        width: "80%",
        height: 256,
        backgroundColor: '#f7f7f7',
        titleTextStyle: {    
            bold: true,       
        }
        };

        var chart = new google.charts.Bar(document.getElementById('chart-container-day'));
        chart.draw(chart_data, google.charts.Bar.convertOptions(options));

    }
}

var drawDataCountChartMonth = function(data) {

    google.charts.load('current', {'packages':['bar']});

    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {       
        
        var result = Object.keys(data["data"]).map(function(key) {
            return [months[data["data"][key]["month"] -1], parseInt(data["data"][key]["sent"]) ,parseInt(data["data"][key]["received"])];
        });
        result.unshift(['Month', 'Sent', 'Recieved']);
        
        var chart_data = new google.visualization.arrayToDataTable(result);

        var options = {
            title: 'Enriched Records From BSA (Monthly) ' + year,
            curveType: 'function',
            legend: { position: 'bottom' },
            width: "80%",
            height: 256,
            backgroundColor: '#f7f7f7',
            titleTextStyle: {    
                bold: true,       
            }
        };

        var chart = new google.charts.Bar(document.getElementById('chart-container-monthly'));
        chart.draw(chart_data, google.charts.Bar.convertOptions(options));

    }
}

$(document).ready(function() {
    
    var drawGraphs = function() {
        
        $("#chart-container-monthly").html(`<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`);

        $("#chart-container-day").html(`<div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>`);
        $.ajax({
            url: "/api/meetpat-admin/enriched-data-tracked-monthly",
            type: "GET",
            data: {year: year, month: month},
            success: function(data) {
                drawDataCountChartMonth(data);
                console.log(data);
                $(window).resize(function(){
                    drawDataCountChartMonth(data);
                  });
            }
        })
        .done(function() {
        })
        .fail(function(error) {
            console.log(error);
        });
    
        $.ajax({
            url: "/api/meetpat-admin/enriched-data-tracked-day",
            type: "GET",
            data: {year: year, month: month},
            success: function(data) {
                console.log(data);
                drawDataCountChartDay(data);
    
                $(window).resize(function(){
                    drawDataCountChartDay(data);
                  });
            }
        })
        .done(function() {
        })
        .fail(function(error) {
            console.log(error);
        });
        
    }

    $("#yearSelect, #monthSelect").on('change', function() {
        year = $("#yearSelect :selected").val();
        month = $("#monthSelect :selected").val();

    });

    $("#graph-filter-button").click(function() {
        drawGraphs();
    });

    drawGraphs();
    
});