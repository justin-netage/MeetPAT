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
            'colors': ['#00A3D9'],
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
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data )
    }).done(function( chart_data ) {
        
        $("#area-graph .spinner-block").hide();    
        $("#areaSubmitBtn").prop("disabled", false);
        $("#area_filter").append(
            '<div id="lunr-search" style="display: none;">'+
            '<input type="text" class="form-control mb-2" id="areaSearchInput" autocomplete="off" placeholder="search for area...">'+
            '<span style="position:absolute; right: 40px; top:35px;"><i class="fas fa-search"></i></span>'+
            '<ul id="lunr-results" class="list-unstyled"></ul>' +
            '</div>'
        );
        //console.log(data);
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Area');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["selected_areas"]).map(function(key) {
            return [key, chart_data["selected_areas"][key], kFormatter(chart_data["selected_areas"][key])];
            });
        var shorter_result = result.slice(0, 20);
        data.addRows(shorter_result);
        // Set chart options
        var chart_options = {
                        'width':'100%',
                        'height': shorter_result.length * 25,
                        'fontSize': 10,
                        'chartArea': {
                            width: '60%',
                            height: '100%'
                        },
                        'colors': ['#00A3D9'],
                        'legend': {
                            position: 'none'
                        },
                        'backgroundColor': '#fff'
                        };
    
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('areasChart'));
        chart.draw(data, chart_options); 

        var result = Object.keys(chart_data["all_areas"]).map(function(key) {
            return {"name": key, "count": kFormatter(chart_data["all_areas"][key])};
        });

        var documents = result;
        var idx = lunr(function() {
            this.ref('name');
            this.field('name');
            this.b(1);

            documents.forEach(function (doc) {
                this.add(doc)
            }, this) 
                
            
        });

        $("#lunr-search").show();
        $("#area-filter-form .text-center").remove();
        // Append checked inputs to hidden form...
        document.getElementById('areaSearchInput').addEventListener('keyup', function() {
            if(idx.search(this.value)) {
                $("#lunr-results").empty();

                idx.search(this.value).forEach(function(result) {
                    
                    if(result.score) {
                        if($('#area_hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').length) {
                            $("#lunr-results").append('<input type="checkbox" name="' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '" id="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option' +'" value="' + result.ref + '" class="css-checkbox" checked="checked"><label for="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option' +'" class="css-label">' + result.ref + '</label><br />');
                            $('#area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').click(function(){
                                
                                if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                                    $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '" id="area_hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option' +'" value="' + result.ref + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option' +'" class="css-label">' + result.ref + '<small> ' + kFormatter(chart_data["all_areas"][result.ref]) + '" checked="checked">');
                                    $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + ' i').click(function() {
                                        if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').length) {
                                            $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "")).remove();
                                            $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').remove();
                                            $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').prop("checked", false);
                                        }
            
                                    });
                                } else {
                                    
                
                                    if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, ""))) {
                                        $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "")).remove();
                                        $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').remove();
                                    }
                                }
                            });                        
                        } else {
                            $("#lunr-results").append('<input type="checkbox" name="' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '" id="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option' +'" value="' + result.ref + '" class="css-checkbox"><label for="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option' +'" class="css-label">' + result.ref + '<small> ' + kFormatter(chart_data["all_areas"][result.ref]) + '</small></label><br />');
                            $('#area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').click(function(){
                                
                                if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                                    $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '" id="area_hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option' +'" value="' + result.ref + '" checked="checked">');
                                    $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + ' i').click(function() {
                                        if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').length) {
                                            $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "")).remove();
                                            $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').remove();
                                            $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').prop("checked", false);
                                        }
            
                                    });
                                } else {
                                    
                
                                    if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, ""))) {
                                        $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "")).remove();
                                        $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\'/g, "") + '_option').remove();

                                    }
                                }
                            });                        
                        }
                    }
    
                });
            } else {
                $("#lunr-results").empty();
            }


        })
    });
       

    // Create the data table.
    
  }

  var drawMunicipalityChart = function ( chart_data ) {
        $("#municipality-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Municipality');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["selected_municipalities"]).map(function(key) {
            return [key, chart_data["selected_municipalities"][key], kFormatter(chart_data["selected_municipalities"][key])];
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
                            'colors': ['#00A3D9'],
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
                        
                        for (var key in chart_data["all_municipalities"]) {
                            if(target_municipalities.includes(key)) {
                                $("#municipality_filter").append(
                                    '<input type="checkbox" name="' + key.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '" id="municipality_' + key.toLowerCase().replace(/\./g, '_') + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="municipality_' + key.toLowerCase().replace(/\./g, '_') + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            } else {
                                $("#municipality_filter").append(
                                    '<input type="checkbox" name="' + key.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '" id="municipality_' + key.toLowerCase().replace(/\./g, '_') + '_option' +'" value="' + key + '" class="css-checkbox"><label for="municipality_' + key.toLowerCase().replace(/\./g, '_') + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            }

                            $('#municipality_' + key.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').click(function(){
                                if($('#municipality_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                
                                    $("#municipality_filters").append('<li id="filter_p_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_municipality_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + ' i').click(function() {
                                        if($('#age_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').length) {
                                            $('#filter_municipality_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_')).remove();
                                            $("#municipality_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').prop("checked", false);
                                        }

                                    });
                                } else {
                                    
                
                                    if($('#filter_municipality_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_'))) {
                                        $('#filter_municipality_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_')).remove();
                                    }
                                }
                            });
                        }
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('municipalityChart'));
            chart.draw(data, chart_options);     
            drawAreaChart();           
  }

  var drawMapChart = function ( chart_data ) {
    $("#map-graph .spinner-block").hide();    
    var result = Object.keys(chart_data).map(function(key) {
    var value;
        switch(key) {
            case 'G':
            value =  ['ZA-GT', chart_data[key], '<ul class="list-unstyled"><li><b>Gauteng</b></li><li>'+ kFormatter(chart_data[key]) +'</li></ul>'];
                break;
            case 'WC':
            value =  ['ZA-WC', chart_data[key], '<ul class="list-unstyled"><li><b>Western Cape</b></li><li>'+ kFormatter(chart_data[key]) +'</li></ul>'];
                break;
            case 'EC':
            value =  ['ZA-EC', chart_data[key], '<ul class="list-unstyled"><li><b>Eastern Cape</b></li><li>'+ kFormatter(chart_data[key]) +'</li></ul>'];
                break;
            case 'M':
            value =  ['ZA-MP', chart_data[key], '<ul class="list-unstyled"><li><b>Mpumalanga</b></li><li>'+ kFormatter(chart_data[key]) +'</li></ul>'];
                break;  
            case 'FS':
            value =  ['ZA-FS', chart_data[key], '<ul class="list-unstyled"><li><b>Free State</b></li><li>'+ kFormatter(chart_data[key]) +'</li></ul>'];
                break;
            case 'L':
            value =  ['ZA-LP', chart_data[key], '<ul class="list-unstyled"><li><b>Limpopo</b></li><li>'+ kFormatter(chart_data[key]) +'</li></ul>'];
                break;  
            case 'KN':
            value =  ['ZA-NL', chart_data[key], '<ul class="list-unstyled"><li><b>KwaZula Natal</b></li><li>'+ kFormatter(chart_data[key]) +'</li></ul>'];
                break; 
            case 'NW':
            value =  ['ZA-NW', chart_data[key], '<ul class="list-unstyled"><li><b>North West Province</b></li><li>'+ kFormatter(chart_data[key]) +'</li></ul>'];
                break;      
            case 'NC':
            value =  ['ZA-NC', chart_data[key], '<ul class="list-unstyled"><li><b>Northern Cape</b></li><li>'+ kFormatter(chart_data[key]) +'</li></ul>'];
                break;
            default:
                value = "";               
            }

            
            return value;
    
      });
      
      result.unshift(['Provinces', 'Popularity', {role: 'tooltip', p:{html:true}}]);
      var filtered = result.filter(function (el) {
        return el != "";
      });

      var data = google.visualization.arrayToDataTable(filtered);

      var options = {
          region:'ZA',resolution:'provinces',
          'backgroundColor': '#fff',
          'colorAxis': {colors: ['#039be5']},
          tooltip: {
            isHtml: true
        }
        };

      var chart = new google.visualization.GeoChart(document.getElementById('chartdiv'));

      chart.draw(data, options);

  }

  var drawAgeChart = function (  ) {

    $.get('/api/meetpat-client/get-records/ages', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( chart_data ) {

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
                            'colors': ['#00A3D9'],
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
                                    '<input type="checkbox" name="' + key.toLowerCase().replace(/ /g, "_").replace("+", "plus") + '" id="age_' + key.toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="age_' + key.toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            } else {
                                $("#age_filter").append(
                                    '<input type="checkbox" name="' + key.toLowerCase().replace(/ /g, "_").replace("+", "plus") + '" id="age_' + key.toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" value="' + key + '" class="css-checkbox"><label for="age_' + key.toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            }

                            $('#age_' + key.toLowerCase() + '_option').click(function(){
                                if($('#age_' + $(this).attr("name").toLowerCase() + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                
                                    $("#age_filters").append('<li id="filter_p_' + $(this).attr("name").toLowerCase() + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_age_' + $(this).val().toLowerCase() + ' i').click(function() {
                                        if($('#age_' + $(parent).attr("name").toLowerCase() + '_option').length) {
                                            $('#filter_age_' + $(parent).val().toLowerCase()).remove();
                                            $("#age_" + $(parent).val().toLowerCase() + '_option').prop("checked", false);
                                        }

                                    });
                                } else {
                                    
                
                                    if($('#filter_age_' + $(this).val().toLowerCase())) {
                                        $('#filter_age_' + $(this).val().toLowerCase()).remove();
                                    }
                                }
                            });
                        }
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('agesChart'));
            chart.draw(data, chart_options);     
    });

               
    }

    var drawGenderChart = function() {
        // get gender name
        var get_gender_name = function(short_name) {
            var name;
            switch(short_name) {
                case "M":
                    name = "Male";
                    break;
                case "F":
                    name = "Female";
                    break;
                default:
                    name = "Unkown";
            }

            return name;
        }
        $.get('/api/meetpat-client/get-records/genders', {user_id: user_id_number, selected_provinces: target_provinces,
             selected_age_groups: target_ages, selected_gender_groups: target_genders, 
             selected_population_groups: target_population_groups, selected_generations: target_generations,
             selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
             selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
             selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
             selected_municipalities: target_municipalities, selected_areas: target_areas}, function(chart_data) {

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
                                'colors': ['#00A3D9'],
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
                                        '<input type="checkbox" name="g_' + key + '" id="g_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="g_' + key.toLowerCase() + '_option' +'" class="css-label">' + get_gender_name(key) + '</label><br />'
                                    );
                                } else {
                                    $("#gender_filter").append(
                                        '<input type="checkbox" name="g_' + key + '" id="g_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox"><label for="g_' + key.toLowerCase() + '_option' +'" class="css-label">' + get_gender_name(key) + '</label><br />'
                                    );
                                }

                                $('#g_' + key.toLowerCase() + '_option').click(function(){
                                    if($('#g_' + $(this).val().toLowerCase() + '_option').is(":checked")) { 
                                        
                                        var parent = this;
                    
                                        $("#gender_filters").append('<li id="filter_g_' + $(this).val().toLowerCase() + '">'+ get_gender_name($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                                        $('#filter_g_' + $(this).val().toLowerCase() + ' i').click(function() {
                                            if($('#g_' + $(parent).val().toLowerCase() + '_option').length) {
                                                $('#filter_g_' + $(parent).val().toLowerCase()).remove();
                                                $("#g_" + $(parent).val().toLowerCase() + '_option').prop("checked", false);
                                            }

                                        });
                                    } else {
                                        
                    
                                        if($('#filter_g_' + $(this).val().toLowerCase())) {
                                            $('#filter_g_' + $(this).val().toLowerCase()).remove();
                                        }
                                    }
                                });
                    
                            }
            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('genderChart'));
                chart.draw(data, chart_options);     
        });
    }

    var drawPopulationChart = function() {

        // get ethnic name
        var get_ethnic_name = function(short_name) {
            var name;
            switch(short_name) {
                case "B":
                    name = "Black";
                    break;
                case "W":
                    name = "White";
                    break;
                case "C":
                    name = "Coloured";
                    break;
                case "A":
                    name = "Asian";
                    break;
                default:
                    name = "Unkown";
            }

            return name;
        }

        $.get('/api/meetpat-client/get-records/population-groups', {user_id: user_id_number, selected_provinces: target_provinces,
             selected_age_groups: target_ages, selected_gender_groups: target_genders, 
             selected_population_groups: target_population_groups, selected_generations: target_generations,
             selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
             selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
             selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
             selected_municipalities: target_municipalities, selected_areas: target_areas}, function( chart_data ) {

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
                            'colors': ['#00A3D9'],
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
                                    '<input type="checkbox" name="pop_' + key + '" id="pop_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="pop_' + key.toLowerCase() + '_option' +'" class="css-label">' + get_ethnic_name(key) + '</label><br />'
                                );
                            } else {
                                $("#population_group_filter").append(
                                    '<input type="checkbox" name="pop_' + key + '" id="pop_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox"><label for="pop_' + key.toLowerCase() + '_option' +'" class="css-label">' + get_ethnic_name(key) + '</label><br />'
                                );
                            }

                            $('#pop_' + key.toLowerCase() + '_option').click(function(){
                                if($('#pop_' + $(this).val().toLowerCase() + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                
                                    $("#population_group_filters").append('<li id="filter_pop_' + $(this).val().toLowerCase() + '">'+ get_ethnic_name($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_pop_' + $(this).val().toLowerCase() + ' i').click(function() {
                                        if($('#pop_' + $(parent).val().toLowerCase() + '_option').length) {
                                            $('#filter_pop_' + $(parent).val().toLowerCase()).remove();
                                            $("#pop_" + $(parent).val().toLowerCase() + '_option').prop("checked", false);
                                        }

                                    });
                                } else {
                                    
                
                                    if($('#filter_pop_' + $(this).val().toLowerCase())) {
                                        $('#filter_pop_' + $(this).val().toLowerCase()).remove();
                                    }
                                }
                            });
                
                        }
            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('populationGroupChart'));
                chart.draw(data, chart_options);     
        });
    }

    var drawGenerationChart = function() {

        $.get('/api/meetpat-client/get-records/generations', {user_id: user_id_number, selected_provinces: target_provinces,
             selected_age_groups: target_ages, selected_gender_groups: target_genders, 
             selected_population_groups: target_population_groups, selected_generations: target_generations,
             selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
             selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
             selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
             selected_municipalities: target_municipalities, selected_areas: target_areas}, function( chart_data ) {

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
                            'colors': ['#00A3D9'],
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
                                    '<input type="checkbox" name="gen_' + key + '" id="gen_' + key.toLowerCase().replace(/ /g, "_") + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="gen_' + key.toLowerCase().replace(/ /g, "_") + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            } else {
                                $("#generation_filter").append(
                                    '<input type="checkbox" name="gen_' + key + '" id="gen_' + key.toLowerCase().replace(/ /g, "_") + '_option' +'" value="' + key + '" class="css-checkbox"><label for="gen_' + key.toLowerCase().replace(/ /g, "_") + '_option' +'" class="css-label">' + key + '</label><br />'
                                );
                            }
                            $('#gen_' + key.toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                                if($('#gen_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                
                                    $("#generation_filters").append('<li id="filter_gen_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_gen_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                                        if($('#gen_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                            $('#filter_gen_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                            $("#gen_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                                        }

                                    });
                                } else {
                                    
                
                                    if($('#filter_gen_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                                        $('#filter_gen_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                                    }
                                }
                            });
                
                        }            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('generationChart'));
                chart.draw(data, chart_options);

        });

    }

