// Load Google Chart Library
google.charts.load('current', {'packages':['corechart', 'geochart', 'bar'],
'mapsApiKey': 'AIzaSyBMae5h5YHUJ1BdNHshwj_SmJzPe5mglwI'});

// Selected Targets Arrays
var target_provinces = [];
var target_municipalities = [];
var target_areas = [];
var target_ages = [];
var target_genders = [];
var target_population_groups = [];
var target_generations = [];
var target_citizen_vs_residents = [];
var target_marital_statuses = [];
var target_home_owners = [];
var target_risk_categories = [];
var target_incomes = [];
var target_directors = [];

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
            'height': result.length * 25,
            'width':'100%',
            'fontSize': 10,
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

    $.get('/api/meetpat-client/get-records/areas', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( chart_data ) {

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
                        'height': result.length * 25,
                        'fontSize': 10,
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
        $(".apply-filter-button").prop("disabled", false);
        $('.apply-filter-button').html("apply");
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
                            'height': result.length * 25,
                            'width':'100%',
                            'fontSize': 10,
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

    $.get('/api/meetpat-client/get-records/ages', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );

    }).done(function( chart_data ) {
        $("#age-graph .spinner-block").hide();    
        $("#age_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Age');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["selected_ages"]).map(function(key) {
            return [key, chart_data["selected_ages"][key], kFormatter(chart_data["selected_ages"][key])];
          });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'height': result.length * 25,
                            'width':'100%',
                            'fontSize': 10,
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

                        for (var key in chart_data["all_ages"]) {
                            if(target_ages.includes(key)) {
                                $("#age_filter").append(
                                    '<input type="checkbox" name="' + key + '" id="' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            } else {
                                $("#age_filter").append(
                                    '<input type="checkbox" name="' + key + '" id="' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox"><label for="' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            }
                
                        }
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('agesChart'));
            chart.draw(data, chart_options);     
    });

               
    }

    var drawGenderChart = function() {
        $.get('/api/meetpat-client/get-records/genders', {user_id: user_id_number, selected_provinces: target_provinces,
             selected_age_groups: target_ages, selected_gender_groups: target_genders, 
             selected_population_groups: target_population_groups}, function(chart_data) {

        }).fail(function( chart_data ) {
            console.log( chart_data )
        }).done(function( chart_data ) {
            $("#gender-graph .spinner-block").hide();    
            $("#gender_filter").empty();

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Gender');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});

            var result = Object.keys(chart_data["selected_genders"]).map(function(key) {
                return [key, chart_data["selected_genders"][key], kFormatter(chart_data["selected_genders"][key])];
            });
        
                data.addRows(result);
                // Set chart options
                var chart_options = {
                                'width':'100%',
                                'fontSize': 10,
                                'chartArea': {
                                    width: '60%',
                                    height: '75%'
                                    },
                                vAxis: {
                                    minValue: 0,
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

                            for (var key in chart_data["all_genders"]) {
                                if(target_genders.includes(key)) {
                                    $("#gender_filter").append(
                                        '<input type="checkbox" name="g_' + key + '" id="g_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="g_' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                                    );
                                } else {
                                    $("#gender_filter").append(
                                        '<input type="checkbox" name="g_' + key + '" id="g_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox"><label for="g_' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                                    );
                                }
                    
                            }
            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('genderChart'));
                chart.draw(data, chart_options);     
        });
    }

    var drawPopulationChart = function() {
        $.get('/api/meetpat-client/get-records/population-groups', {user_id: user_id_number, selected_provinces: target_provinces,
             selected_age_groups: target_ages, selected_gender_groups: target_genders, 
             selected_population_groups: target_population_groups}, function( chart_data ) {

        }).fail(function( chart_data ) {
            console.log( chart_data );

        }).done(function( chart_data ) {
            $("#population-graph .spinner-block").hide();    
            $("#population_group_filter").empty();

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Group');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});

            var result = Object.keys(chart_data["selected_population_groups"]).map(function(key) {
                return [key, chart_data["selected_population_groups"][key], kFormatter(chart_data["selected_population_groups"][key])];
            });
        
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
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

                        for (var key in chart_data["all_population_groups"]) {
                            if(target_population_groups.includes(key)) {
                                $("#population_group_filter").append(
                                    '<input type="checkbox" name="' + key + '" id="' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            } else {
                                $("#population_group_filter").append(
                                    '<input type="checkbox" name="' + key + '" id="' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox"><label for="' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            }
                
                        }
            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('populationGroupChart'));
                chart.draw(data, chart_options);     
        });
    }

    var drawGenerationChart = function() {

        $.get('/api/meetpat-client/get-records/generations', {user_id: user_id_number, selected_provinces: target_provinces,
             selected_age_groups: target_ages, selected_gender_groups: target_genders, 
             selected_population_groups: target_population_groups}, function( chart_data ) {

        }).fail(function( chart_data ) {
            console.log( chart_data );
        }).done(function( chart_data ) {
            $("#generation-graph .spinner-block").hide();    
            $("#generation_filter").empty();

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Generation');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});

            var result = Object.keys(chart_data["selected_generations"]).map(function(key) {
                return [key, chart_data["selected_generations"][key], kFormatter(chart_data["selected_generations"][key])];
            });
        
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
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
                        for (var key in chart_data["all_generations"]) {
                            if(target_generations.includes(key)) {
                                $("#generation_filter").append(
                                    '<input type="checkbox" name="' + key + '" id="' + key.toLowerCase().replace(" ", "_") + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="' + key.toLowerCase().replace(" ", "_") + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            } else {
                                $("#generation_filter").append(
                                    '<input type="checkbox" name="' + key + '" id="' + key.toLowerCase().replace(" ", "_") + '_option' +'" value="' + key + '" class="css-checkbox"><label for="' + key.toLowerCase().replace(" ", "_") + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            }
                
                        }            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('generationChart'));
                chart.draw(data, chart_options);

        });

    }

var drawCitizensChart = function() {
    $.get('/api/meetpat-client/get-records/citizens-and-residents', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );
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
                            'fontSize': 10,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
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
    $.get('/api/meetpat-client/get-records/marital-statuses', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( chart_data ) {

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
                            'fontSize': 10,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
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
    $.get('/api/meetpat-client/get-records/home-owner', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( chart_data ) {

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
                            'fontSize': 10,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
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
    $.get('/api/meetpat-client/get-records/risk-category', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( chart_data ) {

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
                            'height': result.length * 25,
                            'width':'100%',
                            'fontSize': 10,
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
    $.get('/api/meetpat-client/get-records/household-income', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( chart_data ) {

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
                            'height': result.length * 25,
                            'width':'100%',
                            'fontSize': 10,
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
    
    $.get('/api/meetpat-client/get-records/director-of-business', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( chart_data ) {

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
                            'fontSize': 10,
                            'chartArea': {
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
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
        
    $.get("/api/meetpat-client/get-records/count", {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        //console.log(data);
        records_count.html(kFormatter(data));
        records_toast.html(kFormatter(data));
        $("#contacts-number .spinner-block").hide();

    });
}   

var get_municipalities = function() {

    $.get("/api/meetpat-client/get-records/municipalities", {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( data ) {
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
    // First get count. 
    get_records_count();

    $.get("/api/meetpat-client/get-records/provinces", {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        // console.log(data);
        $("#province_filter").empty();
        var get_province_name = function(code) {
            var province_name;

            switch(code) {
                case "G":
                    province_name = "Gauteng"
                    break;
                case "WC":
                    province_name = "Western Cape"
                    break;
                case "EC":
                    province_name = "Eastern Cape"
                    break;
                case "M":
                    province_name = "Mpumalanga"
                    break;
                case "NW":
                    province_name = "North West"
                    break;
                case "FS":
                    province_name = "Free State"
                    break;
                case "L":
                    province_name = "Limpopo"
                    break;
                case "KN":
                    province_name = "KwaZulu Natal"
                    break;
                case "NC":
                    province_name = "Northern Cape"
                    break;
                default:
                    province_name = "Unknown"
            }

            return province_name;
        }
        for (var key in data["all_provinces"]) {
            if(target_provinces.includes(key)) {
                $("#province_filter").append(
                    '<input type="checkbox" name="' + key + '" id="' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="' + key.toLowerCase() + '_option' +'" class="css-label">' + get_province_name(key) + '</label><br />'
                );
            } else {
                $("#province_filter").append(
                    '<input type="checkbox" name="' + key + '" id="' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox"><label for="' + key.toLowerCase() + '_option' +'" class="css-label">' + get_province_name(key) + '</label><br />'
                );
            }

        }

        drawProvinceChart(data["selected_provinces"]);
        drawMapChart(data["selected_provinces"]);
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

// Apply filters function

var apply_filters = function() {
    $("#province-graph .spinner-block").show(); $("#provincesChart").empty(); $("#province_filter").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#municipality-graph .spinner-block").show(); $("#municipalityChart").empty(); 
    $("#map-graph .spinner-block").show(); $("#chartdiv").empty(); 
    $("#area-graph .spinner-block").show(); $("#areasChart").empty(); 
    $("#age-graph .spinner-block").show(); $("#agesChart").empty(); $("#age_filter").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#gender-graph .spinner-block").show(); $("#genderChart").empty(); $("#gender_filter").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#population-graph .spinner-block").show(); $("#populationGroupChart").empty(); $("#population_group_filter").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#generation-graph .spinner-block").show(); $("#generationChart").empty(); $("#generation_filter").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#c-vs-v-graph .spinner-block").show(); $("#citizensVsResidentsChart").empty(); //$("#c-vs-v-filter-form").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#marital-status-graph .spinner-block").show(); $("#maritalStatusChart").empty(); //$("#marital-status-filter-form").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#home-owner-graph .spinner-block").show(); $("#homeOwnerChart").empty(); //$("#home-owner-filter-form").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#risk-category-graph .spinner-block").show();  $("#riskCategoryChart").empty(); //$("#risk-category-filter-form").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#income-graph .spinner-block").show(); $("#householdIncomeChart").empty(); //$("#household-income-filter-form").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#directors-graph .spinner-block").show(); $("#directorOfBusinessChart").empty(); //$("#directer-of-business-filter-form").html('<div class="text-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
    
    $("#records-main-toast .toast-body").html(
                        '<div class="d-flex justify-content-center">' +
                            '<div class="spinner-border text-primary" role="status">' +
                            '<span class="sr-only">Loading...</span>' +
                            '</div>' +
                        '</div>'
    );
    $("#records-toast .toast-body").html(
                        '<div class="d-flex justify-content-center">' +
                            '<div class="spinner-border text-primary" role="status">' +
                            '<span class="sr-only">Loading...</span>' +
                            '</div>' +
                        '</div>'
    );


}

$('.apply-filter-button').click(function() {
    $('.apply-filter-button').prop("disabled", true);
    $('.apply-filter-button').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;applying...');

    target_provinces = [];
    target_municipalities = [];
    target_areas = [];
    target_ages = [];
    target_genders = [];
    target_population_groups = [];
    target_generations = [];
    target_citizen_vs_residents = [];
    target_marital_statuses = [];
    target_home_owners = [];
    target_risk_categories = [];
    target_incomes = [];
    target_directors = [];

    $("#province-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_provinces.push($(this).val());
        }
    });

    $("#age-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_ages.push($(this).val());
        }
    });

    $("#gender-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_genders.push($(this).val());
        }
    });

    $("#population-group-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_population_groups.push($(this).val());
        }
    });

    $("#generation-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_generations.push($(this).val());
        }
    });

    apply_filters();
    get_provinces();
});

$(document).ready(function() {
    //var site_url = window.location.protocol + "//" + window.location.host;
    $('#records-main-toast').toast('show');
    $("#records-toast").toast('show');
    $('.dropdown-menu').on('click', function(e) {
        if($(this).hasClass('dropdown-menu-form')) {
            e.stopPropagation();
        }
    });

    get_provinces();

    
});