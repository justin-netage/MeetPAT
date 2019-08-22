// Load Google Chart Library
google.charts.load('current', {'packages':['corechart', 'geochart', 'bar'],
'mapsApiKey': 'AIzaSyBMae5h5YHUJ1BdNHshwj_SmJzPe5mglwI'});

var keyChanger = function(key_name) {
    if(key_name == 'True' || key_name == 'true') {
        return 'Yes';
    } else if(key_name == 'False' || key_name == 'false') {
        return 'No';
    } else {
        return key_name;
    }
}

var keyChangerPrValBucket = function(key_name) {
    switch (key_name) {
        case 'R0 - R1 000 000':
            return 'R0K - R1M';
        case 'R1 000 000 - R2 000 000':
            return 'R1M - R2M';
            
        case 'R2 000 000 - R4 000 000':
            return 'R2M - R4M';
        case 'R4 000 000 - R6 000 000':
            return 'R4M - R6M';
        case 'R7 000 000+':
            return 'R7M +';
        default:
            return "Unknown";
    }
}

var keyChangerHsIncBucket = function(key_name) {
    switch (key_name) {
        case 'R0 - R2 500':
            return 'R0K - R2.5K';
        case 'R2 500 - R5 000':
            return 'R2.5K - R5K';
        case 'R5 000 - R10 000':
            return 'R5K - R10K';
        case 'R10 000 - R20 000':
            return 'R10K - R20K';
        case 'R20 000 - R30 000':
            return 'R20K - R30K';
        case 'R30 000 - R40 000':
            return 'R30K - R40K';
        case 'R40 000 +':
            return 'R40K +';
        default:
            return 'Unknown';

    }
}

var keyChangerGender = function(key_name) {
    switch (key_name) {
        case 'M':
            return 'Male';
        case 'F':
            return 'Female';
        default: 
            return 'Unknown';
    }
}

var keyChangerMaritalStatus = function(key_name) {
    if(key_name == "True" || key_name == "true") {
        return 'Married';
    } else if(key_name == "False" || key_name == "false") {
        return 'Not Married';
    } else {
        return key_name;
    }
}

var toggle_side_bar = function() {
    if($("#sidebar-toggle-button").hasClass("sidebar-button-in")) {
        $("#sidebar-toggle-button").html('<i class="fas fa-arrow-right"></i>');
    } else {
        $("#sidebar-toggle-button").html('<i class="fas fa-cog"></i>');
    }

    $('#right-options-sidebar').toggleClass("sidebar-in");
    $('#sidebar-toggle-button').toggleClass("sidebar-button-in");
}

var data_fetched = 0;

var update_progress = function() {
    if(data_fetched != 18) {
        data_fetched++;
    }
    $("#progress_popup .progress-bar").width(Math.round((data_fetched/18) * 100) + "%");
    $("#progress_popup .progress-bar").attr("aria-valuenow", Math.round((data_fetched/18) * 100))
    //console.log(data_fetched);
}

var hide_progress = function() {
    setTimeout(function() {
        $("#progress_popup").hide();
    },1000);    
}

// Saved Audience Files methods

var user_id_number = $("#user_id").val();

var current_page = 1;
var records_per_page = 5;

function changePage(page, data)
{
    var listing_table = document.getElementById("userSavedFiles");
    var page_span = document.getElementById("page_span");

    // Validate page
    if (page < 1) page = 1;
    if (page > numPages(data)) page = numPages(data);

    listing_table.innerHTML = "";

    for (var i = (page-1) * records_per_page; i < (page * records_per_page) && i < data.length; i++) {
        audience_file = data[i];
        
        $("#userSavedFiles").append(
            `<div class="col-9 mb-1" id="file_name_${audience_file.file_unique_name}">
            <input id="input_${audience_file.file_unique_name}" class="form-control" name="${audience_file.file_unique_name}" value="${audience_file.file_name}" readonly>
            </div>
            <div class="col-3 mb-1" id="file_actions_${audience_file.file_unique_name}">
                <div class="btn-group float-right" role="group" aria-label="Basic example">
                    <a type="button" id="download_${audience_file.file_unique_name}" href="${audience_file.link}" class="btn btn-light" download><i class="fas fa-file-download"></i></a>
                    <button type="button" id="edit_${audience_file.file_unique_name}" onclick="edit_file('${audience_file.file_unique_name}')" class="btn btn-light"><i class="far fa-edit"></i></button>
                    <button type="button" id="delete_${audience_file.file_unique_name}" onclick="delete_file('${audience_file.file_unique_name}','${audience_file.file_name}');" class="btn btn-danger delete_file_btn"><i class="fas fa-trash-alt"></i></button>
                </div>
            </div>`
        )                   

    }

    data.forEach(function(audience_file) {
        if($("#input_" + audience_file.file_unique_name).length == 0) {
            $("#userSavedFiles").append(
                `<div class="col-9 mb-1 d-none" id="file_name_${audience_file.file_unique_name}">
                <input id="input_${audience_file.file_unique_name}" class="form-control" name="${audience_file.file_unique_name}" value="${audience_file.file_name}" readonly>
                </div>
                <div class="col-3 mb-1 d-none" id="file_actions_${audience_file.file_unique_name}">
                    <div class="btn-group float-right" role="group" aria-label="Basic example">
                        <a type="button" id="download_${audience_file.file_unique_name}" href="${audience_file.link}" class="btn btn-light" download><i class="fas fa-file-download"></i></a>
                        <button type="button" id="edit_${audience_file.file_unique_name}" onclick="edit_file('${audience_file.file_unique_name}')" class="btn btn-light"><i class="far fa-edit"></i></button>
                        <button type="button" id="delete_${audience_file.file_unique_name}" onclick="delete_file('${audience_file.file_unique_name}','${audience_file.file_name}');" class="btn btn-danger delete_file_btn"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>`
            )
        }
    });
    

    if (page == 1) {
        document.getElementById("btn_prev_item").classList.add("disabled");
    } else {
        document.getElementById("btn_prev_item").classList.remove("disabled");
    }

    if (page == numPages(data)) {
        
        document.getElementById("btn_next_item").classList.add("disabled");
    } else {
        document.getElementById("btn_next_item").classList.remove("disabled");
    }

    page_span.innerHTML = page + " of " + numPages(data);
}

function numPages(data)
{
    return Math.ceil(data.length / records_per_page);
}

var edit_file = function(file_unique_name) {
    if($("#input_" + file_unique_name).attr("readonly"))
    {
        $("#input_" + file_unique_name).removeAttr("readonly");
    } else {
        $("#input_" + file_unique_name).attr("readonly", true);
    }
}

function find_duplicate_in_array(arra1) {
    var object = {};
    var result = [];

    arra1.forEach(function (item) {
      if(!object[item])
          object[item] = 0;
        object[item] += 1;
    })

    for (var prop in object) {
       if(object[prop] >= 2) {
           result.push(prop);
       }
    }

    return result;

}

var file_name_exists = function(file_name) {
    inputs_array = [];
    var dataArray = $("#savedAudiencesForm").serializeArray(), dataObj = {};

    $(dataArray).each(function(i, field) {
        inputs_array.push(field.value);
    })
        
    return inputs_array.includes(file_name);
}

var no_same_file_names = function() {
    inputs_array = [];
    var dataArray = $("#savedAudiencesForm").serializeArray(), dataObj = {};

    $(dataArray).each(function(i, field) {
        inputs_array.push(field.value);
    })
        
    return find_duplicate_in_array(inputs_array);
}