var drawCitizensChart = function() {
    $.get('/api/meetpat-client/get-records/citizens-and-residents', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#c-vs-r-graph .spinner-block").hide();    
        $("#citizen_vs_resident_filter").empty();

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
                            'colors': ['#00A3D9'],
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
            if(target_citizen_vs_residents.includes("citizen")) {
                $('#citizen_vs_resident_filter').append(
                    '<input type="checkbox" name="citizen" id="citizen_option" value="citizen" class="css-checkbox" checked="checked"><label for="citizen_option" class="css-label">Citizen</label><br />'

                );
            } else {
                $('#citizen_vs_resident_filter').append(
                    '<input type="checkbox" name="citizen" id="citizen_option" value="citizen" class="css-checkbox"><label for="citizen_option" class="css-label">Citizen</label><br />'

                );
            }

            if(target_citizen_vs_residents.includes('resident')) {
                $("#citizen_vs_resident_filter").append(
                    '<input type="checkbox" name="resident" id="resident_option" value="resident" class="css-checkbox" checked="checked"><label for="resident_option" class="css-label">Resident</label><br />'
    
                    );
            } else {
                $("#citizen_vs_resident_filter").append(
                    '<input type="checkbox" name="resident" id="resident_option" value="resident" class="css-checkbox"><label for="resident_option" class="css-label">Resident</label><br />'
    
                    );
            }
            $('#citizen_option').click(function(){
                if($(this).is(":checked")) { console.log("checked: " + $(this).attr("name" ))};
            });

            $('#resident_option').click(function(){
                if($(this).is(":checked")) { console.log("checked: " + $(this).attr("name" ))};
            });

            $('#citizen_option').click(function(){
                if($('#citizen_option').is(":checked")) { 

                    $("#generation_filters").append('<li id="filter_citizen_option">Citizen<i class="fas fa-window-close float-right"></i></li>');
                    $('#filter_citizen_option i').click(function() {
                        if($('#filter_citizen_option')) {
                            $('#filter_citizen_option').remove();
                            $("#citizen_option").prop("checked", false);
                        }

                    });
                } else {

                    if($('#filter_citizen_option')){
                        $('#filter_citizen_option').remove();
                    }
                }
            });

            $('#resident_option').click(function(){
                if($('#resident_option').is(":checked")) { 

                    $("#generation_filters").append('<li id="filter_resident_option">Resident<i class="fas fa-window-close float-right"></i></li>');
                    $('#filter_resident_option i').click(function() {
                        if($('#filter_resident_option')) {
                            $('#filter_resident_option').remove();
                            $("#resident_option").prop("checked", false);
                        }

                    });
                } else {

                    if($('#filter_resident_option')){
                        $('#filter_resident_option').remove();
                    }
                }
            });

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('citizensVsResidentsChart'));
            chart.draw(data, chart_options);    
    });
}

