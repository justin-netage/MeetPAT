// Load Google Chart Library

google.charts.load('current', {'packages':['corechart', 'geochart', 'bar'],
'mapsApiKey': 'AIzaSyBMae5h5YHUJ1BdNHshwj_SmJzPe5mglwI'});

function kFormatter(num) {
    return num > 999 ? (num/1000).toFixed(1) + 'k' : num.toString()
}

function drawProvinceChart( chart_data ) {

    $("#province-graph .spinner-block").hide();    

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Province');
    data.addColumn('number', 'Records');
    data.addColumn({type: 'string', role: 'annotation'});

    var result = Object.keys(chart_data).map(function(key) {

        var province;
        
        switch(key) {
            case 'G':
                province = ['Gauteng', chart_data[key], kFormatter(chart_data[key])];
                break;
            case 'EC':
                province = ['Eastern Cape', chart_data[key], kFormatter(chart_data[key])];
                break;
            case 'NC':
                province = ['Northern Cape', chart_data[key], kFormatter(chart_data[key])];
                break;
            case 'FS':
                province = ['Free State', chart_data[key],kFormatter(chart_data[key])];
                break;
            case 'L':
                province = ['Limpopo', chart_data[key], kFormatter(chart_data[key])];
                break;
            case 'KN':
                province = ['KwaZulu Natal', chart_data[key], kFormatter(chart_data[key])];
                break;
            case 'M':
                province = ['Mpumalanga', chart_data[key], kFormatter(chart_data[key])];
                break;
            case 'NW':
                province = ['North West', chart_data[key], kFormatter(chart_data[key])];
                break;
            case 'WC':
                province = ['Western Cape', chart_data[key], kFormatter(chart_data[key])];
                break;
            default:
                province = [key, chart_data[key], kFormatter(chart_data[key])];
            }
    
        return province;
        });
        //console.log(result);
        data.addRows(result);
        var chart_options = {
            'width':'100%',
            'fontSize': 12,
            'chartArea': {
                width: '60%',
                height: '100%'
            },
            'legend': {
                position: 'none'
            },
            'backgroundColor': '#fff',
            'colors': ['#3490DC'],
            'animation': {
                'startup':true,
                'duration': 1000,
                'easing': 'out'
            }
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('provincesChart'));
        chart.draw(data, chart_options);  
}