var delete_file = function(file_unique_name, file_name) {
    var confirmed = confirm("Are you sure that you want to delete: " + file_name + "?");
    $(".delete_file_btn").prop("disabled", true);
    $(".page-item").addClass("disabled");
    if(confirmed == true) {
        $("#delete_" + file_unique_name).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>`);
        $.post('/api/meetpat-client/delete-saved-audience-file', {user_id: user_id_number, file_unique_name: file_unique_name}, function(data) {
            $("#file_name_" + file_unique_name).remove();
            $("#file_actions_" + file_unique_name).remove();
            
            //console.log(data);
        }).fail(function(data) {
            $("#delete_" + file_unique_name).html(`<i class="fas fa-trash-alt"></i>`);
            $(".delete_file_btn").prop("disabled", false);
            $(".page-item").removeClass("disabled");
            console.log(data);
        }).done(function() {
            $("#delete_" + file_unique_name).html(`<i class="fas fa-trash-alt"></i>`);
            $(".delete_file_btn").prop("disabled", false);
            $(".page-item").removeClass("disabled");
            get_saved_audiences();
        });
    } 

    
}

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
var target_vehicle_owners = [];
var target_lsm_groups = [];
var target_property_valuations = [];
var target_property_count_buckets = [];

var checkForFilters = function() {
    var target_provinces_el = document.getElementById("province_filters") ;var target_municipalities_el = document.getElementById("municipality_filters");
    var target_areas_el = document.getElementById("area_filters") ;var target_ages_el = document.getElementById("age_filters");
    var target_genders_el = document.getElementById("gender_filters") ;var target_population_groups_el = document.getElementById("population_group_filters");
    var target_generations_el = document.getElementById("generation_filters") ;var target_citizen_vs_residents_el = document.getElementById("citizen_vs_resident_filters");
    var target_marital_statuses_el = document.getElementById("marital_status_filters") ;var target_home_owners_el = document.getElementById("home_owner_filters");
    var target_risk_categories_el = document.getElementById("risk_category_filters") ;var target_incomes_el = document.getElementById("household_income_filters");
    var target_directors_el = document.getElementById("directors_filters") ;var target_vehicle_owners_el = document.getElementById("vehicle_owner_filters");
    var target_lsm_group_el = document.getElementById("lsm_group_filters") ;var target_property_valuations_el = document.getElementById("property_valuation_filters");
    var target_property_count_buckets_el = document.getElementById("property_count_bucket_filters");

    if(
        target_provinces_el.childNodes.length > 1 || target_municipalities_el.childNodes.length > 1 ||
        target_areas_el.childNodes.length > 1 || target_ages_el.childNodes.length > 1 ||
        target_genders_el.childNodes.length > 1 || target_population_groups_el.childNodes.length > 1 ||
        target_generations_el.childNodes.length > 1 || target_citizen_vs_residents_el.childNodes.length > 1 ||
        target_marital_statuses_el.childNodes.length > 1 || target_home_owners_el.childNodes.length > 1 ||
        target_risk_categories_el.childNodes.length > 1 || target_incomes_el.childNodes.length > 1 ||
        target_directors_el.childNodes.length > 1 || target_vehicle_owners_el.childNodes.length > 1 ||
        target_lsm_group_el.childNodes.length > 1 || target_property_valuations_el.childNodes.length > 1 ||
        target_property_count_buckets_el.childNodes.length > 1
        ) { $("#no_filters").hide();} else { $("#no_filters").show();}

        if (target_provinces_el.childNodes.length > 1) {$("#province_filters").show()} else {$("#province_filters").hide()};
        if (target_municipalities_el.childNodes.length > 1) {$("#municipality_filters").show()} else {$("#municipality_filters").hide()};        
        if (target_areas_el.childNodes.length > 1) {$("#area_filters").show()} else {$("#area_filters").hide()};
        if (target_ages_el.childNodes.length > 1) {$("#age_filters").show()} else {$("#age_filters").hide()};
        if (target_genders_el.childNodes.length > 1) {$("#gender_filters").show()} else {$("#gender_filters").hide()};
        if (target_population_groups_el.childNodes.length > 1) {$("#population_group_filters").show()} else {$("#population_group_filters").hide()};        
        if (target_generations_el.childNodes.length > 1) {$("#generation_filters").show()} else {$("#generation_filters").hide()};
        if (target_citizen_vs_residents_el.childNodes.length > 1) {$("#citizen_vs_resident_filters").show()} else {$("#citizen_vs_resident_filters").hide()};
        if (target_marital_statuses_el.childNodes.length > 1) {$("#marital_status_filters").show()} else {$("#marital_status_filters").hide()};
        if (target_home_owners_el.childNodes.length > 1) {$("#home_owner_filters").show()} else {$("#home_owner_filters").hide()};
        if (target_risk_categories_el.childNodes.length > 1) {$("#risk_category_filters").show()} else {$("#risk_category_filters").hide()};
        if (target_incomes_el.childNodes.length > 1) {$("#household_income_filters").show()} else {$("#household_income_filters").hide()};
        if (target_directors_el.childNodes.length > 1) {$("#directors_filters").show()} else {$("#directors_filters").hide()};
        if (target_vehicle_owners_el.childNodes.length > 1) {$("#vehicle_owner_filters").show()} else {$("#vehicle_owner_filters").hide()};
        if (target_lsm_group_el.childNodes.length > 1) {$("#lsm_group_filters").show()} else {$("#lsm_group_filters").hide()};
        if (target_property_valuations_el.childNodes.length > 1) {$("#property_valuation_filters").show()} else {$("#property_valuation_filters").hide()};
        if (target_property_count_buckets_el.childNodes.length > 1) {$("#property_count_bucket_filters").show()} else {$("#property_count_bucket_filters").hide()};
        
}

function kFormatter(num) {
    var si = [
        { value: 1, symbol: "" },
        { value: 1E3, symbol: "k" },
        { value: 1E6, symbol: "M" },
        { value: 1E9, symbol: "G" },
        { value: 1E12, symbol: "T" },
        { value: 1E15, symbol: "P" },
        { value: 1E18, symbol: "E" }
      ];
      var rx = /\.0+$|(\.[0-9]*[1-9])0+$/;
      var i;
      for (i = si.length - 1; i > 0; i--) {
        if (num >= si[i].value) {
          break;
        }
      }
      return (num / si[i].value).toFixed(1).replace(rx, "$1") + si[i].symbol;
}

function drawProvinceChart( chart_data ) {

    $("#province-graph .spinner-block").hide();    

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Province');
    data.addColumn('number', 'Records');
    data.addColumn({type: 'string', role: 'annotation'});

    var result = Object.keys(chart_data).map(function(key) {

        var province;
        
        switch(chart_data[key]["province"]) {
            case 'G':
                province = ['Gauteng', chart_data[key]["audience"], kFormatter(chart_data[key]["audience"])];
                break;
            case 'EC':
                province = ['Eastern Cape', chart_data[key]["audience"], kFormatter(chart_data[key]["audience"])];
                break;
            case 'NC':
                province = ['Northern Cape', chart_data[key]["audience"], kFormatter(chart_data[key]["audience"])];
                break;
            case 'FS':
                province = ['Free State', chart_data[key]["audience"],kFormatter(chart_data[key]["audience"])];
                break;
            case 'L':
                province = ['Limpopo', chart_data[key]["audience"], kFormatter(chart_data[key]["audience"])];
                break;
            case 'KN':
                province = ['KwaZulu Natal', chart_data[key]["audience"], kFormatter(chart_data[key]["audience"])];
                break;
            case 'M':
                province = ['Mpumalanga', chart_data[key]["audience"], kFormatter(chart_data[key]["audience"])];
                break;
            case 'NW':
                province = ['North West', chart_data[key]["audience"], kFormatter(chart_data[key]["audience"])];
                break;
            case 'WC':
                province = ['Western Cape', chart_data[key]["audience"], kFormatter(chart_data[key]["audience"])];
                break;
            default:
                province = [chart_data[key]["province"], chart_data[key]["audience"], kFormatter(chart_data[key]["audience"])];
            }
    
        return province;
        });
        //console.log(result);
        data.addRows(result);
        var chart_options = {
            // 'height': result.length * 25,
            'width':'100%',
            'fontSize': 10,
            'chartArea': {
                top: '20',
                width: '60%',
                height: '100%'
            },
            'legend': {
                position: 'none'
            },
            'backgroundColor': '#f7f7f7',
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
        update_progress();
}

function drawAreaChart(  ) {

    $.get('/api/meetpat-client/get-records/areas', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_owners: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        $("#area-graph .spinner-block").hide();
        $("#area-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#area_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
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
        //console.log(chart_data);
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Area');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [chart_data["all"][key]["area"], chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
            });
        var shorter_result = result.slice(0, 20);
        data.addRows(shorter_result);
        // Set chart options
        var chart_options = {
                        'width':'100%',
                        'height': shorter_result.length * 25,
                        'fontSize': 10,
                        'chartArea': {
                            top: '20',
                            top: '20',
                            width: '60%',
                            height: '100%'
                        },
                        'colors': ['#00A3D9'],
                        'legend': {
                            position: 'none'
                        },
                        'backgroundColor': '#f7f7f7'
                        };
    
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.BarChart(document.getElementById('areasChart'));
        chart.draw(data, chart_options); 

        var results = Object.keys(chart_data["all"]).map(function(key) {
            return {"name": chart_data["all"][key]["area"], "count": kFormatter(chart_data["all"][key]["audience"])};
        });

        var documents = results;
        var idx = lunr(function() {
            this.ref('name');
            this.field('name');
            this.k1(1.5)
            this.b(0.25)
            documents.forEach(function (doc) {
                this.add(doc)
            }, this) 
                
            
        });

        $("#lunr-search").show();
        $("#area-filter-form .text-center").remove();
        shorter_result.forEach(function(result) {
                
            if($('#area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                $("#lunr-results").append('<input type="checkbox" name="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" class="css-checkbox" checked="checked"><label for="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '<small> ' + kFormatter(results.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0]) + '</small></label><br />');
                $('#area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                    
                    if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                        
                        var parent = this;
                        $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '" checked="checked">' + '<small> ' + kFormatter(results.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0]) + '</small></label><br />');
                        $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                            
                            if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                            }
                            checkForFilters();
                        });
                    } else {
                        
    
                        if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                            $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                            $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                        }
                    }
                    checkForFilters();
                });                        
            } else {
                $("#lunr-results").append('<input type="checkbox" name="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" class="css-checkbox"><label for="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '<small> ' + kFormatter(results.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0]) + '</small></label><br />');
                $('#area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                    if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                        
                        var parent = this;
                        $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" checked="checked">');
                        $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                            if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                            }
                            checkForFilters();

                        });
                    } else {
                        
    
                        if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                            $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                            $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();

                        }
                    }
                    checkForFilters();

                });                        
            }

        });
        // Append checked inputs to hidden form...
        document.getElementById('areaSearchInput').addEventListener('keyup', function() {
            if(idx.search(this.value + "*").length && this.value != '') {
                $("#lunr-results").empty();
                
                idx.search(this.value + "*").forEach(function(result) {
                    
                    if(result.score) {
                        if($('#area_hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                            $("#lunr-results").append('<input type="checkbox" name="' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result.ref + '" class="css-checkbox" checked="checked"><label for="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result.ref + '<small> ' + kFormatter(results.filter(obj => {if(obj.name === result.ref) { return obj.count}}).map(function(obj) { return obj.count})[0]) + '</small></label><br />');
                            $('#area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                                
                                if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                                    $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result.ref + '" checked="checked">' + '<small> ' + kFormatter(results.filter(obj => {if(obj.name === result.ref) { return obj.count}}).map(function(obj) { return obj.count})[0]) + '</small></label><br />');
                                    $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                       
                                        if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                            $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                            $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                            $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                        }
                                        checkForFilters();
                                    });
                                } else {
                                    
                
                                    if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                                        $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                        $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                    }
                                }
                                checkForFilters();
                            });                        
                        } else {
                            $("#lunr-results").append('<input type="checkbox" name="' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result.ref + '" class="css-checkbox"><label for="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result.ref + '<small> ' + kFormatter(results.filter(obj => {if(obj.name === result.ref) { return obj.count}}).map(function(obj) { return obj.count})[0]) + '</small></label><br />');
                            $('#area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                                if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                                    $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result.ref + '" checked="checked">');
                                    $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                        if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                            $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                            $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                            $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                        }
                                        checkForFilters();

                                    });
                                } else {
                                    
                
                                    if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                                        $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                        $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();

                                    }
                                }
                                checkForFilters();

                            });                        
                        }
                    }
    
                });
            } else {
                $("#lunr-results").empty();
                
                shorter_result.forEach(function(result) {
                
                    if($('#area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                        $("#lunr-results").append('<input type="checkbox" name="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" class="css-checkbox" checked="checked"><label for="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '<small> ' + kFormatter(results.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0]) + '</small></label><br />');
                        $('#area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                            
                            if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                                
                                var parent = this;
                                $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '" checked="checked">' + '<small> ' + kFormatter(results.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0]) + '</small></label><br />');
                                $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                    
                                    if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                        $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                        $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                        $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                    }
                                    checkForFilters();
                                });
                            } else {
                                
            
                                if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                                    $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                    $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                }
                            }
                            checkForFilters();
                        });                        
                    } else {
                        $("#lunr-results").append('<input type="checkbox" name="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" class="css-checkbox"><label for="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '<small> ' + kFormatter(results.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0]) + '</small></label><br />');
                        $('#area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                            if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                                
                                var parent = this;
                                $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" checked="checked">');
                                $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                    if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                        $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                        $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                        $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            } else {
                                
            
                                if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                                    $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                    $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();

                                }
                            }
                            checkForFilters();

                        });                        
                    }
    
                });
            }


        });
        update_progress();
    });
       

    // Create the data table.
    
  }

  var drawMunicipalityChart = function ( chart_data ) {
        $("#municipality-graph .spinner-block").hide();    

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Municipality');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [chart_data["all"][key]["municipality"], chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
          });

            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'height': result.length * 25,
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
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
                            'backgroundColor': '#f7f7f7'
                        };
                        
                        for (var key in chart_data["distinct"]) {
                            if(target_municipalities.includes(chart_data["distinct"][key]["municipality"])) {
                                $("#municipality_filter").append(
                                    '<input type="checkbox" name="' + chart_data["distinct"][key]["municipality"].toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '" id="municipality_' + chart_data["distinct"][key]["municipality"].toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option' +'" value="' + chart_data["distinct"][key]["municipality"] + '" class="css-checkbox" checked="checked"><label for="municipality_' + chart_data["distinct"][key]["municipality"].toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option' +'" class="css-label">' + chart_data["distinct"][key]["municipality"] + '</label><br />'
                                );
                            } else {
                                $("#municipality_filter").append(
                                    '<input type="checkbox" name="' + chart_data["distinct"][key]["municipality"].toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '" id="municipality_' + chart_data["distinct"][key]["municipality"].toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option' +'" value="' + chart_data["distinct"][key]["municipality"] + '" class="css-checkbox"><label for="municipality_' + chart_data["distinct"][key]["municipality"].toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option' +'" class="css-label">' + chart_data["distinct"][key]["municipality"] + '</label><br />'
                                );
                            }

                            $('#municipality_' + chart_data["distinct"][key]["municipality"].toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').click(function(){
                                if($('#municipality_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                
                                    $("#municipality_filters").append('<li id="filter_municipality_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_municipality_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + ' i').click(function() {
                                        if($('#municipality_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').length) {
                                            $('#filter_municipality_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_')).remove();
                                            $("#municipality_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').prop("checked", false);
                                        }
                                        checkForFilters();

                                    });
                                } else {
                                    
                
                                    if($('#filter_municipality_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_'))) {
                                        $('#filter_municipality_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_')).remove();
                                    }
                                }
                                checkForFilters();

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
        switch(chart_data[key]["province"]) {
            case 'G':
            value =  ['ZA-GT', chart_data[key]["audience"], '<ul class="list-unstyled"><li><b>Gauteng</b></li><li>'+ kFormatter(chart_data[key]["audience"]) +'</li></ul>'];
                break;
            case 'WC':
            value =  ['ZA-WC', chart_data[key]["audience"], '<ul class="list-unstyled"><li><b>Western Cape</b></li><li>'+ kFormatter(chart_data[key]["audience"]) +'</li></ul>'];
                break;
            case 'EC':
            value =  ['ZA-EC', chart_data[key]["audience"], '<ul class="list-unstyled"><li><b>Eastern Cape</b></li><li>'+ kFormatter(chart_data[key]["audience"]) +'</li></ul>'];
                break;
            case 'M':
            value =  ['ZA-MP', chart_data[key]["audience"], '<ul class="list-unstyled"><li><b>Mpumalanga</b></li><li>'+ kFormatter(chart_data[key]["audience"]) +'</li></ul>'];
                break;  
            case 'FS':
            value =  ['ZA-FS', chart_data[key]["audience"], '<ul class="list-unstyled"><li><b>Free State</b></li><li>'+ kFormatter(chart_data[key]["audience"]) +'</li></ul>'];
                break;
            case 'L':
            value =  ['ZA-LP', chart_data[key]["audience"], '<ul class="list-unstyled"><li><b>Limpopo</b></li><li>'+ kFormatter(chart_data[key]["audience"]) +'</li></ul>'];
                break;  
            case 'KN':
            value =  ['ZA-NL', chart_data[key]["audience"], '<ul class="list-unstyled"><li><b>KwaZula Natal</b></li><li>'+ kFormatter(chart_data[key]["audience"]) +'</li></ul>'];
                break; 
            case 'NW':
            value =  ['ZA-NW', chart_data[key]["audience"], '<ul class="list-unstyled"><li><b>North West Province</b></li><li>'+ kFormatter(chart_data[key]["audience"]) +'</li></ul>'];
                break;      
            case 'NC':
            value =  ['ZA-NC', chart_data[key]["audience"], '<ul class="list-unstyled"><li><b>Northern Cape</b></li><li>'+ kFormatter(chart_data[key]["audience"]) +'</li></ul>'];
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
          'backgroundColor': '#f7f7f7',
          'colorAxis': {colors: ['#039be5']},
          tooltip: {
            isHtml: true
        }
        };

      var chart = new google.visualization.GeoChart(document.getElementById('chartdiv'));

      chart.draw(data, options);
      update_progress();
  }

  var drawAgeChart = function (  ) {

    $.get('/api/meetpat-client/get-records/ages', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        $("#age-graph .spinner-block").hide();
        $("#age-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#age_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        console.log( chart_data );

    }).done(function( chart_data ) {
        $("#age-graph .spinner-block").hide();    
        $("#age_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Age');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [chart_data["all"][key]["ageGroup"], chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
          });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            // 'height': result.length * 25,
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
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
                            'backgroundColor': '#f7f7f7'
                        };

                        for (var key in chart_data["distinct"]) {
                            if(target_ages.includes(chart_data["distinct"][key]["ageGroup"])) {
                                $("#age_filter").append(
                                    '<input type="checkbox" name="' + chart_data["distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '" id="age_' + chart_data["distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" value="' + chart_data["distinct"][key]["ageGroup"] + '" class="css-checkbox" checked="checked"><label for="age_' + chart_data["distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" class="css-label">' + chart_data["distinct"][key]["ageGroup"] + '</label><br />'
                                );
                            } else {
                                $("#age_filter").append(
                                    '<input type="checkbox" name="' + chart_data["distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '" id="age_' + chart_data["distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" value="' + chart_data["distinct"][key]["ageGroup"] + '" class="css-checkbox"><label for="age_' + chart_data["distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" class="css-label">' + chart_data["distinct"][key]["ageGroup"] + '</label><br />'
                                );
                            }

                            $('#age_' + chart_data["distinct"][key]["ageGroup"].toLowerCase() + '_option').click(function(){
                                if($('#age_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                
                                    $("#age_filters").append('<li id="filter_age_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace("+", "plus") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_age_' + $(this).val().toLowerCase().replace(/ /g, "_").replace("+", "plus") + ' i').click(function() {
                                        if($('#age_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option').length) {
                                            $('#filter_age_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace("+", "plus")).remove();
                                            $("#age_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option').prop("checked", false);
                                        }
                                        checkForFilters();

                                    });
                                } else {
                                    
                
                                    if($('#filter_age_' + $(this).val().toLowerCase().replace(/ /g, "_").replace("+", "plus"))) {
                                        $('#filter_age_' + $(this).val().toLowerCase().replace(/ /g, "_").replace("+", "plus")).remove();
                                    }
                                }
                                checkForFilters();

                            });
                        }
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('agesChart'));
            chart.draw(data, chart_options);   
            update_progress();  
    });

               
    }

    var drawPropertyValuationChart = function (  ) {

        $.get('/api/meetpat-client/get-records/property-valuation', {user_id: user_id_number, province: target_provinces.join(","),
             age_group: target_ages.join(","), gender: target_genders.join(","), 
             population_group: target_population_groups.join(","), generation: target_generations.join(","),
             marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
             risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
             directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
             municipality: target_municipalities.join(","), area: target_areas.join(","),
             vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
             lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
             }, function( chart_data ) {
    
        }).fail(function( chart_data ) {
            $("#property-valuation-graph .spinner-block").hide();
            $("#property-valuation-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
            $("#property_valuation_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
            console.log( chart_data );
    
        }).done(function( chart_data ) {
            $("#property-valuation-graph .spinner-block").hide();    
            $("#property_valuation_filter").empty();
    
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Property Valuation');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});
    
            var result = Object.keys(chart_data["all"]).map(function(key) {
                return [keyChangerPrValBucket(chart_data["all"][key]["propertyValuationBucket"]), chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
              });
        
                data.addRows(result);
                // Set chart options
                var chart_options = {
                                // 'height': '30%',
                                'width':'100%',
                                'fontSize': 10,
                                'chartArea': {
                                    top: '20',
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
                                'backgroundColor': '#f7f7f7'
                            };
    
                            for (var key in chart_data["distinct"]) {
                                if(target_property_valuations.includes(chart_data["distinct"][key]["propertyValuationBucket"])) {
                                    $("#property_valuation_filter").append(
                                        '<input type="checkbox" name="' + chart_data["distinct"][key]["propertyValuationBucket"].toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "").replace("-", "") + '" id="property_valuations_' + chart_data["distinct"][key]["propertyValuationBucket"].toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '_option' +'" value="' + chart_data["distinct"][key]["propertyValuationBucket"] + '" class="css-checkbox" checked="checked"><label for="property_valuations_' + chart_data["distinct"][key]["propertyValuationBucket"].toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '_option' +'" class="css-label">' + keyChangerPrValBucket(chart_data["distinct"][key]["propertyValuationBucket"]) + '</label><br />'
                                    );
                                } else {
                                    $("#property_valuation_filter").append(
                                        '<input type="checkbox" name="' + chart_data["distinct"][key]["propertyValuationBucket"].toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '" id="property_valuations_' + chart_data["distinct"][key]["propertyValuationBucket"].toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '_option' +'" value="' + chart_data["distinct"][key]["propertyValuationBucket"] + '" class="css-checkbox"><label for="property_valuations_' + chart_data["distinct"][key]["propertyValuationBucket"].toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '_option' +'" class="css-label">' + keyChangerPrValBucket(chart_data["distinct"][key]["propertyValuationBucket"]) + '</label><br />'
                                    );
                                }
    
                                $('#property_valuations_' + chart_data["distinct"][key]["propertyValuationBucket"].toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '_option').click(function(){
                                    if($('#property_valuations_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '_option').is(":checked")) { 
                                        
                                        var parent = this;
                    
                                        $("#property_valuation_filters").append('<li id="filter_property_valuations_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                        $('#filter_property_valuations_' + $(this).val().toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + ' i').click(function() {
                                            if($('#property_valuations_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '_option').length) {
                                                $('#filter_property_valuations_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "")).remove();
                                                $("#property_valuations_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "") + '_option').prop("checked", false);
                                            }
                                            checkForFilters();
    
                                        });
                                    } else {
                                        
                    
                                        if($('#filter_property_valuations_' + $(this).val().toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", ""))) {
                                            $('#filter_property_valuations_' + $(this).val().toLowerCase().replace(/ /g, "_").replace("+", "plus").replace("-", "")).remove();
                                        }
                                    }
                                    checkForFilters();
    
                                });
                            }
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.BarChart(document.getElementById('propertyValuationChart'));
                chart.draw(data, chart_options);     
                update_progress();
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
        $.get('/api/meetpat-client/get-records/genders', {user_id: user_id_number, province: target_provinces.join(","),
             age_group: target_ages.join(","), gender: target_genders.join(","), 
             population_group: target_population_groups.join(","), generation: target_generations.join(","),
             marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
             risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
             directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
             municipality: target_municipalities.join(","), area: target_areas.join(","),
             vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
             lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
             }, function(chart_data) {

        }).fail(function( chart_data ) {
            $("#gender-graph .spinner-block").hide();
            $("#gender-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
            $("#gender_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
            console.log( chart_data )
        }).done(function( chart_data ) {
            $("#gender-graph .spinner-block").hide();    
            $("#gender_filter").empty();

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Gender');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});

            var result = Object.keys(chart_data["all"]).map(function(key) {
                return [keyChangerGender(chart_data["all"][key]["gender"]), chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
            });
        
                data.addRows(result);
                // Set chart options
                var chart_options = {
                                'width':'100%',
                                'fontSize': 10,
                                'chartArea': {
                                    top: '20',
                                    width: '60%',
                                    height: '75%'
                                    },
                                vAxis: {
                                    minValue: 0,
                                    format: "short"
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
                                'backgroundColor': '#f7f7f7'
                            };

                            for (var key in chart_data["distinct"]) {
                                if(target_genders.includes(chart_data["distinct"][key]["gender"])) {
                                    $("#gender_filter").append(
                                        '<input type="checkbox" name="g_' + chart_data["distinct"][key]["gender"] + '" id="g_' + chart_data["distinct"][key]["gender"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["gender"] + '" class="css-checkbox" checked="checked"><label for="g_' + chart_data["distinct"][key]["gender"].toLowerCase() + '_option' +'" class="css-label">' + get_gender_name(chart_data["distinct"][key]["gender"]) + '</label><br />'
                                    );
                                } else {
                                    $("#gender_filter").append(
                                        '<input type="checkbox" name="g_' + chart_data["distinct"][key]["gender"] + '" id="g_' + chart_data["distinct"][key]["gender"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["gender"] + '" class="css-checkbox"><label for="g_' + chart_data["distinct"][key]["gender"].toLowerCase() + '_option' +'" class="css-label">' + get_gender_name(chart_data["distinct"][key]["gender"]) + '</label><br />'
                                    );
                                }

                                $('#g_' + chart_data["distinct"][key]["gender"].toLowerCase() + '_option').click(function(){
                                    if($('#g_' + $(this).val().toLowerCase() + '_option').is(":checked")) { 
                                        
                                        var parent = this;
                    
                                        $("#gender_filters").append('<li id="filter_g_' + $(this).val().toLowerCase() + '">'+ get_gender_name($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                                        $('#filter_g_' + $(this).val().toLowerCase() + ' i').click(function() {
                                            if($('#g_' + $(parent).val().toLowerCase() + '_option').length) {
                                                $('#filter_g_' + $(parent).val().toLowerCase()).remove();
                                                $("#g_" + $(parent).val().toLowerCase() + '_option').prop("checked", false);
                                            }
                                            checkForFilters();

                                        });
                                    } else {
                                        
                    
                                        if($('#filter_g_' + $(this).val().toLowerCase())) {
                                            $('#filter_g_' + $(this).val().toLowerCase()).remove();
                                        }
                                    }
                                    checkForFilters();

                                });
                    
                            }
            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('genderChart'));
                chart.draw(data, chart_options);  
                update_progress();   
        });
    }

    var drawPopulationChart = function() {

        // get ethnic name
        var get_ethnic_name = function(short_name) {
            switch(short_name) {
                case "B":
                    return "Black";
                case "W":
                    return "White";
                case "C":
                    return "Coloured";
                case "A":
                    return "Asian";
                default:
                    return "Unkown";
            }
        }

        $.get('/api/meetpat-client/get-records/population-groups', {user_id: user_id_number, province: target_provinces.join(","),
             age_group: target_ages.join(","), gender: target_genders.join(","), 
             population_group: target_population_groups.join(","), generation: target_generations.join(","),
             marital_status: target_marital_statuses.join(","), home_owners: target_home_owners.join(","),
             risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
             directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
             municipality: target_municipalities.join(","), area: target_areas.join(","),
             vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
             lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
             }, function( chart_data ) {

        }).fail(function( chart_data ) {
            $("#population-graph .spinner-block").hide();
            $("#population-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
            $("#population_group_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
            console.log( chart_data );

        }).done(function( chart_data ) {
            $("#population-graph .spinner-block").hide();    
            $("#population_group_filter").empty();

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Group');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});

            var result = Object.keys(chart_data["all"]).map(function(key) {
                return [get_ethnic_name(chart_data["all"][key]["populationGroup"]), chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
            });
        
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0, 
                                format: "short"

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
                            'backgroundColor': '#f7f7f7'
                        };

                        for (var key in chart_data["distinct"]) {
                            if(target_population_groups.includes(chart_data["distinct"][key]["populationGroup"])) {
                                $("#population_group_filter").append(
                                    '<input type="checkbox" name="pop_' + chart_data["distinct"][key]["populationGroup"] + '" id="pop_' + chart_data["distinct"][key]["populationGroup"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["populationGroup"] + '" class="css-checkbox" checked="checked"><label for="pop_' + chart_data["distinct"][key]["populationGroup"].toLowerCase() + '_option' +'" class="css-label">' + get_ethnic_name(chart_data["distinct"][key]["populationGroup"]) + '</label><br />'
                                );
                            } else {
                                $("#population_group_filter").append(
                                    '<input type="checkbox" name="pop_' + chart_data["distinct"][key]["populationGroup"] + '" id="pop_' + chart_data["distinct"][key]["populationGroup"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["populationGroup"] + '" class="css-checkbox"><label for="pop_' + chart_data["distinct"][key]["populationGroup"].toLowerCase() + '_option' +'" class="css-label">' + get_ethnic_name(chart_data["distinct"][key]["populationGroup"]) + '</label><br />'
                                );
                            }

                            $('#pop_' + chart_data["distinct"][key]["populationGroup"].toLowerCase() + '_option').click(function(){
                                if($('#pop_' + $(this).val().toLowerCase() + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                
                                    $("#population_group_filters").append('<li id="filter_pop_' + $(this).val().toLowerCase() + '">'+ get_ethnic_name($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_pop_' + $(this).val().toLowerCase() + ' i').click(function() {
                                        if($('#pop_' + $(parent).val().toLowerCase() + '_option').length) {
                                            $('#filter_pop_' + $(parent).val().toLowerCase()).remove();
                                            $("#pop_" + $(parent).val().toLowerCase() + '_option').prop("checked", false);
                                        }
                                        checkForFilters();

                                    });
                                } else {
                                    
                
                                    if($('#filter_pop_' + $(this).val().toLowerCase())) {
                                        $('#filter_pop_' + $(this).val().toLowerCase()).remove();
                                    }
                                }
                                checkForFilters();

                            });
                
                        }
            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('populationGroupChart'));
                chart.draw(data, chart_options);     
                update_progress();
        });
    }

    var drawGenerationChart = function() {

        $.get('/api/meetpat-client/get-records/generations', {user_id: user_id_number, province: target_provinces.join(","),
             age_group: target_ages.join(","), gender: target_genders.join(","), 
             population_group: target_population_groups.join(","), generation: target_generations.join(","),
             marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
             risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
             directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
             municipality: target_municipalities.join(","), area: target_areas.join(","), 
             vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
             lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
             }, function( chart_data ) {

        }).fail(function( chart_data ) {
            $("#generation-graph .spinner-block").hide();
            $("#generation-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
            $("#generation_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
            console.log( chart_data );
        }).done(function( chart_data ) {
            $("#generation-graph .spinner-block").hide();    
            $("#generation_filter").empty();

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Generation');
            data.addColumn('number', 'Records');
            data.addColumn({type: 'string', role: 'annotation'});

            var result = Object.keys(chart_data["all"]).map(function(key) {
                return [chart_data["all"][key]["generation"], chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
            });
        
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
                                format: "short"
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
                            'backgroundColor': '#f7f7f7'
                        };
                        for (var key in chart_data["distinct"]) {
                            if(target_generations.includes(chart_data["distinct"][key]["generation"])) {
                                $("#generation_filter").append(
                                    '<input type="checkbox" name="gen_' + chart_data["distinct"][key]["generation"] + '" id="gen_' + chart_data["distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option' +'" value="' + chart_data["distinct"][key]["generation"] + '" class="css-checkbox" checked="checked"><label for="gen_' + chart_data["distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option' +'" class="css-label">' + chart_data["distinct"][key]["generation"] + '</label><br />'
                                );
                            } else {
                                $("#generation_filter").append(
                                    '<input type="checkbox" name="gen_' + chart_data["distinct"][key]["generation"] + '" id="gen_' + chart_data["distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option' +'" value="' + chart_data["distinct"][key]["generation"] + '" class="css-checkbox"><label for="gen_' + chart_data["distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option' +'" class="css-label">' + chart_data["distinct"][key]["generation"] + '</label><br />'
                                );
                            }
                            $('#gen_' + chart_data["distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                                if($('#gen_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                
                                    $("#generation_filters").append('<li id="filter_gen_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_gen_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                                        if($('#gen_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                            $('#filter_gen_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                            $("#gen_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                                        }
                                        checkForFilters();

                                    });
                                } else {
                                    
                
                                    if($('#filter_gen_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                                        $('#filter_gen_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                                    }
                                }
                                checkForFilters();

                            });
                
                        }            
                // Instantiate and draw our chart, passing in some options.
                var chart = new google.visualization.ColumnChart(document.getElementById('generationChart'));
                chart.draw(data, chart_options);
                update_progress();
        });

    }

var drawCitizensChart = function() {
    $.get('/api/meetpat-client/get-records/citizens-and-residents', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","), 
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        $("#c-vs-r-graph .spinner-block").hide();
        $("#c-vs-r-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#citizen_vs_resident_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
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
                                top: '20',
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
                                format: "short"
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
                            'backgroundColor': '#f7f7f7'
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
                if($('#citizen_option').is(":checked")) { 

                    $("#generation_filters").append('<li id="filter_citizen_option">Citizen<i class="fas fa-window-close float-right"></i></li>');
                    $('#filter_citizen_option i').click(function() {
                        if($('#filter_citizen_option')) {
                            $('#filter_citizen_option').remove();
                            $("#citizen_option").prop("checked", false);
                        }
                        checkForFilters();

                    });
                } else {

                    if($('#filter_citizen_option')){
                        $('#filter_citizen_option').remove();
                    }
                }
                checkForFilters();

            });

            $('#resident_option').click(function(){
                if($('#resident_option').is(":checked")) { 

                    $("#generation_filters").append('<li id="filter_resident_option">Resident<i class="fas fa-window-close float-right"></i></li>');
                    $('#filter_resident_option i').click(function() {
                        if($('#filter_resident_option')) {
                            $('#filter_resident_option').remove();
                            $("#resident_option").prop("checked", false);
                        }
                        checkForFilters();

                    });
                } else {

                    if($('#filter_resident_option')){
                        $('#filter_resident_option').remove();
                    }
                }
                checkForFilters();

            });

            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('citizensVsResidentsChart'));
            chart.draw(data, chart_options);    
            update_progress();
    });
}

var drawMaritalStatusChart = function() {
    $.get('/api/meetpat-client/get-records/marital-statuses', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        $("#marital-status-graph .spinner-block").hide();
        $("#marital-status-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#marital_status_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#marital-status-graph .spinner-block").hide();    
        $("#marital_status_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Marital Status');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
                return [keyChangerMaritalStatus(chart_data["all"][key]["maritalStatus"]), chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                'width':'100%',
                'fontSize': 10,
                'chartArea': {
                    top: '20',
                    width: '60%',
                    height: '75%'
                    },
                vAxis: {
                    minValue: 0,
                    format: "short"
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
                'backgroundColor': '#f7f7f7'
            };

            for (var key in chart_data["distinct"]) {
                if(target_marital_statuses.includes(chart_data["distinct"][key]["maritalStatus"].toLowerCase())) {
                    $("#marital_status_filter").append(
                        '<input type="checkbox" name="m_' + chart_data["distinct"][key]["maritalStatus"].toLowerCase() + '" id="m_' + chart_data["distinct"][key]["maritalStatus"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["maritalStatus"].toLowerCase() + '" class="css-checkbox" checked="checked"><label for="m_' + chart_data["distinct"][key]["maritalStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChangerMaritalStatus(chart_data["distinct"][key]["maritalStatus"]) + '</label><br />'
                    );

                } else {
                    $("#marital_status_filter").append(
                        '<input type="checkbox" name="m_' + chart_data["distinct"][key]["maritalStatus"].toLowerCase() + '" id="m_' + chart_data["distinct"][key]["maritalStatus"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["maritalStatus"].toLowerCase() + '" class="css-checkbox"><label for="m_' + chart_data["distinct"][key]["maritalStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChangerMaritalStatus(chart_data["distinct"][key]["maritalStatus"]) + '</label><br />'
                    );
                }
                $('#m_' + chart_data["distinct"][key]["maritalStatus"].toLowerCase() + '_option').click(function(){
                    if($('#m_' + $(this).val().toLowerCase() + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#marital_status_filters").append('<li id="filter_m_' + $(this).val().toLowerCase() + '">'+ keyChangerMaritalStatus($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_m_' + $(this).val().toLowerCase() + ' i').click(function() {
                            if($('#m_' + $(parent).val().toLowerCase() + '_option').length) {
                                $('#filter_m_' + $(parent).val().toLowerCase()).remove();
                                $("#m_" + $(parent).val().toLowerCase() + '_option').prop("checked", false);
                            }
                            checkForFilters();

                        });
                    } else {
                        
    
                        if($('#filter_m_' + $(this).val().toLowerCase())) {
                            $('#filter_m_' + $(this).val().toLowerCase()).remove();
                        }
                    }
                    checkForFilters();

                });
            }            
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('maritalStatusChart'));
            chart.draw(data, chart_options);    
            update_progress();
    });
}

var drawHomeOwnerChart = function() {
    $.get('/api/meetpat-client/get-records/home-owner', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        $("#home-owner-graph .spinner-block").hide();
        $("#home-owner-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#home_owner_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#home-owner-graph .spinner-block").hide();    
        $("#home_owner_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Home Owner Status');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [keyChanger(chart_data["all"][key]["homeOwnershipStatus"]), chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
                                format: "short"
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
                            'backgroundColor': '#f7f7f7'
                        };
            for (var key in chart_data["distinct"]) {
                if(target_home_owners.includes(chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase())) {
                    $("#home_owner_filter").append(
                        '<input type="checkbox" name="h_' + chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase() + '" id="h_' + chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase() + '" class="css-checkbox" checked="checked"><label for="h_' + chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(chart_data["distinct"][key]["homeOwnershipStatus"]) + '</label><br />'
                    );
                } else {
                    $("#home_owner_filter").append(
                        '<input type="checkbox" name="h_' + chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase() + '" id="h_' + chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase() + '" class="css-checkbox"><label for="h_' + chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(chart_data["distinct"][key]["homeOwnershipStatus"]) + '</label><br />'
                    );
                }
                
                $('#h_' + chart_data["distinct"][key]["homeOwnershipStatus"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                    if($('#h_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#home_owner_filters").append('<li id="filter_h_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ keyChanger($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_h_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                            if($('#h_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                $('#filter_h_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                $("#h_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                            }
                            checkForFilters();

                        });
                    } else {
                        
    
                        if($('#filter_h_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                            $('#filter_h_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                        }
                    }
                    checkForFilters();

                });
            }              
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('homeOwnerChart'));
            chart.draw(data, chart_options);    
            update_progress();
    });
}

var drawpropertyCountBucketChart = function() {
    $.get('/api/meetpat-client/get-records/property-count-bucket', {user_id: user_id_number, province: target_provinces.join(","),
         age: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log(chart_data);
        $("#property-count-bucket-graph .spinner-block").hide();
        $("#property-count-bucket-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#property_count_bucket_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
    }).done(function( chart_data ) {
        $("#property-count-bucket-graph .spinner-block").hide();    
        $("#property_count_bucket_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Property Count');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [chart_data["all"][key]["propertyCountBucket"], chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0, 
                                format: "short"
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
                            'backgroundColor': '#f7f7f7'
                        };
            for (var key in chart_data["distinct"]) {
                if(target_home_owners.includes(chart_data["distinct"][key]["propertyCountBucket"])) {
                    $("#property_count_bucket_filter").append(
                        '<input type="checkbox" name="pc_' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '" id="pc_' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '" class="css-checkbox" checked="checked"><label for="pc_' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '_option' +'" class="css-label">' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '</label><br />'
                    );
                } else {
                    $("#property_count_bucket_filter").append(
                        '<input type="checkbox" name="pc_' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '" id="pc_' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '" class="css-checkbox"><label for="pc_' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '_option' +'" class="css-label">' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase() + '</label><br />'
                    );
                }
                
                $('#pc_' + chart_data["distinct"][key]["propertyCountBucket"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                    if($('#pc_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#property_count_bucket_filters").append('<li id="filter_pc_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_pc_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                            if($('#pc_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                $('#filter_pc_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                $("#pc_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                            }
                            checkForFilters();

                        });
                    } else {
                        
    
                        if($('#filter_pc_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                            $('#filter_pc_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                        }
                    }
                    checkForFilters();

                });
            }              
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('propertyCountBucketChart'));
            chart.draw(data, chart_options);  
            update_progress();  
    });
}

var drawVehicleOwnerChart = function() {
    $.get('/api/meetpat-client/get-records/vehicle-owner', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        console.log(chart_data);
        $("#vehicle-owner-graph .spinner-block").hide();
        $("#vehicle-owner-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#vehicle_owner_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
    }).done(function( chart_data ) {
        $("#vehicle-owner-graph .spinner-block").hide();    
        $("#vehicle_owner_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Vehicle Owner Status');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [keyChanger(chart_data["all"][key]["vehicleOwnershipStatus"]), chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
                                format: "short"
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
                            'backgroundColor': '#f7f7f7'
                        };
            for (var key in chart_data["distinct"]) {
                if(target_vehicle_owners.includes(chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase())) {
                    $("#vehicle_owner_filter").append(
                        '<input type="checkbox" name="vo_' + chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '" id="vo_' + chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '" class="css-checkbox" checked="checked"><label for="vo_' + chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(chart_data["distinct"][key]["vehicleOwnershipStatus"]) + '</label><br />'
                    );
                } else {
                    $("#vehicle_owner_filter").append(
                        '<input type="checkbox" name="vo_' + chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '" id="vo_' + chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '" class="css-checkbox"><label for="vo_' + chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(chart_data["distinct"][key]["vehicleOwnershipStatus"]) + '</label><br />'
                    );
                }
                
                $('#vo_' + chart_data["distinct"][key]["vehicleOwnershipStatus"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                    
                    if($('#vo_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#vehicle_owner_filters").append('<li id="filter_vo_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ keyChanger($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_vo_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                            if($('#vo_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                $('#filter_vo_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                $("#vo_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                            }
                            checkForFilters();

                        });
                    } else {
                        
    
                        if($('#filter_vo_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                            $('#filter_vo_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                        }
                    }
                    checkForFilters();

                });
            }              
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('vehicleOwnerChart'));
            chart.draw(data, chart_options);    
            update_progress();
    });
}

var drawLSMGroupChart = function() {
    $.get('/api/meetpat-client/get-records/lsm-group', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        $("#lsm-group-graph .spinner-block").hide();
        $("#lsm-group-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#lsm_group_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#lsm-group-graph .spinner-block").hide();    
        $("#lsm_group_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'LSM Group');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [chart_data["all"][key]["lsmGroup"], chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
                                format: "short"
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
                            'backgroundColor': '#f7f7f7'
                        };
            for (var key in chart_data["distinct"]) {
                if(target_lsm_groups.includes(chart_data["distinct"][key]["lsmGroup"])) {
                    $("#lsm_group_filter").append(
                        '<input type="checkbox" name="lsm_' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '" id="lsm_' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '" class="css-checkbox" checked="checked"><label for="lsm_' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '_option' +'" class="css-label">' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '</label><br />'
                    );
                } else {
                    $("#lsm_group_filter").append(
                        '<input type="checkbox" name="lsm_' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '" id="lsm_' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '" class="css-checkbox"><label for="lsm_' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '_option' +'" class="css-label">' + chart_data["distinct"][key]["lsmGroup"].toLowerCase() + '</label><br />'
                    );
                }
                
                $('#lsm_' + chart_data["distinct"][key]["lsmGroup"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                    
                    if($('#lsm_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#lsm_group_filters").append('<li id="filter_lsm_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_lsm_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                            if($('#lsm_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                $('#filter_lsm_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                $("#lsm_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                            }
                            checkForFilters();

                        });
                    } else {
                        
    
                        if($('#filter_lsm_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                            $('#filter_lsm_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                        }
                    }
                    checkForFilters();

                });
            }              
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('lsmGroupChart'));
            chart.draw(data, chart_options);    
            update_progress();
    });
}

var drawRiskCategoryChart = function() {
    $.get('/api/meetpat-client/get-records/risk-category', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        $("#risk-category-graph .spinner-block").hide();
        $("#risk-category-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#risk_category_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        console.log( chart_data );

    }).done(function( chart_data ) {
        $("#risk-category-graph .spinner-block").hide();    
        $("#risk_category_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Age');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [chart_data["all"][key]["riskCategory"], chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
          });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            // 'height': result.length * 25,
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
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
                            'backgroundColor': '#f7f7f7'
                        };
            for (var key in chart_data["distinct"]) {
                if(target_risk_categories.includes(chart_data["distinct"][key]["riskCategory"])) {
                    $("#risk_category_filter").append(
                        '<input type="checkbox" name="r_' + chart_data["distinct"][key]["riskCategory"] + '" id="r_' + chart_data["distinct"][key]["riskCategory"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["riskCategory"] + '" class="css-checkbox" checked="checked"><label for="r_' + chart_data["distinct"][key]["riskCategory"].toLowerCase() + '_option' +'" class="css-label">' + chart_data["distinct"][key]["riskCategory"] + '</label><br />'
                    );
                } else {
                    $("#risk_category_filter").append(
                        '<input type="checkbox" name="r_' + chart_data["distinct"][key]["riskCategory"] + '" id="r_' + chart_data["distinct"][key]["riskCategory"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["riskCategory"] + '" class="css-checkbox"><label for="r_' + chart_data["distinct"][key]["riskCategory"].toLowerCase() + '_option' +'" class="css-label">' + chart_data["distinct"][key]["riskCategory"] + '</label><br />'
                    );
                }

                $('#r_' + chart_data["distinct"][key]["riskCategory"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                    if($('#r_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#risk_category_filters").append('<li id="filter_r_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_r_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                            if($('#r_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                $('#filter_r_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                $("#r_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                            }
                            checkForFilters();

                        });
                    } else {
                        
    
                        if($('#filter_r_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                            $('#filter_r_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                        }
                    }
                    checkForFilters();

                });
    
            }        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('riskCategoryChart'));
            chart.draw(data, chart_options);  
            update_progress();   
    });
}

var drawHouseholdIncomeChart = function() {
    $.get('/api/meetpat-client/get-records/household-income', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","), 
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","), 
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        $("#income-graph .spinner-block").hide();
        $("#income-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#household_income_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        console.log( chart_data );

    }).done(function( chart_data ) {
        $("#income-graph .spinner-block").hide();    
        $("#household_income_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Income');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [keyChangerHsIncBucket(chart_data["all"][key]["incomeBucket"]), chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
          });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                                    'width':'100%',
                                    'fontSize': 10,
                                    'chartArea': {
                                        top: '20',
                                        width: '60%',
                                        height: '75%'
                                        },
                                    vAxis: {
                                        minValue: 0,
                                        format: "short"
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
                                    'backgroundColor': '#f7f7f7'
                        };
            for (var key in chart_data["distinct"]) {
                if(target_incomes.includes(chart_data["distinct"][key]["incomeBucket"])) {
                    $("#household_income_filter").append(
                        '<input type="checkbox" name="hi_' + chart_data["distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '" id="hi_' + chart_data["distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" value="' + chart_data["distinct"][key]["incomeBucket"] + '" class="css-checkbox" checked="checked"><label for="hi_' + chart_data["distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" class="css-label">' + keyChangerHsIncBucket(chart_data["distinct"][key]["incomeBucket"]) + '</label><br />'
                    );
                } else {
                    $("#household_income_filter").append(
                        '<input type="checkbox" name="hi_' + chart_data["distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '" id="hi_' + chart_data["distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" value="' + chart_data["distinct"][key]["incomeBucket"] + '" class="css-checkbox"><label for="hi_' + chart_data["distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" class="css-label">' + keyChangerHsIncBucket(chart_data["distinct"][key]["incomeBucket"]) + '</label><br />'
                    );
                }

                $('#hi_' + chart_data["distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option').click(function(){
                    if($('#hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#household_income_filters").append('<li id="filter_hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + ' i').click(function() {
                            if($('#hi_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option').length) {
                                $('#filter_hi_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus')).remove();
                                $("#hi_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option').prop("checked", false);
                            }
                            checkForFilters();

                        });
                    } else {
                        
    
                        if($('#filter_hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') )) {
                            $('#filter_hi_' + $(this).val().toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') ).remove();
                        }
                    }
                    checkForFilters();

                });
    
            }              
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.BarChart(document.getElementById('householdIncomeChart'));
            chart.draw(data, chart_options);     
            update_progress();
    });    
}

var drawDirectorOfBusinessChart = function() {
    
    $.get('/api/meetpat-client/get-records/director-of-business', {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( chart_data ) {

    }).fail(function( chart_data ) {
        $("#directors-graph .spinner-block").hide();
        $("#directors-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#directors_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        console.log( chart_data );
    }).done(function( chart_data ) {
        $("#directors-graph .spinner-block").hide();    
        $("#directors_filter").empty();

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Director of Business');
        data.addColumn('number', 'Records');
        data.addColumn({type: 'string', role: 'annotation'});

        var result = Object.keys(chart_data["all"]).map(function(key) {
            return [keyChanger(chart_data["all"][key]["directorshipStatus"]), chart_data["all"][key]["audience"], kFormatter(chart_data["all"][key]["audience"])];
        });
    
            data.addRows(result);
            // Set chart options
            var chart_options = {
                            'width':'100%',
                            'fontSize': 10,
                            'chartArea': {
                                top: '20',
                                width: '60%',
                                height: '75%'
                                },
                            vAxis: {
                                minValue: 0,
                                format: "short"
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
                            'backgroundColor': '#f7f7f7'
                        };
            for (var key in chart_data["distinct"]) {
                if(target_directors.includes(chart_data["distinct"][key]["directorshipStatus"])) {
                    $("#directors_filter").append(
                        '<input type="checkbox" name="d_' + chart_data["distinct"][key]["directorshipStatus"] + '" id="d_' + chart_data["distinct"][key]["directorshipStatus"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["directorshipStatus"] + '" class="css-checkbox" checked="checked"><label for="d_' + chart_data["distinct"][key]["directorshipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(chart_data["distinct"][key]["directorshipStatus"]) + '</label><br />'
                    );
                } else {
                    $("#directors_filter").append(
                        '<input type="checkbox" name="d_' + chart_data["distinct"][key]["directorshipStatus"] + '" id="d_' + chart_data["distinct"][key]["directorshipStatus"].toLowerCase() + '_option' +'" value="' + chart_data["distinct"][key]["directorshipStatus"] + '" class="css-checkbox"><label for="d_' + chart_data["distinct"][key]["directorshipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(chart_data["distinct"][key]["directorshipStatus"]) + '</label><br />'
                    );
                }

                $('#d_' + chart_data["distinct"][key]["directorshipStatus"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                    if($('#d_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                        
                        var parent = this;
    
                        $("#directors_filters").append('<li id="filter_d_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ keyChanger($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                        $('#filter_d_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                            if($('#d_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                $('#filter_d_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                $("#d_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                            }
                            checkForFilters();

                        });
                    } else {
                        
    
                        if($('#filter_d_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                            $('#filter_d_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                        }
                    }
                    checkForFilters();

                });
    
            }        
            // Instantiate and draw our chart, passing in some options.
            var chart = new google.visualization.ColumnChart(document.getElementById('directorOfBusinessChart'));
            chart.draw(data, chart_options);    
            update_progress();
            hide_progress();
            $(".apply-filter-button").prop("disabled", false);
            $('.apply-filter-button').html("apply");
            $('#sidebarSubmitBtn').html('<i class="fas fa-sync-alt"></i>&nbsp;Apply Filters');
            $('#sidebarSubmitBtn').prop("disabled", false);
            $("#resetFilterToastBtn").prop("disabled", false);
            $("#audienceSubmitBtn").prop("disabled", false);
            $("#resetFilterToastBtn").html('<i class="fas fa-undo-alt"></i>&nbsp;Reset Filters');
            $("#downloadSubmitBtn").prop("disabled", false);
    });
    
}

var get_records_count =  function(records_data) {
        
    var records_count = $("#records-main-toast .toast-body");
    var records_count_toast = $("#records-toast .toast-body");
    var records_toast = $("#contacts-num-sidebar");
    var number_of_contacts = $("#numberOfContactsId");
    var eta_file_process = $("#eta_file_process");

    $.get("/api/meetpat-client/get-records/count", {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","), 
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( data ) {
    }).fail(function(data) {
        $("#contacts-number .spinner-block").hide();
        $("#contacts-number .toast-body").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
        console.log(data)
    }).done(function(data) {
        //console.log(data);
        records_count.html(kFormatter(data));
        records_toast.html(kFormatter(data));
        records_count_toast.html(kFormatter(data));
        number_of_contacts.val(data);
        if(data < 100000) {
            eta_file_process.html("30 seconds");
        } else if(data > 100000 && data < 300000) {
            eta_file_process.html("a minute");
        } else {
            eta_file_process.html("5 minutes or more");
        }

        $("#contacts-number .spinner-block").hide();

    });
}   

var set_records_count_progress_text =  function() {
        
    var attributes = $("#attributes_placeholder");
    var records = $("#records_placeholder");

    $.get("/api/meetpat-client/get-records/count", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $("#contacts-number .spinner-block").hide();
        $("#contacts-number .toast-body").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
        console.log(data)
    }).done(function(data) {
        //console.log(data);
        $("#progress_popup").show();
        attributes.html(kFormatter(data* 17));
        records.html(kFormatter(data));

        $("#contacts-number .spinner-block").hide();

    });
}   

var get_municipalities = function() {

    $.get("/api/meetpat-client/get-records/municipalities", {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","), vehicle_ownership_status: target_vehicle_owners.join(","),
         property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( data ) {
    }).fail(function(data) {
        $("#municipality-graph .spinner-block").hide();
        $("#municipality-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#municipality_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        console.log(data)
    }).done(function(data) {
        $("#municipality-graph .spinner-block").hide();    
        $("#municipality_filter").empty();
        ////console.log(data);
        drawMunicipalityChart(data);
        update_progress();
        get_ages();
    });

}

var get_provinces = function() {
    // First get count. 

    get_records_count();

    $.get("/api/meetpat-client/get-records/provinces", {user_id: user_id_number, province: target_provinces.join(","),
         age_group: target_ages.join(","), gender: target_genders.join(","), 
         population_group: target_population_groups.join(","), generation: target_generations.join(","),
         marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
         risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
         directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
         municipality: target_municipalities.join(","), area: target_areas.join(","),
         vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
         lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
         }, function( data ) {
    }).fail(function(data) {
        
        console.log(data)
    }).done(function(data) {
        //console.log(data);
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
        for (var key in data["distinct"]) {
            if(target_provinces.includes(data["distinct"][key]["province"])) {
                $("#province_filter").append(
                    '<input type="checkbox" name="' + data["distinct"][key]["province"] + '" id="' + data["distinct"][key]["province"].toLowerCase() + '_option' +'" value="' + data["distinct"][key]["province"] + '" class="css-checkbox" checked="checked"><label for="' + data["distinct"][key]["province"].toLowerCase() + '_option' +'" class="css-label">' + get_province_name(data["distinct"][key]["province"]) + '</label><br />'
                );
            } else {
                $("#province_filter").append(
                    '<input type="checkbox" name="' + data["distinct"][key]["province"] + '" id="' + data["distinct"][key]["province"].toLowerCase() + '_option' +'" value="' + data["distinct"][key]["province"] + '" class="css-checkbox"><label for="' + data["distinct"][key]["province"].toLowerCase() + '_option' +'" class="css-label">' + get_province_name(data["distinct"][key]["province"]) + '</label><br />'
                );
            }

            $('#' + data["distinct"][key]["province"].toLowerCase() + '_option').click(function(){
                if($('#' + $(this).attr("name").toLowerCase() + '_option').is(":checked")) { 
                    
                    var parent = this;

                    $("#province_filters").append('<li id="filter_p_' + $(this).attr("name").toLowerCase() + '">'+ get_province_name($(this).val()) +'<i class="fas fa-window-close float-right"></i></li>')
                    $('#filter_p_' + $(this).val().toLowerCase() + ' i').click(function() {
                        if($('#' + $(parent).val().toLowerCase() + '_option').length) {
                            $('#filter_p_' + $(parent).val().toLowerCase()).remove();
                            $("#" + $(parent).val().toLowerCase() + '_option').prop("checked", false);
                        }
                        checkForFilters();

                    });
                } else {
                    

                    if($('#filter_p_' + $(this).val().toLowerCase())) {
                        $('#filter_p_' + $(this).val().toLowerCase()).remove();
                    }
                }
                checkForFilters();

            });

        }
        
        drawProvinceChart(data["all"]);
        drawMapChart(data["all"]);
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
    get_property_valuation();
}

var get_property_valuation = function() {
    
    drawPropertyValuationChart();
    get_property_count_bucket();
}

var get_property_count_bucket = function() {
    
    drawpropertyCountBucketChart();
    get_vehicle_owner();
}

var get_vehicle_owner = function() {
    
    drawVehicleOwnerChart();
    get_risk_category();
}

var get_household_income = function() {
    
    drawHouseholdIncomeChart();
    get_director_of_business();
}

var get_risk_category = function() {
    
    drawRiskCategoryChart();
    get_lsm_group();
}

var get_lsm_group = function() {
    
    drawLSMGroupChart();
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
    $("#vehicle-owner-graph .spinner-block").show(); $("#vehicleOwnerChart").empty(); $("#vehicle_owner_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#lsm-group-graph .spinner-block").show(); $("#lsmGroupChart").empty(); $("#lsm_group_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#property-valuation-graph .spinner-block").show(); $("#propertyValuationChart").empty(); $("#property_valuation_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#property-count-bucket-graph .spinner-block").show(); $("#propertyCountBucketChart").empty(); $("#property_count_bucket_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');

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

$('.apply-filter-button, #sidebarSubmitBtn, #apply-toggle-button').click(function() {
    window.scrollTo(0, 0);
    checkForFilters();

    data_fetched = 0;
    $("#progress_popup .progress-bar").width("0%");
    $("#progress_popup").show();

    $('.apply-filter-button').prop("disabled", true);
    $('#sidebarSubmitBtn').prop("disabled", true);
    $('#audienceSubmitBtn').prop("disabled", true);
    $('.apply-filter-button').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;applying...');
    $('#sidebarSubmitBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Applying Filters...');
    $("#resetFilterToastBtn").prop("disabled", true);
    $(".apply-filter-button").prop("disabled", true);

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
    target_vehicle_owners = [];
    target_lsm_groups = [];
    target_property_valuations = [];
    target_property_count_buckets = [];
    

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

    $("#vehicle-owner-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_vehicle_owners.push($(this).val());
        }
    });

    $("#property-valuation-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_property_valuations.push($(this).val());
        }
    });

    $("#property-count-bucket-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_property_count_buckets.push($(this).val());
        }
    });

    $("#lsm-group-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_lsm_groups.push($(this).val());
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
    $("#areaContactsId").val(target_areas);
    $("#municipalityContactsID").val(target_municipalities);
    $("#AgeContactsId").val(target_ages);
    $("#GenderContactsId").val(target_genders);
    $("#populationContactsId").val(target_population_groups);
    $("#generationContactsId").val(target_generations);
    $("#citizenVsResidentsContactsId").val(target_citizen_vs_residents);
    $("#maritalStatusContactsId").val(target_marital_statuses);
    $("#homeOwnerContactsId").val(target_home_owners);
    $("#riskCategoryContactsId").val(target_risk_categories);
    $("#houseHoldIncomeContactsId").val(target_incomes);
    $("#directorsContactsId").val(target_directors);
    $("#vehicleOwnerContactsId").val(target_vehicle_owners);
    $("#lsmGroupContactsId").val(target_lsm_groups);
    $("#propertyValuationContactsId").val(target_property_valuations);
    $("#propertyCountBucketContactsId").val(target_property_count_buckets);
    
    apply_filters();
    get_provinces();
});

$("#resetFilterToastBtn, #reset-toggle-button").click(function() {
    window.scrollTo(0, 0);
    data_fetched = 0;
    $("#progress_popup .progress-bar").width("0%");
    $("#progress_popup").show();
    checkForFilters();
    $("#resetFilterToastBtn").prop("disabled", true);
    $("#resetFilterToastBtn").html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
        + '&nbsp;Resetting...'
    );
    $('.apply-filter-button, .apply-changes-button').prop('disabled', true);
    $('.sidebar-filters ul li').remove();
    $('.sidebar-filters ul').hide();
    $("#no_filters").show();
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
    target_vehicle_owners = [];
    target_lsm_groups = [];
    target_property_valuations = [];
    target_property_count_buckets = [];

    $("#provinceContactsId").val(target_provinces);
    $("#areaContactsId").val(target_areas);
    $("#municipalityContactsID").val(target_municipalities);
    $("#AgeContactsId").val(target_ages);
    $("#GenderContactsId").val(target_genders);
    $("#populationContactsId").val(target_population_groups);
    $("#generationContactsId").val(target_generations);
    $("#citizenVsResidentsContactsId").val(target_citizen_vs_residents);
    $("#maritalStatusContactsId").val(target_marital_statuses);
    $("#homeOwnerContactsId").val(target_home_owners);
    $("#riskCategoryContactsId").val(target_risk_categories);
    $("#houseHoldIncomeContactsId").val(target_incomes);
    $("#directorsContactsId").val(target_directors);
    $("#vehicleOwnerContactsId").val(target_vehicle_owners);
    $("#lsmGroupContactsId").val(target_lsm_groups);
    $("#propertyValuationContactsId").val(target_property_valuations);
    $("#propertyCountBucketContactsId").val(target_property_count_buckets);
    
    $('input:checkbox').each(function(el) {
        if($(el).is(':checked')) {
            $(el).prop('checked', false);
        }
    });

    apply_filters();
    get_provinces();
});

var get_saved_audiences = function() {
    
    $("#btn_prev").off();
    $("#btn_next").off();
    $("#userSavedFiles").html(
        `<div class="d-flex justify-content-center w-100">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>`
    )
    $.get('/api/meetpat-client/get-saved-audiences', {user_id: user_id_number}, function(data) {
        $("#userSavedFiles .d-flex").remove();
        if(numPages(data) >= current_page) {
            changePage(current_page, data);
        } else {
            changePage(current_page - 1, data);
            current_page = current_page - 1;
        }
        
    }).fail(function(data) {
        console.log(data);
    }).done(function(data) {
        if(data.length)
        {
            $("#btn_prev").on("click", function(e) {
                e.preventDefault();
                if (current_page > 1) {
                    current_page--;
                    changePage(current_page, data);
                }
            })
            
            $("#btn_next").on("click", function(e) {
                e.preventDefault();
                if (current_page < numPages(data)) {
                    current_page++;
                    changePage(current_page, data);
                }
            });
            
        } else {
            $("#userSavedFiles").append('<div class="col-12">You haved not saved any audiences yet.</div>');
        }

        
    });
}

$(document).ready(function() {
    set_records_count_progress_text();   
    get_saved_audiences();
    //var site_url = window.location.protocol + "//" + window.location.host;
    $('#records-main-toast').toast('show');
    $("#records-toast").toast('show');    
    
    $('.dropdown-menu, .dropdown-toggle').on('click', function(e) {
        if($(this).hasClass('dropdown-menu-form')) {
            e.stopPropagation();
        }
    });

    $("#SavedAudiencesModal").on('show.bs.modal', function() {
        $("#SaveAudienceModal").modal("hide");
        toggle_side_bar();
        
    });
    
    $("#SaveAudienceModal").on('show.bs.modal', function() {
        $("#SavedAudiencesModal").modal("hide");

        if($("#nameFile").val().length > 1 && $("#nameFile").val().match(/^([A-z\_0-9])\w+$/g))
        {
            $("#downloadSubmitBtn").prop("disabled", false);
        } else {
            $("#downloadSubmitBtn").prop("disabled", true);
        }
        
        toggle_side_bar();
    });

    $("#SavedAudiencesModal").on('hide.bs.modal', function() {       
        toggle_side_bar();
    });
    
    $("#SaveAudienceModal").on('hide.bs.modal', function() {
        toggle_side_bar();
    });

    get_provinces(); // Starts sequence to fetch data and draw charts.

    /** Sidebar toggling. */

    $('#sidebar-toggle-button').click(function() {
        toggle_side_bar();
    });
 
    //   var idx = lunr(function () {
    //     this.ref('Area');
    //     this.field('GreaterArea');
      
    //     areas_list.forEach(function (doc) {
    //       this.add(doc)
    //     }, this)
    //   });

    //   lunr_result = idx.search("modularity");

    //   //console.log(lunr_result);

    $("#nameFile").on('input', function() {
        if($("#nameFile").val().length > 1 && $("#nameFile").val().match(/^([A-z\_0-9])\w+$/g))
        {
            $("#nameFile").removeClass("is-invalid");
            $("#nameFile").addClass("is-valid");
            $("#downloadSubmitBtn").prop("disabled", false);
        } else {
            $("#nameFile").removeClass("is-valid");
            $("#nameFile").addClass("is-invalid");
            $("#downloadSubmitBtn").prop("disabled", true);
        }
    });

    $("#downloadSubmitBtn").click(function() {
        
        if(!file_name_exists($("#nameFile").val())) {
            $("#SaveAudienceModal .alerts").empty();
            var filter_form_data = {};
            $("#filtersForm").serializeArray().map(function(filter) {
                return filter_form_data[filter['name']] = filter['value'];
            });
    
            filter_form_data["file_name"] = $("#nameFile").val();
            //console.log(filter_form_data);
            $("#downloadSubmitBtn").prop("disabled", true);
            $("#downloadSubmitBtn").html(
                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
                + '&nbsp;Saving...'
            );
    
            $.post('/api/meetpat-client/filtered-audience/save', filter_form_data, function(data) {
                //console.log(data);
                
                
            }).fail(function(data) {
                
                $("#downloadSubmitBtn").prop("disabled", false);
                $("#downloadSubmitBtn").html(
                    '<i class="far fa-save"></i>&nbsp;Save Contacts'
                );
                $("#savedAudiencesForm .alerts").html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> There was an error saving file names. Please contact MeetPAT for support.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                `);
                $('#SavedAudiencesModal').modal('show');
                console.log(data);
            }).done(function(data) {
                
                

                var check_job_status = setInterval(function(){ 
                    $.post('/api/meetpat-client/saved-file-job-status', {id: data["job"]["id"]}, function(current_job) {
                        //console.log(current_job["job"]["status"]);
                    }).fail(function(error) {
                        console.log(error);
                    }).done(function(job_data) {
                        
                        if(job_data["job"]["status"] == "complete") {
                            clearInterval(check_job_status);
                            $("#downloadSubmitBtn").prop("disabled", false);
                            $("#downloadSubmitBtn").html(
                                '<i class="far fa-save"></i>&nbsp;Save Contacts'
                            );
                            $('#SavedAudiencesModal').modal('show');
                            get_saved_audiences();
                        }
                    });
                }, 3000);
                                   

            });
        } else {
            $("#SaveAudienceModal .alerts").html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> You can't have a file with the same name.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                `);
        }
        
    });

    $("#saveFileNameEdits").click(function() {
        
        if(no_same_file_names().length == 0) {
            $("#savedAudiencesForm .alerts").empty();
            var edit_form_data = {};
            var el_save = $(this);
            var already_disabled_next = $("#btn_next_item").hasClass("disabled");
            var already_disabled_prev = $("#btn_prev_item").hasClass("disabled");
            $("#savedAudiencesForm").serializeArray().map(function(filter) {
                return edit_form_data[filter['name']] = filter['value'];
            });
    
            edit_form_data["user_id"] = user_id_number;
    
            el_save.prop("disabled", true);
            el_save.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Saving...');
    
            if(!already_disabled_next) {
                $("#btn_next_item").addClass("disabled");
            }
            if(!already_disabled_prev) {
                $("#btn_prev_item").addClass("disabled");
            }
            $(".delete_file_btn").prop("disabled", true);
    
            $.post('/api/meetpat-client/save-filename-edits', edit_form_data, function(data) {
                //console.log(data);
                $("#savedAudiencesForm .alerts").html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> There was an error saving file names. Please contact MeetPAT for support.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                `);
            }).fail(function(data) {
                el_save.prop("disabled", false);
                el_save.html("Save Changes");
                if(!already_disabled_next) {
                    $("#btn_next_item").removeClass("disabled");
                }
                if(!already_disabled_prev) {
                    $("#btn_prev_item").removeClass("disabled");
                }
                $(".delete_file_btn").prop("disabled", false);
                el_save.prop("disabled", false);
                el_save.html("Save Changes");
                console.log(data);
            }).done(function(data) {
                $(".delete_file_btn").prop("disabled", false);
                if(!already_disabled_next) {
                    $("#btn_next_item").removeClass("disabled");
                }
                if(!already_disabled_prev) {
                    $("#btn_prev_item").removeClass("disabled");
                }
                get_saved_audiences();
                el_save.prop("disabled", false);
                el_save.html("Save Changes");
                //console.log( data );
                $("#savedAudiencesForm .alerts").html(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> File names updated successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
            });
        } else {
            $("#savedAudiencesForm .alerts").html(`
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> You can't have files with the same name.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `);
        }

        

    });

    /** Push progressbar down */

    
    initial_scroll = 145;
    window.addEventListener("scroll",function() { 
        if(window.scrollY < 120 && window.scrollY > 0) {
            
            $('#progress_popup').css({"padding-top": initial_scroll - window.scrollY + "px"});
        }
        else if(window.scrollY > 120) {
            $('#progress_popup').css({"padding-top": '25px'});
            
        }
        else {
            initial_scroll = 145;
            $('#progress_popup').css({"padding-top": '145px'});
        }
    },false);

    
    
    
    
});