var drawMaritalStatusChart = function() {
    $.get('/api/meetpat-client/get-records/marital-statuses', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas, selected_areas: target_areas}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#marital-status-graph .spinner-block").hide();    
        $("#marital_status_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Marital Status');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["selected_marital_status"]).map(function(key) {
            return [key, chart_data["selected_marital_status"][key], kFormatter(chart_data["selected_marital_status"][key])];
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
                            'colors': ['#00A3D9'],
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

            for (var key in chart_data["all_marital_status"]) {
                if(target_marital_statuses.includes(key)) {
                    $("#marital_status_filter").append(
                        '<input type="checkbox" name="m_' + key + '" id="m_' + key + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="m_' + key + '_option' +'" class="css-label">' + key + '</label><br />'
                    );

                } else {
                    $("#marital_status_filter").append(
                        '<input type="checkbox" name="m_' + key + '" id="m_' + key + '_option' +'" value="' + key + '" class="css-checkbox"><label for="m_' + key + '_option' +'" class="css-label">' + key + '</label><br />'
                    );
                }
                $('#m_' + key.toLowerCase() + '_option').click(function(){
                    if($('#m_' + $(this).val().toLowerCase() + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#marital_status_filters").append('<li id="filter_m_' + $(this).val().toLowerCase() + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_m_' + $(this).val().toLowerCase() + ' i').click(function() {
                            if($('#m_' + $(parent).val().toLowerCase() + '_option').length) {
                                $('#filter_m_' + $(parent).val().toLowerCase()).remove();
                                $("#m_" + $(parent).val().toLowerCase() + '_option').prop("checked", false);
                            }

                        });
                    } else {
                        
    
                        if($('#filter_m_' + $(this).val().toLowerCase())) {
                            $('#filter_m_' + $(this).val().toLowerCase()).remove();
                        }
                    }
                });
            }            
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('maritalStatusChart'));
            chart.draw(data, chart_options);    
    });
}