function drawAreaChart(  ) {

    $.post('/api/meetpat-client/get-records/areas', {user_id: user_id_number}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data )
    }).done(function( chart_data ) {
        $("#area-graph .spinner-block").hide();    
        //console.log(data);
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Area');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data).map(function(key) {
            return [key, chart_data[key], kFormatter(chart_data[key])];
            });
    
        data.addRows(result);
        // Set chart options
        var chart_options = {
                        'width':'100%',
                        'height': result.length * 20,
                        'fontSize': 12,
                        'chartArea': {
                            width: '60%',
                            height: '100s%'
                        },
                        'colors': ['#3490DC'],
                        'legend': {
                            position: 'none'
                        },
                        'backgroundColor': '#fff'
                        };
    
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('areasChart'));
        chart.draw(data, chart_options); 
    });
       

    // Create the data table.
    
  }

  var drawMunicipalityChart = function ( chart_data ) {
        $("#municipality-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Municipality');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data).map(function(key) {
            return [key, chart_data[key], kFormatter(chart_data[key])];
          });

            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '60%',
                                height: '100%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('municipalityChart'));
            chart.draw(data, chart_options);                
  }

  var drawMapChart = function ( chart_data ) {
    $("#map-graph .spinner-block").hide();    
    var result = Object.keys(chart_data).map(function(key) {
    var value;
        switch(key) {
            case 'G':
            value =  ['ZA-GT', chart_data[key]];
                break;
            case 'WC':
            value =  ['ZA-WC', chart_data[key]];
                break;
            case 'EC':
            value =  ['ZA-EC', chart_data[key]];
                break;
            case 'M':
            value =  ['ZA-MP', chart_data[key]];
                break;  
            case 'FS':
            value =  ['ZA-FS', chart_data[key]];
                break;
            case 'L':
            value =  ['ZA-LP', chart_data[key]];
                break;  
            case 'KN':
            value =  ['ZA-NL', chart_data[key]];
                break; 
            case 'NW':
            value =  ['ZA-NW', chart_data[key]];
                break;      
            case 'NC':
            value =  ['ZA-NC', chart_data[key]];
                break;
            default:
                value = "";               
            }

            
            return value;
    
      });
      
      result.unshift(['Provinces', 'Popularity']);
      var filtered = result.filter(function (el) {
        return el != "";
      });

      var data = google.visualization.arrayToDataTable(filtered);

      var options = {
          region:'ZA',resolution:'provinces',
          'backgroundColor': '#fff',
          'colorAxis': {colors: ['#039be5']}
        };

      var chart = new google.visualization.GeoChart(document.getElementById('chartdiv'));

      chart.draw(data, options);
  }

  var drawAgeChart = function (  ) {

    $.post('/api/meetpat-client/get-records/ages', {user_id: user_id_number}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );

    }).done(function( chart_data ) {
        $("#age-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Age');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data).map(function(key) {
            return [key, chart_data[key], kFormatter(chart_data[key])];
          });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '60%',
                                height: '100%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('agesChart'));
            chart.draw(data, chart_options);     
    });

               
    }

    var drawGenderChart = function() {
        $.post('/api/meetpat-client/get-records/genders', {user_id: user_id_number}, function(chart_data) {

        }).fail(function( chart_data ) {
            console.log( chart_data )
        }).done(function( chart_data ) {
            $("#gender-graph .spinner-block").hide();    

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Gender');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});

            var result = Object.keys(chart_data).map(function(key) {
                return [key, chart_data[key], kFormatter(chart_data[key])];
            });
        
                data.addRows(result);
                // Set chart options
                var chart_options = {
                                'width':'100%',
                                'fontSize': 12,
                                'chartArea': {
                                    width: '60%',
                                    height: '75%'
                                    },
                                'colors': ['#3490DC'],
                                'animation': {
                                    'startup':true,
                                    'duration': 1000,
                                    'easing': 'out'
                                },
                                'legend': {
                                    position: 'none'
                                },
                                'backgroundColor': '#fff'
                            };
            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('genderChart'));
                chart.draw(data, chart_options);     
        });
    }

    var drawPopulationChart = function() {
        $.post('/api/meetpat-client/get-records/population-groups', {user_id: user_id_number}, function( chart_data ) {

        }).fail(function( chart_data ) {
            console.log( chart_data );

        }).done(function( chart_data ) {
            $("#population-graph .spinner-block").hide();    

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Group');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});

            var result = Object.keys(chart_data).map(function(key) {
                return [key, chart_data[key], kFormatter(chart_data[key])];
            });
        
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('populationGroupChart'));
                chart.draw(data, chart_options);     
        });
    }

    var drawGenerationChart = function() {

        $.post('/api/meetpat-client/get-records/generations', {user_id: user_id_number}, function( chart_data ) {

        }).fail(function( chart_data ) {
            console.log( chart_data );
        }).done(function( chart_data ) {
            $("#generation-graph .spinner-block").hide();    

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Generation');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});

            var result = Object.keys(chart_data).map(function(key) {
                return [key, chart_data[key], kFormatter(chart_data[key])];
            });
        
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('generationChart'));
                chart.draw(data, chart_options);

        });

    }

var drawCitizensChart = function() {
    $.post('/api/meetpat-client/get-records/citizens-and-residents', {user_id: user_id_number}, function( chart_data ) {

    }).fail(function( chart_data ) {

    }).done(function( chart_data ) {
        $("#c-vs-v-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Citizen or Resident');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data).map(function(key) {
            return [key, chart_data[key], kFormatter(chart_data[key])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('citizensVsResidentsChart'));
            chart.draw(data, chart_options);    
    });
}

var drawMaritalStatusChart = function() {
    $.post('/api/meetpat-client/get-records/marital-statuses', {user_id: user_id_number}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#marital-status-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Marital Status');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data).map(function(key) {
            return [key, chart_data[key], kFormatter(chart_data[key])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('maritalStatusChart'));
            chart.draw(data, chart_options);    
    });
}

