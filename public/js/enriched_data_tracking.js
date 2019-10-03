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

// Methods
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function round(value, precision) {
    var multiplier = Math.pow(10, precision || 0);
    return Math.round(value * multiplier) / multiplier;
}

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
            title: 'Enriched Records From BSA (Daily) ' + months[month -1] + ' ' + year,
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
    $("#moreDetailDailyTable tbody").empty();
    for(var item in data.data) {
        console.log(data.data[item]["day"])
        $("#moreDetailDailyTable tbody").append(`
        <tr>
            <td>${data.data[item]["day"]}</td>
            <td>${numberWithCommas(data.data[item]["sent"])}</td>
            <td>${numberWithCommas(data.data[item]["received"])}</td>
            <td>${round(data.data[item]["received"]/data.data[item]["sent"] * 100, 2)}%</td>
        </tr>
        `);
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
    $("#moreDetailMonthlyTable tbody").empty();
    for(var item in data.data) {
        $("#moreDetailMonthlyTable tbody").append(`
        <tr>
            <td>${months[data.data[item]["month"] -1]}</td>
            <td>${numberWithCommas(data.data[item]["sent"])}</td>
            <td>${numberWithCommas(data.data[item]["received"])}</td>
            <td>${round(data.data[item]["received"]/data.data[item]["sent"] * 100, 2)}%</td>
        </tr>
        `);
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

        $("#moreDetailDailyTable tbody").html('<tr><td colspan="4"><div class="loading">loading</div></td></tr>');

        $("#moreDetailMonthlyTable tbody").html('<tr><td colspan="4"><div class="loading">loading</div></td></tr>');

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