var drawHomeOwnerChart = function() {
    $.get('/api/meetpat-client/get-records/home-owner', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#home-owner-graph .spinner-block").hide();    
        $("#home_owner_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Home Owner Status');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["selected_home_owners"]).map(function(key) {
            return [key, chart_data["selected_home_owners"][key], kFormatter(chart_data["selected_home_owners"][key])];
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
                            'colors': ['#00A3D9'],
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
            for (var key in chart_data["all_home_owners"]) {
                if(target_home_owners.includes(key)) {
                    $("#home_owner_filter").append(
                        '<input type="checkbox" name="h_' + key + '" id="h_' + key + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="h_' + key + '_option' +'" class="css-label">' + key + '</label><br />'
                    );
                } else {
                    $("#home_owner_filter").append(
                        '<input type="checkbox" name="h_' + key + '" id="h_' + key + '_option' +'" value="' + key + '" class="css-checkbox"><label for="h_' + key + '_option' +'" class="css-label">' + key + '</label><br />'
                    );
                }
                
                $('#h_' + key.toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                    if($('#h_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#home_owner_filters").append('<li id="filter_h_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_h_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                            if($('#h_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                $('#filter_h_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                $("#h_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                            }

                        });
                    } else {
                        
    
                        if($('#filter_h_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                            $('#filter_h_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                        }
                    }
                });
            }              
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('homeOwnerChart'));
            chart.draw(data, chart_options);    
    });
}