var drawHomeOwnerChart = function() {
    $.post('/api/meetpat-client/get-records/home-owner', {user_id: user_id_number}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#home-owner-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Home Owner Status');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data).map(function(key) {
            return [key, chart_data[key], kFormatter(chart_data[key])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('homeOwnerChart'));
            chart.draw(data, chart_options);    
    });
}

var drawRiskCategoryChart = function() {
    $.post('/api/meetpat-client/get-records/risk-category', {user_id: user_id_number}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );

    }).done(function( chart_data ) {
        $("#risk-category-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Age');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data).map(function(key) {
            return [key, chart_data[key], kFormatter(chart_data[key])];
          });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '50%',
                                height: '100%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('riskCategoryChart'));
            chart.draw(data, chart_options);     
    });
}

var drawHouseholdIncomeChart = function() {
    $.post('/api/meetpat-client/get-records/household-income', {user_id: user_id_number}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );

    }).done(function( chart_data ) {
        $("#income-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Income');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data).map(function(key) {
            return [key, chart_data[key], kFormatter(chart_data[key])];
          });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '40%',
                                height: '100%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('householdIncomeChart'));
            chart.draw(data, chart_options);     
    });    
}

var drawDirectorOfBusinessChart = function() {
    
    $.post('/api/meetpat-client/get-records/director-of-business', {user_id: user_id_number}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#directors-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Director of Business');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data).map(function(key) {
            return [key, chart_data[key], kFormatter(chart_data[key])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 12,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            'colors': ['#3490DC'],
                            'animation': {
                                'startup':true,
                                'duration': 1000,
                                'easing': 'out'
                            },
                            'legend': {
                                position: 'none'
                            },
                            'backgroundColor': '#fff'
                        };
        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('directorOfBusinessChart'));
            chart.draw(data, chart_options);    
    });
}

var user_id_number = $("#user_id").val();

var get_records_count =  function(records_data) {
        
    var records_count = $("#records-main-toast .toast-body");
    var records_toast = $("#records-toast .toast-body");
        
    $.post("/api/meetpat-client/get-records/count", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        //console.log(data)
    }).done(function(data) {
        //console.log(data);
        records_count.html(kFormatter(data));
        records_toast.html(kFormatter(data));
        $("#contacts-number .spinner-block").hide();

    });
}   

get_records_count();

var get_municipalities = function() {

    $.post("/api/meetpat-client/get-records/municipalities", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        //console.log(data)
    }).done(function(data) {
        //console.log(data);
        drawMunicipalityChart(data);
        get_ages();
    });

}

var get_provinces = function() {

    $.post("/api/meetpat-client/get-records/provinces", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        //console.log(data)
    }).done(function(data) {
        //console.log(data);
        drawProvinceChart(data);
        drawMapChart(data);
        get_municipalities();
    });

}

var get_ages = function() {
     
    drawAgeChart();
    get_genders();
}

var get_genders = function() {

    drawGenderChart();
    get_population_groups();
}

var get_population_groups = function() {

    drawPopulationChart();
    get_generations();
}

var get_generations = function() {

    drawGenerationChart();
    get_citizens_and_residents();
}

var get_home_owner = function() {

        drawHomeOwnerChart();
        get_risk_category();
}

var get_household_income = function() {

        drawHouseholdIncomeChart();
        get_director_of_business();
}

var get_risk_category = function() {

    drawRiskCategoryChart();
    get_household_income();
}

var get_director_of_business = function() {

    drawDirectorOfBusinessChart();
    drawAreaChart();


}

var get_citizens_and_residents = function() {

        drawCitizensChart();
        get_marital_statuses();

}

var get_marital_statuses = function() {

        drawMaritalStatusChart();
        get_home_owner();
}

$(document).ready(function() {
    //var site_url = window.location.protocol + "//" + window.location.host;
    $('#records-main-toast').toast('show');
    $("#records-toast").toast('show');
    get_provinces();
});