var drawRiskCategoryChart = function() {
    $.get('/api/meetpat-client/get-records/risk-category', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );

    }).done(function( chart_data ) {
        $("#risk-category-graph .spinner-block").hide();    
        $("#risk_category_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Age');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["selected_risk_categories"]).map(function(key) {
            return [key, chart_data["selected_risk_categories"][key], kFormatter(chart_data["selected_risk_categories"][key])];
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
                            'colors': ['#00A3D9'],
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
            for (var key in chart_data["all_risk_categories"]) {
                if(target_risk_categories.includes(key)) {
                    $("#risk_category_filter").append(
                        '<input type="checkbox" name="r_' + key + '" id="r_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="r_' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                    );
                } else {
                    $("#risk_category_filter").append(
                        '<input type="checkbox" name="r_' + key + '" id="r_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox"><label for="r_' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                    );
                }

                $('#r_' + key.toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                    if($('#r_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#risk_category_filters").append('<li id="filter_r_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_r_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                            if($('#r_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                $('#filter_r_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                $("#r_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                            }

                        });
                    } else {
                        
    
                        if($('#filter_r_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                            $('#filter_r_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                        }
                    }
                });
    
            }        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('riskCategoryChart'));
            chart.draw(data, chart_options);     
    });
}

var drawHouseholdIncomeChart = function() {
    $.get('/api/meetpat-client/get-records/household-income', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );

    }).done(function( chart_data ) {
        $("#income-graph .spinner-block").hide();    
        $("#household_income_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Income');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["selected_household_incomes"]).map(function(key) {
            return [key, chart_data["selected_household_incomes"][key], kFormatter(chart_data["selected_household_incomes"][key])];
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
                            'colors': ['#00A3D9'],
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
            for (var key in chart_data["all_household_incomes"]) {
                if(target_incomes.includes(key)) {
                    $("#household_income_filter").append(
                        '<input type="checkbox" name="hi_' + key.toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '" id="hi_' + key.toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="hi_' + key.toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" class="css-label">' + key + '</label><br />'
                    );
                } else {
                    $("#household_income_filter").append(
                        '<input type="checkbox" name="hi_' + key.toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '" id="hi_' + key.toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" value="' + key + '" class="css-checkbox"><label for="hi_' + key.toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" class="css-label">' + key + '</label><br />'
                    );
                }

                $('#hi_' + key.toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option').click(function(){
                    if($('#hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#household_income_filters").append('<li id="filter_hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + ' i').click(function() {
                            if($('#hi_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option').length) {
                                $('#filter_hi_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus')).remove();
                                $("#hi_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option').prop("checked", false);
                            }

                        });
                    } else {
                        
    
                        if($('#filter_hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') )) {
                            $('#filter_hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') ).remove();
                        }
                    }
                });
    
            }              
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('householdIncomeChart'));
            chart.draw(data, chart_options);     
    });    
}

var drawDirectorOfBusinessChart = function() {
    
    $.get('/api/meetpat-client/get-records/director-of-business', {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#directors-graph .spinner-block").hide();    
        $("#directors_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Director of Business');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["selected_directors"]).map(function(key) {
            return [key, chart_data["selected_directors"][key], kFormatter(chart_data["selected_directors"][key])];
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
                            'colors': ['#00A3D9'],
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
            for (var key in chart_data["all_directors"]) {
                if(target_directors.includes(key)) {
                    $("#directors_filter").append(
                        '<input type="checkbox" name="d_' + key + '" id="d_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox" checked="checked"><label for="d_' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                    );
                } else {
                    $("#directors_filter").append(
                        '<input type="checkbox" name="d_' + key + '" id="d_' + key.toLowerCase() + '_option' +'" value="' + key + '" class="css-checkbox"><label for="d_' + key.toLowerCase() + '_option' +'" class="css-label">' + key + '</label><br />'
                    );
                }

                $('#d_' + key.toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                    if($('#d_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#directors_filters").append('<li id="filter_d_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_d_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                            if($('#d_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                $('#filter_d_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                $("#d_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                            }

                        });
                    } else {
                        
    
                        if($('#filter_d_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                            $('#filter_d_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                        }
                    }
                });
    
            }        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('directorOfBusinessChart'));
            chart.draw(data, chart_options);    
            $(".apply-filter-button").prop("disabled", false);
            $('.apply-filter-button').html("apply");
            $('#sidebarSubmitBtn').html('<i class="fas fa-sync-alt"></i>&nbsp;Apply Changes');
            $('#sidebarSubmitBtn').prop("disabled", false);
            $("#resetFilterToastBtn").prop("disabled", false);
            $("#audienceSubmitBtn").prop("disabled", false);
            $("#resetFilterToastBtn").html('<i class="fas fa-undo-alt"></i>&nbsp;Reset Filters');
    });
}

var user_id_number = $("#user_id").val();

var get_records_count =  function(records_data) {
        
    var records_count = $("#records-main-toast .toast-body");
    var records_count_toast = $("#records-toast .toast-body");
    var records_toast = $("#contacts-num-sidebar");
    var number_of_contacts = $("#numberOfContactsId");

    $.get("/api/meetpat-client/get-records/count", {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        //console.log(data);
        records_count.html(kFormatter(data));
        records_toast.html(kFormatter(data));
        records_count_toast.html(kFormatter(data));
        number_of_contacts.val(data);

        $("#contacts-number .spinner-block").hide();

    });
}   

var get_municipalities = function() {

    $.get("/api/meetpat-client/get-records/municipalities", {user_id: user_id_number, selected_provinces: target_provinces,
         selected_age_groups: target_ages, selected_gender_groups: target_genders, 
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        //console.log(data)
    }).done(function(data) {
        $("#municipality-graph .spinner-block").hide();    
        $("#municipality_filter").empty();
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
         selected_population_groups: target_population_groups, selected_generations: target_generations,
         selected_marital_status: target_marital_statuses, selected_home_owners: target_home_owners,
         selected_risk_categories: target_risk_categories, selected_household_incomes: target_incomes,
         selected_directors: target_directors, selected_citizen_vs_residents: target_citizen_vs_residents,
         selected_municipalities: target_municipalities, selected_areas: target_areas}, function( data ) {
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

            $('#' + key.toLowerCase() + '_option').click(function(){
                if($('#' + $(this).attr("name").toLowerCase() + '_option').is(":checked")) { 
                    
                    var parent = this;

                    $("#province_filters").append('<li id="filter_p_' + $(this).attr("name").toLowerCase() + '">'+ get_province_name($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                    $('#filter_p_' + $(this).val().toLowerCase() + ' i').click(function() {
                        if($('#' + $(parent).val().toLowerCase() + '_option').length) {
                            $('#filter_p_' + $(parent).val().toLowerCase()).remove();
                            $("#" + $(parent).val().toLowerCase() + '_option').prop("checked", false);
                        }

                    });
                } else {
                    

                    if($('#filter_p_' + $(this).val().toLowerCase())) {
                        $('#filter_p_' + $(this).val().toLowerCase()).remove();
                    }
                }
            });

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
    $("#province-graph .spinner-block").show(); $("#provincesChart").empty(); $("#province_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#municipality-graph .spinner-block").show(); $("#municipalityChart").empty(); 
    $("#map-graph .spinner-block").show(); $("#chartdiv").empty(); 
    $("#area-graph .spinner-block").show(); $("#areasChart").empty(); $("#area_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#age-graph .spinner-block").show(); $("#agesChart").empty(); $("#age_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#gender-graph .spinner-block").show(); $("#genderChart").empty(); $("#gender_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#population-graph .spinner-block").show(); $("#populationGroupChart").empty(); $("#population_group_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#generation-graph .spinner-block").show(); $("#generationChart").empty(); $("#generation_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#c-vs-r-graph .spinner-block").show(); $("#citizensVsResidentsChart").empty(); $("#citizen_vs_resident_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#marital-status-graph .spinner-block").show(); $("#maritalStatusChart").empty(); $("#marital_status_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#home-owner-graph .spinner-block").show(); $("#homeOwnerChart").empty(); $("#home_owner_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#risk-category-graph .spinner-block").show();  $("#riskCategoryChart").empty(); $("#risk_category_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#income-graph .spinner-block").show(); $("#householdIncomeChart").empty(); $("#household_income_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#directors-graph .spinner-block").show(); $("#directorOfBusinessChart").empty(); $("#directors_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    
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

$('.apply-filter-button, #sidebarSubmitBtn').click(function() {

    $('.apply-filter-button').prop("disabled", true);
    $('#sidebarSubmitBtn').prop("disabled", true);
    $('#audienceSubmitBtn').prop("disabled", true);
    $('.apply-filter-button').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;applying...');
    $('#sidebarSubmitBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Applying Changes...');
    $("#resetFilterToastBtn").prop("disabled", true);

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

    $("#marital-status-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_marital_statuses.push($(this).val());
        }
    });

    $("#home-owner-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_home_owners.push($(this).val());
        }
    });

    $("#risk-categories-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_risk_categories.push($(this).val());
        }
    });

    $("#household-income-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_incomes.push($(this).val());
        }
    });
    $("#directors-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_directors.push($(this).val());
        }
    });
    $("#citizen-vs-resident-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_citizen_vs_residents.push($(this).val());
        }
    });
    $("#municipality-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_municipalities.push($(this).val());
        }
    });
    $("#area-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_areas.push($(this).val());
        }
    });

    $("#provinceContactsId").val(target_provinces);
    $("#AgeContactsId").val(target_ages);
    $("#GenderContactsId").val(target_genders);
    $("#populationContactsId").val(target_population_groups);
    $("#generationContactsId").val(target_generations);
    $("#citizenVsResidentsContactsId").val(target_citizen_vs_residents);
    $("#maritalStatusContactsId").val(target_marital_statuses);
    $("#homeOwnerContactsId").val(target_home_owners);
    $("#riskCategoryContactsId").val(target_risk_categories);
    $("#houseHoldIncomeContactsId").val(target_home_owners);
    $("#directorsContactsId").val(target_directors);

    apply_filters();
    get_provinces();
});

$("#resetFilterToastBtn").click(function() {

    $("#resetFilterToastBtn").prop("disabled", true);
    $("#resetFilterToastBtn").html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
        + '&nbsp;Resetting...'
    );
    $('.apply-filter-button, .apply-changes-button').prop('disabled', true);
    $('.sidebar-filters ul li').remove();
    // Selected Targets Arrays
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

    $('input:checkbox').each(function(el) {
        if($(el).is(':checked')) {
            $(el).prop('checked', false);
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

    /** Sidebar toggling. */

    $('#sidebar-toggle-button').click(function() {
        if($(this).hasClass("sidebar-button-in")) {
            $(this).html('<i class="fas fa-arrow-right"></i>');
        } else {
            $(this).html('<i class="fas fa-cog"></i>');
        }

        $('#right-options-sidebar').toggleClass("sidebar-in");
        $('#sidebar-toggle-button').toggleClass("sidebar-button-in");
    });
 
    //   var idx = lunr(function () {
    //     this.ref('Area');
    //     this.field('GreaterArea');
      
    //     areas_list.forEach(function (doc) {
    //       this.add(doc)
    //     }, this)
    //   });

    //   lunr_result = idx.search("modularity");

    //   console.log(lunr_result);
});