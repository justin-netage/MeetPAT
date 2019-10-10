// Load Google Chart Library
google.charts.load('current', {'packages':['corechart', 'geochart', 'bar'],
'mapsApiKey': 'AIzaSyBMae5h5YHUJ1BdNHshwj_SmJzPe5mglwI'});

function capitalizeFLetter(str) { 
    str = str.charAt(0).toUpperCase() + 
     str.slice(1); return str;
  } 

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

//Get province full name

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

// Subtract count of municipality and area selected
var sub_province_count = function(province_code) {
    switch(province_code) {
        case "G":
            count_G--;
            if(count_G == 0) {
                $("#" + province_code.toLowerCase() + "_option").prop("checked", false);

                if($('#filter_p_' + province_code.toLowerCase())) {
                    $('#filter_p_' + province_code.toLowerCase()).remove();
                }
            }
            break;
        case "WC":
            count_WC--;
            if(count_WC == 0) {
                $("#" + province_code.toLowerCase() + "_option").prop("checked", false);

                if($('#filter_p_' + province_code.toLowerCase())) {
                    $('#filter_p_' + province_code.toLowerCase()).remove();
                }
            }
            break;
        case "EC":
            count_EC--;
            if(count_EC == 0) {
                $("#" + province_code.toLowerCase() + "_option").prop("checked", false);

                if($('#filter_p_' + province_code.toLowerCase())) {
                    $('#filter_p_' + province_code.toLowerCase()).remove();
                }
            }
            break;
        case "M":
            count_M--;
            if(count_M == 0) {
                $("#" + province_code.toLowerCase() + "_option").prop("checked", false);

                if($('#filter_p_' + province_code.toLowerCase())) {
                    $('#filter_p_' + province_code.toLowerCase()).remove();
                }
            }
            break;
        case "NW":
            count_NW--;
            if(count_NW == 0) {
                $("#" + province_code.toLowerCase() + "_option").prop("checked", false);

                if($('#filter_p_' + province_code.toLowerCase())) {
                    $('#filter_p_' + province_code.toLowerCase()).remove();
                }
            }
            break;
        case "FS":
            count_FS--;
            if(count_FS == 0) {
                $("#" + province_code.toLowerCase() + "_option").prop("checked", false);

                if($('#filter_p_' + province_code.toLowerCase())) {
                    $('#filter_p_' + province_code.toLowerCase()).remove();
                }
            }
            break;
        case "L":
            count_L--;
            if(count_L == 0) {
                $("#" + province_code.toLowerCase() + "_option").prop("checked", false);

                if($('#filter_p_' + province_code.toLowerCase())) {
                    $('#filter_p_' + province_code.toLowerCase()).remove();
                }
            }
            break;
        case "KN":
            count_KN--;
            if(count_KN == 0) {
                $("#" + province_code.toLowerCase() + "_option").prop("checked", false);

                if($('#filter_p_' + province_code.toLowerCase())) {
                    $('#filter_p_' + province_code.toLowerCase()).remove();
                }
            }
            break;
        case "NC":
            count_NC--;
            if(count_NC == 0) {
                $("#" + province_code.toLowerCase() + "_option").prop("checked", false);

                if($('#filter_p_' + province_code.toLowerCase())) {
                    $('#filter_p_' + province_code.toLowerCase()).remove();
                }
            }
            break;
    }
}

// Check province method
var check_province = function(province_code, checked) {
    province_code_array = ["G", "WC", "KN", "M", "EC", "FS", "NC", "NW","L"];
    
    if(province_code_array.includes(province_code)) {
                            
        if(!$("#" + province_code.toLowerCase() + "_option").is(":checked")) {
            
            $("#" + province_code.toLowerCase() + "_option").prop("checked", true);
        } 

        switch(province_code) {
            case "G":
                
                    if(!checked && count_G != 0) {
                        count_G--;
                        if(count_G == 0) {
                            $("#" + province_code.toLowerCase() + "_option").prop("checked", false);
                            
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $('#filter_p_' + province_code.toLowerCase()).remove();
                            }
                        }
                    } else {
                        count_G++;

                        if(count_G == 1) {
                            if($('#filter_p_' + province_code.toLowerCase()).length == 0) {
                                $("#province_filters").append('<li id="filter_p_' + province_code.toLowerCase() + '">'+ get_province_name(province_code) +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_p_' + province_code.toLowerCase() + ' i').click(function() {
                                    if($('#' + province_code.toLowerCase() + '_option').length) {
                                        $('#filter_p_' + province_code.toLowerCase()).remove();
                                        $("#" + province_code.toLowerCase() + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            }
                        }
                    }

            break;
            case "WC":
                
                    if(!checked && count_WC != 0) {
                        count_WC--;
                        if(count_WC == 0) {
                            $("#" + province_code.toLowerCase() + "_option").prop("checked", false);
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $('#filter_p_' + province_code.toLowerCase()).remove();
                            }

                        } 
                    } else {
                        count_WC++;

                        if(count_WC == 1) {
                            if($('#filter_p_' + province_code.toLowerCase()).length == 0) {
                                $("#province_filters").append('<li id="filter_p_' + province_code.toLowerCase() + '">'+ get_province_name(province_code) +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_p_' + province_code.toLowerCase() + ' i').click(function() {
                                    if($('#' + province_code.toLowerCase() + '_option').length) {
                                        $('#filter_p_' + province_code.toLowerCase()).remove();
                                        $("#" + province_code.toLowerCase() + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            }
                        }
                    }

            break;
            case "KN":
                
                    if(!checked && count_KN != 0) {
                        count_KN--;
                        if(count_KN == 0) {
                            $("#" + province_code.toLowerCase() + "_option").prop("checked", false);
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $('#filter_p_' + province_code.toLowerCase()).remove();
                            }

                        } 
                    } else {
                        count_KN++;

                        if(count_KN == 1) {
                            if($('#filter_p_' + province_code.toLowerCase()).length == 0) {
                                $("#province_filters").append('<li id="filter_p_' + province_code.toLowerCase() + '">'+ get_province_name(province_code) +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_p_' + province_code.toLowerCase() + ' i').click(function() {
                                    if($('#' + province_code.toLowerCase() + '_option').length) {
                                        $('#filter_p_' + province_code.toLowerCase()).remove();
                                        $("#" + province_code.toLowerCase() + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            }
                        }
                    }

            break;
            case "M":
                
                    if(!checked && count_M != 0) {
                        count_M--;
                        if(count_M == 0) {
                            $("#" + province_code.toLowerCase() + "_option").prop("checked", false);
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $('#filter_p_' + province_code.toLowerCase()).remove();
                            }

                        } 
                    } else {
                        count_M++;

                        if(count_M == 1) {
                            if($('#filter_p_' + province_code.toLowerCase()).length == 0) {
                                $("#province_filters").append('<li id="filter_p_' + province_code.toLowerCase() + '">'+ get_province_name(province_code) +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_p_' + province_code.toLowerCase() + ' i').click(function() {
                                    if($('#' + province_code.toLowerCase() + '_option').length) {
                                        $('#filter_p_' + province_code.toLowerCase()).remove();
                                        $("#" + province_code.toLowerCase() + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            }
                        }
                    }
                                                
            break;
            case "EC":
                
                    if(!checked && count_EC != 0) {
                        count_EC--;
                        if(count_EC == 0) {
                            $("#" + province_code.toLowerCase() + "_option").prop("checked", false);
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $('#filter_p_' + province_code.toLowerCase()).remove();
                            }

                        } 
                    } else {
                        count_EC++;

                        if(count_EC == 1) {
                            if($('#filter_p_' + province_code.toLowerCase()).length == 0) {
                                $("#province_filters").append('<li id="filter_p_' + province_code.toLowerCase() + '">'+ get_province_name(province_code) +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_p_' + province_code.toLowerCase() + ' i').click(function() {
                                    if($('#' + province_code.toLowerCase() + '_option').length) {
                                        $('#filter_p_' + province_code.toLowerCase()).remove();
                                        $("#" + province_code.toLowerCase() + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            }
                        }
                    }
                                                
            break;
            case "FS":
                
                    if(!checked && count_FS != 0) {
                        count_FS--;
                        if(count_FS == 0) {
                            $("#" + province_code.toLowerCase() + "_option").prop("checked", false);
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $('#filter_p_' + province_code.toLowerCase()).remove();
                            }

                        }
                    } else {
                        count_FS++;

                        if(count_FS == 1) {
                            if($('#filter_p_' + province_code.toLowerCase()).length == 0) {
                                $("#province_filters").append('<li id="filter_p_' + province_code.toLowerCase() + '">'+ get_province_name(province_code) +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_p_' + province_code.toLowerCase() + ' i').click(function() {
                                    if($('#' + province_code.toLowerCase() + '_option').length) {
                                        $('#filter_p_' + province_code.toLowerCase()).remove();
                                        $("#" + province_code.toLowerCase() + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            }
                        }
                    }
                
            break;
            case "NC":
                    if(!checked && count_NC != 0) {
                        count_NC--;
                        if(count_NC == 0) {
                            $("#" + province_code.toLowerCase() + "_option").prop("checked", false);
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $('#filter_p_' + province_code.toLowerCase()).remove();
                            }

                        }
                    } else {
                        count_NC++;

                        if(count_G == 1) {
                            if($('#filter_p_' + province_code.toLowerCase()).length == 0) {
                                $("#province_filters").append('<li id="filter_p_' + province_code.toLowerCase() + '">'+ get_province_name(province_code) +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_p_' + province_code.toLowerCase() + ' i').click(function() {
                                    if($('#' + province_code.toLowerCase() + '_option').length) {
                                        $('#filter_p_' + province_code.toLowerCase()).remove();
                                        $("#" + province_code.toLowerCase() + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            }
                        }
                    }
                
            break;
            case "NW":
                
                    if(!checked && count_NW != 0) {
                        count_NW--;
                        if(count_NW == 0) {
                            $("#" + province_code.toLowerCase() + "_option").prop("checked", false);
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $('#filter_p_' + province_code.toLowerCase()).remove();
                            }

                        }
                    } else {
                        count_NW++;

                        if(count_G == 1) {
                            if($('#filter_p_' + province_code.toLowerCase()).length == 0) {
                                $("#province_filters").append('<li id="filter_p_' + province_code.toLowerCase() + '">'+ get_province_name(province_code) +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_p_' + province_code.toLowerCase() + ' i').click(function() {
                                    if($('#' + province_code.toLowerCase() + '_option').length) {
                                        $('#filter_p_' + province_code.toLowerCase()).remove();
                                        $("#" + province_code.toLowerCase() + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            }
                        }
                    }
                    
                
            break;
            case "L":
                
                    if(!checked && count_L != 0) {
                        count_L--;
                        if(count_L == 0) {
                            $("#" + province_code.toLowerCase() + "_option").prop("checked", false);
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $('#filter_p_' + province_code.toLowerCase()).remove();
                            }

                        }
                    } else {
                        count_L++;

                        if(count_G == 1) {
                            if($('#filter_p_' + province_code.toLowerCase())) {
                                $("#province_filters").append('<li id="filter_p_' + province_code.toLowerCase() + '">'+ get_province_name(province_code) +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_p_' + province_code.toLowerCase() + ' i').click(function() {
                                    if($('#' + province_code.toLowerCase() + '_option').length) {
                                        $('#filter_p_' + province_code.toLowerCase()).remove();
                                        $("#" + province_code.toLowerCase() + '_option').prop("checked", false);
                                    }
                                    checkForFilters();

                                });
                            }
                        }
                    }
                
            break;
        
        }

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

var close_side_bar = function() {
    if(!$("#sidebar-toggle-button").hasClass("sidebar-button-in")) {
        $("#sidebar-toggle-button").html('<i class="fas fa-cog"></i>');
        $('#right-options-sidebar').addClass("sidebar-in");
        $('#sidebar-toggle-button').addClass("sidebar-button-in");
    }     
}

var data_fetched = 0;

var update_progress = function() {
    if(data_fetched != 19) {
        data_fetched++;
    }
    $("#progress_popup .progress-bar").width(Math.round((data_fetched/19) * 100) + "%");
    $("#progress_popup .progress-bar").attr("aria-valuenow", Math.round((data_fetched/19) * 100))
    //console.log(data_fetched);
}

var hide_progress = function() {
    setTimeout(function() {
        $("#progress_popup").hide();
    },1000);    
}

// Clear checked inputs

var clear_checked_inputs = function() {    
        array_checked = $("#areaContactsId").val().split(",");
        
        $.each($("#area-filter-form #lunr-results input"), function(index, field) {
            if(array_checked.includes($(field).val())) {
                $(field).prop("checked", true);
            } else {
                $(field).prop("checked", false);
            }
        });
    
    
}

// Saved Audience Files methods

var user_id_number = $("#user_id").val();
var user_auth_token = $("#user_auth_token").val();
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
        

        if($("#userSavedFiles").length && data.length) {
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
    } else {
        $(".delete_file_btn").prop("disabled", false);
        $(".page-item").removeClass("disabled");
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
var target_primary_property_types = [];
var target_branches = [];

var count_G = 0;
var count_WC = 0;
var count_KN = 0;
var count_M = 0;
var count_EC = 0;
var count_FS = 0;
var count_NC = 0;
var count_NW = 0;
var count_L = 0;

var checkForFilters = function() {
    var target_provinces_el = document.getElementById("province_filters") ;var target_municipalities_el = document.getElementById("municipality_filters");
    var target_areas_el = document.getElementById("area_filters") ;var target_ages_el = document.getElementById("age_filters");
    var target_genders_el = document.getElementById("gender_filters") ;var target_population_groups_el = document.getElementById("population_group_filters");
    var target_generations_el = document.getElementById("generation_filters") ;var target_citizen_vs_residents_el = document.getElementById("citizen_vs_resident_filters");
    var target_marital_statuses_el = document.getElementById("marital_status_filters") ;var target_home_owners_el = document.getElementById("home_owner_filters");
    var target_risk_categories_el = document.getElementById("risk_category_filters") ;var target_incomes_el = document.getElementById("household_income_filters");
    var target_directors_el = document.getElementById("directors_filters") ;var target_vehicle_owners_el = document.getElementById("vehicle_owner_filters");
    var target_lsm_group_el = document.getElementById("lsm_group_filters") ;var target_property_valuations_el = document.getElementById("property_valuation_filters");
    var target_property_count_buckets_el = document.getElementById("property_count_bucket_filters"); var target_primary_property_types_el = document.getElementById("primary_property_type_filters");
    var target_branches_el = document.getElementById("branches_filters");

    if(
        target_provinces_el.childNodes.length > 1 || target_municipalities_el.childNodes.length > 1 ||
        target_areas_el.childNodes.length > 1 || target_ages_el.childNodes.length > 1 ||
        target_genders_el.childNodes.length > 1 || target_population_groups_el.childNodes.length > 1 ||
        target_generations_el.childNodes.length > 1 || target_citizen_vs_residents_el.childNodes.length > 1 ||
        target_marital_statuses_el.childNodes.length > 1 || target_home_owners_el.childNodes.length > 1 ||
        target_risk_categories_el.childNodes.length > 1 || target_incomes_el.childNodes.length > 1 ||
        target_directors_el.childNodes.length > 1 || target_vehicle_owners_el.childNodes.length > 1 ||
        target_lsm_group_el.childNodes.length > 1 || target_property_valuations_el.childNodes.length > 1 ||
        target_property_count_buckets_el.childNodes.length > 1 || target_primary_property_types_el.childNodes.length > 1 ||
        target_branches_el.childNodes.length > 1
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
        if (target_primary_property_types_el.childNodes.length > 1) {$("#primary_property_type_filters").show()} else {$("#primary_property_type_filters").hide()};
        if (target_branches_el.childNodes.length > 1) {$("#branches_filters").show()} else {$("#branches_filters").hide()};
        
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

/** Draw Graphs asynchronously */   
function DrawLocationCharts() {
    
    data = {    user_id: user_id_number, province: target_provinces.join(","),
                age_group: target_ages.join(","), gender: target_genders.join(","), 
                population_group: target_population_groups.join(","), generation: target_generations.join(","),
                marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
                risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
                directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
                municipality: target_municipalities.join(","), area: target_areas.join(","),
                vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
                lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
                primary_property_type: target_primary_property_types.join(","), custom_variable_1: target_branches.join(","), api_token: user_auth_token
            }

    $.ajax({
        url: "/api/meetpat-client/get-location-data",
        type: "GET",
        data: data,
        success: function(data) {
            //Records Count
            var records_count = $("#records-main-toast .toast-body");
            var records_count_toast = $("#records-toast .toast-body");
            var records_toast = $("#contacts-num-sidebar");
            var number_of_contacts = $("#numberOfContactsId");
            var eta_file_process = $("#eta_file_process");

            records_count.html(kFormatter(data["count"][0]["count"]));
            records_toast.html(kFormatter(data["count"][0]["count"]));
            records_count_toast.html(kFormatter(data["count"][0]["count"]));
            number_of_contacts.val(data["count"][0]["count"]);
            if(data["count"][0]["count"] < 100000) {
                eta_file_process.html("30 seconds");
            } else if(data["count"][0]["count"] > 100000 && data["count"][0]["count"] < 300000) {
                eta_file_process.html("a minute");
            } else {
                eta_file_process.html("5 minutes or more");
            }
    
            $("#contacts-number .spinner-block").hide();


            $("#province_filter").empty();
        
        for (var key in data["provinces_distinct"]) {
            if(target_provinces.includes(data["provinces_distinct"][key]["province"])) {
                $("#province_filter").append(
                    '<input type="checkbox" name="' + data["provinces_distinct"][key]["province"] + '" id="' + data["provinces_distinct"][key]["province"].toLowerCase() + '_option' +'" value="' + data["provinces_distinct"][key]["province"] + '" class="css-checkbox" checked="checked"><label for="' + data["provinces_distinct"][key]["province"].toLowerCase() + '_option' +'" class="css-label">' + get_province_name(data["provinces_distinct"][key]["province"]) + '</label><br />'
                );
            } else {
                $("#province_filter").append(
                    '<input type="checkbox" name="' + data["provinces_distinct"][key]["province"] + '" id="' + data["provinces_distinct"][key]["province"].toLowerCase() + '_option' +'" value="' + data["provinces_distinct"][key]["province"] + '" class="css-checkbox"><label for="' + data["provinces_distinct"][key]["province"].toLowerCase() + '_option' +'" class="css-label">' + get_province_name(data["provinces_distinct"][key]["province"]) + '</label><br />'
                );
            }

            $('#' + data["provinces_distinct"][key]["province"].toLowerCase() + '_option').click(function(){
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

            chart_data_province = data["provinces"];

            $("#map-graph .spinner-block").hide();    
            var result_map = Object.keys(chart_data_province).map(function(key) {
            var value;
                switch(chart_data_province[key]["province"]) {
                    case 'G':
                    value =  ['ZA-GT', chart_data_province[key]["audience"], '<ul class="list-unstyled"><li><b>Gauteng</b></li><li>'+ kFormatter(chart_data_province[key]["audience"]) +'</li></ul>'];
                        break;
                    case 'WC':
                    value =  ['ZA-WC', chart_data_province[key]["audience"], '<ul class="list-unstyled"><li><b>Western Cape</b></li><li>'+ kFormatter(chart_data_province[key]["audience"]) +'</li></ul>'];
                        break;
                    case 'EC':
                    value =  ['ZA-EC', chart_data_province[key]["audience"], '<ul class="list-unstyled"><li><b>Eastern Cape</b></li><li>'+ kFormatter(chart_data_province[key]["audience"]) +'</li></ul>'];
                        break;
                    case 'M':
                    value =  ['ZA-MP', chart_data_province[key]["audience"], '<ul class="list-unstyled"><li><b>Mpumalanga</b></li><li>'+ kFormatter(chart_data_province[key]["audience"]) +'</li></ul>'];
                        break;  
                    case 'FS':
                    value =  ['ZA-FS', chart_data_province[key]["audience"], '<ul class="list-unstyled"><li><b>Free State</b></li><li>'+ kFormatter(chart_data_province[key]["audience"]) +'</li></ul>'];
                        break;
                    case 'L':
                    value =  ['ZA-LP', chart_data_province[key]["audience"], '<ul class="list-unstyled"><li><b>Limpopo</b></li><li>'+ kFormatter(chart_data_province[key]["audience"]) +'</li></ul>'];
                        break;  
                    case 'KN':
                    value =  ['ZA-NL', chart_data_province[key]["audience"], '<ul class="list-unstyled"><li><b>KwaZula Natal</b></li><li>'+ kFormatter(chart_data_province[key]["audience"]) +'</li></ul>'];
                        break; 
                    case 'NW':
                    value =  ['ZA-NW', chart_data_province[key]["audience"], '<ul class="list-unstyled"><li><b>North West Province</b></li><li>'+ kFormatter(chart_data_province[key]["audience"]) +'</li></ul>'];
                        break;      
                    case 'NC':
                    value =  ['ZA-NC', chart_data_province[key]["audience"], '<ul class="list-unstyled"><li><b>Northern Cape</b></li><li>'+ kFormatter(chart_data_province[key]["audience"]) +'</li></ul>'];
                        break;
                    default:
                        value = "";               
                    }
        
                    
                    return value;
            
              });
              
              result_map.unshift(['Provinces', 'Popularity', {role: 'tooltip', p:{html:true}}]);
              var filtered = result_map.filter(function (el) {
                return el != "";
              });
        
              var data_map = google.visualization.arrayToDataTable(filtered);
        
              var options_map = {
                  region:'ZA',resolution:'provinces',
                  'backgroundColor': '#f7f7f7',
                  'colorAxis': {colors: ['#039be5']},
                  tooltip: {
                    isHtml: true
                }
                };
        
              var chart_map = new google.visualization.GeoChart(document.getElementById('chartdiv'));
        
              chart_map.draw(data_map, options_map);
              update_progress();

              $("#province-graph .spinner-block").hide();    

                var data_province = new google.visualization.DataTable();
                data_province.addColumn('string', 'Province');
                data_province.addColumn('number', 'Records');
                data_province.addColumn({type: 'string', role: 'annotation'});

                var result = Object.keys(chart_data_province).map(function(key) {

                    var province;
                    
                    switch(chart_data_province[key]["province"]) {
                        case 'G':
                            province = ['Gauteng', chart_data_province[key]["audience"], kFormatter(chart_data_province[key]["audience"])];
                            break;
                        case 'EC':
                            province = ['Eastern Cape', chart_data_province[key]["audience"], kFormatter(chart_data_province[key]["audience"])];
                            break;
                        case 'NC':
                            province = ['Northern Cape', chart_data_province[key]["audience"], kFormatter(chart_data_province[key]["audience"])];
                            break;
                        case 'FS':
                            province = ['Free State', chart_data_province[key]["audience"],kFormatter(chart_data_province[key]["audience"])];
                            break;
                        case 'L':
                            province = ['Limpopo', chart_data_province[key]["audience"], kFormatter(chart_data_province[key]["audience"])];
                            break;
                        case 'KN':
                            province = ['KwaZulu Natal', chart_data_province[key]["audience"], kFormatter(chart_data_province[key]["audience"])];
                            break;
                        case 'M':
                            province = ['Mpumalanga', chart_data_province[key]["audience"], kFormatter(chart_data_province[key]["audience"])];
                            break;
                        case 'NW':
                            province = ['North West', chart_data_province[key]["audience"], kFormatter(chart_data_province[key]["audience"])];
                            break;
                        case 'WC':
                            province = ['Western Cape', chart_data_province[key]["audience"], kFormatter(chart_data_province[key]["audience"])];
                            break;
                        default:
                            province = [chart_data_province[key]["province"], chart_data_province[key]["audience"], kFormatter(chart_data_province[key]["audience"])];
                        }
                
                    return province;
                    });
                    //console.log(result);
                    data_province.addRows(result);
                    var chart_options_province = {
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
                    var chart_provinces = new google.visualization.BarChart(document.getElementById('provincesChart'));
                    chart_provinces.draw(data_province, chart_options_province);
                    update_progress();

                    // Municipality Graph
                    $("#municipality-graph .spinner-block").hide();    
                    $("#municipality_filter").empty();
                    var data_municipalities = new google.visualization.DataTable();
                    data_municipalities.addColumn('string', 'Municipality');
                    data_municipalities.addColumn('number', 'Records');
                    data_municipalities.addColumn({type: 'string', role: 'annotation'});

                    var chart_data_muncipalities = data["municipalities"];
                    var result_municipality = Object.keys(chart_data_muncipalities).map(function(key) {
                        return [chart_data_muncipalities[key]["municipality"], chart_data_muncipalities[key]["audience"], kFormatter(chart_data_muncipalities[key]["audience"])];
                    });

                    data_municipalities.addRows(result_municipality);
                    // Set chart options
                    if(result_municipality.length > 10) {
                        var chart_options_municipality = {
                            'height': result_municipality.length * 25,
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
                    } else {
                        var chart_options_municipality = {
                            //'height': result_municipality.length * 25,
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
                    }
            
                    data["municipality_distinct"].forEach(function(result) {
                        
                        if(target_municipalities.includes(result.municipality)) {
                            $("#municipality_filter").append(
                                '<input type="checkbox" name="' + result.municipality.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '" id="municipality_' + result.municipality.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option' +'" value="' + result.municipality + '" class="css-checkbox" checked="checked"><label for="municipality_' + result.municipality.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option' +'" class="css-label">' + result.municipality + ' (' + result.province + ') </label><br />'
                            );
                        } else {
                            $("#municipality_filter").append(
                                '<input type="checkbox" name="' + result.municipality.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '" id="municipality_' + result.municipality.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option' +'" value="' + result.municipality + '" class="css-checkbox"><label for="municipality_' + result.municipality.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option' +'" class="css-label">' + result.municipality + ' (' + result.province + ') </label><br />'
                            );
                        }

                        $('#municipality_' + result.municipality.toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').click(function(){
                            if($('#municipality_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').is(":checked")) { 
                                check_province(result.province, true);
                                
                                var parent = this;
            
                                $("#municipality_filters").append('<li id="filter_municipality_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                $('#filter_municipality_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + ' i').click(function() {
                                    if($('#municipality_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').length) {
                                        $('#filter_municipality_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_')).remove();
                                        $("#municipality_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_') + '_option').prop("checked", false);
                                    }
                                    sub_province_count(result.province);
                                    checkForFilters();

                                });
                            } else {
                                check_province(result.province, false);
                                
                                if($('#filter_municipality_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_'))) {
                                    $('#filter_municipality_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/\./g, '_')).remove();
                                }
                            }
                            checkForFilters();

                        });
                    });
                        
            // Instantiate and draw our chart, passing in some options.
            var chart_municipality = new google.visualization.BarChart(document.getElementById('municipalityChart'));
            chart_municipality.draw(data_municipalities, chart_options_municipality);
            update_progress();

            // Areas Chart and Search
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
            var data_area = new google.visualization.DataTable();
            data_area.addColumn('string', 'Area');
            data_area.addColumn('number', 'Records');
            data_area.addColumn({type: 'string', role: 'annotation'});

            var result_areas = Object.keys(data["areas"]).map(function(key) {
                return [data["areas"][key]["area"], data["areas"][key]["audience"], kFormatter(data["areas"][key]["audience"])];
                });

            var shorter_result = result_areas.slice(0, 20);
            data_area.addRows(shorter_result);
            // Set chart options

            if(shorter_result.length > 10) {
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
            } else {
                var chart_options = {
                    'width':'100%',
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
            }
        
            // Instantiate and draw our chart, passing in some options.
            var chart_area = new google.visualization.BarChart(document.getElementById('areasChart'));
            chart_area.draw(data_area, chart_options); 

            var results_areas = Object.keys(data["areas_distinct"]).map(function(key) {
                return {"name": data["areas_distinct"][key]["area"], "province": data["areas_distinct"][key]["province"],
                        "municipality": data["areas_distinct"][key]["municipality"],
                        "count": kFormatter(data["areas_distinct"][key]["audience"])};
            });
            // get municipalities
            var municipalities_unique = [];
            results_areas.forEach(function(result_item) {
                if(!municipalities_unique.includes(result_item.municipality)) {
                    municipalities_unique.push(result_item.municipality);
                }
            });

            var municipalities = Object.keys(municipalities_unique).map(function(key) {
                return {"name": municipalities_unique[key], "format_name": municipalities_unique[key].toLowerCase().replace(/ /g, "_").replace(/\./g, '_'), "count": 0};
            });

            var sub_municipality_count = function(municipality_name) {
                var municipality_tmp = municipalities.filter(obj => {if(obj.name === municipality_name) { return obj}}).map(function(obj) { return obj});
                
                    municipality_tmp[0].count--;
                    if(municipality_tmp[0].count == 0) {
                        $("#municipality_" + municipality_tmp[0].format_name.toLowerCase() + "_option").prop("checked", false);
        
                        if($('#filter_municipality_' + municipality_tmp[0].format_name)) {
                            $('#filter_municipality_' + municipality_tmp[0].format_name).remove();
                        }
                    }
    
                    checkForFilters();
                 
                
            }
            
            // When area is checked check binding municipality.
            var check_municipality = function(municipality_name, checked) {
                var municipality_tmp = municipalities.filter(obj => {if(obj.name === municipality_name) { return obj}}).map(function(obj) { return obj});
                
                if(!$("#municipality_" + municipality_tmp[0].format_name + "_option").is(":checked")) {
            
                    $("#municipality_" + municipality_tmp[0].format_name + "_option").prop("checked", true);
                    
                } 

                if(!checked && municipality_tmp[0].count != 0) {
                    municipality_tmp[0].count--;
                    if(municipality_tmp[0].count == 0) {
                        $("#municipality_" + municipality_tmp[0].format_name + "_option").prop("checked", false);
                        
                        if($('#filter_municipality_' + municipality_tmp[0].format_name)) {
                            $('#filter_municipality_' + municipality_tmp[0].format_name).remove();
                        }
                    }
                } else {
                    municipality_tmp[0].count++;

                    if(municipality_tmp[0].count == 1) {
                        if($('#filter_municipality_' + municipality_tmp[0].format_name).length == 0) {
                            $("#municipality_filters").append('<li id="filter_municipality_' + municipality_tmp[0].format_name + '">'+ municipality_tmp[0].format_name +'<i class="fas fa-window-close float-right"></i></li>')
                            $('#filter_municipality_' + municipality_tmp[0].format_name + ' i').click(function() {
                                if($('#municipality_' + municipality_tmp[0].format_name + '_option').length) {
                                    $('#filter_municipality_' + municipality_tmp[0].format_name).remove();
                                    $("#municipality_" + municipality_tmp[0].format_name + '_option').prop("checked", false);
                                }
                                
                                if(municipality_tmp[0].count == 0) {
                                    $("#municipality_" + municipality_tmp[0].format_name + "_option").prop("checked", false);
                                    
                                    if($('#filter_municipality_' + municipality_tmp[0].format_name)) {
                                        $('#filter_municipality_' + municipality_tmp[0].format_name).remove();
                                    }
                                }
                                
                                checkForFilters();

                            });
                        }
                    }
                }
                
            }
            // Update counts for municipalities selected.
            $("#hidden-area-filter-form input").serializeArray().forEach(function(input_item) {
                    
                    results_areas.filter(obj => {
                        
                        if(obj.name === input_item.value) { 
                            
                            municipalities.filter(obj_m => {
                                if(obj_m.name == obj.municipality) {
                                    obj_m.count++;
                                }
                            })
                        };
                    });
                    
            });

            

            var documents = results_areas;
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
                    $("#lunr-results").append('<input type="checkbox" name="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" class="css-checkbox" checked="checked"><label for="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '<small> ' 
                    + results_areas.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0] + '</small> (' 
                    + get_province_name(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0]) + ')</label><br />');
                    $('#area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                        
                        if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                            check_province(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0], true);
                            check_municipality(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], true);
                            var parent = this;
                            $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" checked="checked">');
                            $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                            $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                
                                if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                    $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                    $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                    $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                }
                                sub_province_count(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0]);
                                sub_municipality_count(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0]);
                                checkForFilters();
                            });
                        } else {
                            
                            check_province(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0], false);
                            check_municipality(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], false);
                            if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                                $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                            }
                        }
                        checkForFilters();
                    });                        
                } else {
                    $("#lunr-results").append('<input type="checkbox" name="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" class="css-checkbox"><label for="area_' 
                    + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '<small> ' 
                    + results_areas.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0] + '</small> (' 
                    + get_province_name(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0]) + ')</label><br />');
                    $('#area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                        
                        if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                            check_province(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0], true);
                            check_municipality(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], true);
                            var parent = this;
                            $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" checked="checked">');
                            $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                            $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                    $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                    $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                    $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                }
                                sub_province_count(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0]);
                                sub_municipality_count(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0]);
                                checkForFilters();

                            });
                        } else {
                            
                            check_province(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0], false);
                            check_municipality(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], false);
                            if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                                $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();

                            }
                        }
                        checkForFilters();

                    });                        
                }
                //clear_checked_inputs();
            });
            // Append checked inputs to hidden form...
            document.getElementById('areaSearchInput').addEventListener('keyup', function() {
                if(idx.search(this.value + "*").length && this.value != '') {
                    $("#lunr-results").empty();
                    
                    idx.search(this.value + "*").forEach(function(result) {
                        
                        if(result.score) {
                            if($('#area_hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                $("#lunr-results").append('<input type="checkbox" name="' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result.ref + '" class="css-checkbox" checked="checked"><label for="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result.ref + '<small> ' 
                                + results_areas.filter(obj => {if(obj.name === result.ref) { return obj.count}}).map(function(obj) { return obj.count})[0] + '</small> (' 
                                + get_province_name(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.province}}).map(function(obj) { return obj.province})[0]) + ')</label><br />');
                                $('#area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                                    
                                    if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                                        check_province(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.province}}).map(function(obj) { return obj.province})[0], true);
                                        check_municipality(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], true);
                                        var parent = this;
                                        $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result.ref + '" checked="checked">');
                                        $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                        $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                        
                                            if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                                $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                                $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                                $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                            }
                                            sub_province_count(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.province}}).map(function(obj) { return obj.province})[0]);
                                            sub_municipality_count(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0]);
                                            checkForFilters();
                                        });
                                    } else {
                                        
                                        check_province(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.province}}).map(function(obj) { return obj.province})[0], false);
                                        check_municipality(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], false);
                                        if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                                            $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                            $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                        }
                                    }
                                    checkForFilters();
                                });                        
                            } else {
                                $("#lunr-results").append('<input type="checkbox" name="' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result.ref + '" class="css-checkbox"><label for="area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result.ref + '<small> ' 
                                + results_areas.filter(obj => {if(obj.name === result.ref) { return obj.count}}).map(function(obj) { return obj.count})[0] + '</small> (' 
                                + get_province_name(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.province}}).map(function(obj) { return obj.province})[0]) + ')</label><br />');
                                $('#area_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                                    if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 

                                        check_province(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.province}}).map(function(obj) { return obj.province})[0], true);
                                        check_municipality(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], true);
                                        var parent = this;
                                        $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result.ref.toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result.ref + '" checked="checked">');
                                        $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                        $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                            if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                                $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                                $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                                $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                            }
                                            sub_province_count(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.province}}).map(function(obj) { return obj.province})[0]);
                                            sub_municipality_count(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0]);
                                            checkForFilters();

                                        });
                                    } else {
                                        
                                        check_province(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.province}}).map(function(obj) { return obj.province})[0], false);
                                        check_municipality(results_areas.filter(obj => {if(obj.name === result.ref) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], false);
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
                            $("#lunr-results").append('<input type="checkbox" name="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" class="css-checkbox" checked="checked"><label for="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '<small> ' 
                            + results_areas.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0] + '</small> (' 
                            + get_province_name(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0]) + ')</label><br />');
                            $('#area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                                
                                if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                                    check_province(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0], true);
                                    check_municipality(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], true);
                                    var parent = this;
                                    $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" checked="checked">');
                                    $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                        
                                        if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                            $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                            $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                            $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                        }
                                        sub_province_count(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0]);
                                        sub_municipality_count(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0]);
                                        checkForFilters();
                                    });
                                } else {
                                    check_province(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0], false);
                                    check_municipality(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], false);
                
                                    if($('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, ""))) {
                                        $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                        $('#area_hidden_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                    }
                                }
                                checkForFilters();
                            });                        
                        } else {
                            $("#lunr-results").append('<input type="checkbox" name="' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" class="css-checkbox"><label for="area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" class="css-label">' + result[0] + '<small> ' 
                            + results_areas.filter(obj => {if(obj.name === result[0]) { return obj.count}}).map(function(obj) { return obj.count})[0] + '</small> (' 
                            + get_province_name(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0]) + ')</label><br />');
                            $('#area_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').click(function(){
                                if($('#area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').is(":checked")) { 
                                    
                                    check_province(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0], true);
                                    check_municipality(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], true);
                                    var parent = this;
                                    $("#hidden-area-filter-form").append('<input type="checkbox" name="hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '" id="area_hidden_' + result[0].toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option' +'" value="' + result[0] + '" checked="checked">');
                                    $("#area_filters").append('<li id="filter_area_' + $(this).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_area_' + $(this).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + ' i').click(function() {
                                        if($('#area_hidden_' + $(parent).attr("name").toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').length) {
                                            $('#filter_area_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "")).remove();
                                            $('#area_hidden_' + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').remove();
                                            $("#area_" + $(parent).val().toLowerCase().replace(/ /g, "_").replace(/[\'&()]/g, "") + '_option').prop("checked", false);
                                        }
                                        sub_province_count(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0]);
                                        sub_municipality_count(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0]);
                                        checkForFilters();

                                    });
                                } else {
                                    check_province(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.province}}).map(function(obj) { return obj.province})[0], false);
                                    check_municipality(results_areas.filter(obj => {if(obj.name === result[0]) { return obj.municipality}}).map(function(obj) { return obj.municipality})[0], false);
                
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

                //clear_checked_inputs();
            });
            update_progress();
                    
        }

    }).done(function() {
        drawDemographicGraphs();
    }).fail(function(error) {
        $("#contacts-number .spinner-block").hide();
        $("#contacts-number .toast-body").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        $("#province-graph .spinner-block").hide();
        $("#province-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#province_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
        $("#map-graph .spinner-block").hide();
        $("#map-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        
        $("#area-graph .spinner-block").hide();
        $("#area-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#area_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
        console.log(error);
    });

}

function drawDemographicGraphs() {

    data = {    user_id: user_id_number, province: target_provinces.join(","),
                age_group: target_ages.join(","), gender: target_genders.join(","), 
                population_group: target_population_groups.join(","), generation: target_generations.join(","),
                marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
                risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
                directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
                municipality: target_municipalities.join(","), area: target_areas.join(","),
                vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
                lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
                primary_property_type: target_primary_property_types.join(","), custom_variable_1: target_branches.join(","), api_token: user_auth_token
            }
    // Age
    $.ajax({
        url: "/api/meetpat-client/get-demographic-data",
        type: "GET",
        data: data,
        success: function(data) {
            // Ages Graph
            $("#age-graph .spinner-block").hide();    
            $("#age_filter").empty();
    
            var data_ages = new google.visualization.DataTable();
            data_ages.addColumn('string', 'Age');
            data_ages.addColumn('number', 'Records');
            data_ages.addColumn({type: 'string', role: 'annotation'});
    
            var result_ages = Object.keys(data["ages"]).map(function(key) {
                return [data["ages"][key]["ageGroup"], data["ages"][key]["audience"], kFormatter(data["ages"][key]["audience"])];
              });
        
                data_ages.addRows(result_ages);
                // Set chart options
                var chart_options_ages = {
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

                for (var key in data["ages_distinct"]) {
                    if(target_ages.includes(data["ages_distinct"][key]["ageGroup"])) {
                        $("#age_filter").append(
                            '<input type="checkbox" name="' + data["ages_distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '" id="age_' + data["ages_distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" value="' + data["ages_distinct"][key]["ageGroup"] + '" class="css-checkbox" checked="checked"><label for="age_' + data["ages_distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" class="css-label">' + data["ages_distinct"][key]["ageGroup"] + '</label><br />'
                        );
                    } else {
                        $("#age_filter").append(
                            '<input type="checkbox" name="' + data["ages_distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '" id="age_' + data["ages_distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" value="' + data["ages_distinct"][key]["ageGroup"] + '" class="css-checkbox"><label for="age_' + data["ages_distinct"][key]["ageGroup"].toLowerCase().replace(/ /g, "_").replace("+", "plus") + '_option' +'" class="css-label">' + data["ages_distinct"][key]["ageGroup"] + '</label><br />'
                        );
                    }

                    $('#age_' + data["ages_distinct"][key]["ageGroup"].toLowerCase() + '_option').click(function(){
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
                var chart_ages = new google.visualization.BarChart(document.getElementById('agesChart'));
                chart_ages.draw(data_ages, chart_options_ages);   
                update_progress();

                // Gender Graph
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
    
                $("#gender-graph .spinner-block").hide();    
                $("#gender_filter").empty();
    
                var data_genders = new google.visualization.DataTable();
                data_genders.addColumn('string', 'Gender');
                data_genders.addColumn('number', 'Records');
                data_genders.addColumn({type: 'string', role: 'annotation'});
    
                var result_genders = Object.keys(data["genders"]).map(function(key) {
                    return [keyChangerGender(data["genders"][key]["gender"]), data["genders"][key]["audience"], kFormatter(data["genders"][key]["audience"])];
                });
            
                    data_genders.addRows(result_genders);
                    // Set chart options
                    var chart_options_genders = {
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
    
                    for (var key in data["genders_distinct"]) {
                        if(target_genders.includes(data["genders_distinct"][key]["gender"])) {
                            $("#gender_filter").append(
                                '<input type="checkbox" name="g_' + data["genders_distinct"][key]["gender"] + '" id="g_' + data["genders_distinct"][key]["gender"].toLowerCase() + '_option' +'" value="' + data["genders_distinct"][key]["gender"] + '" class="css-checkbox" checked="checked"><label for="g_' + data["genders_distinct"][key]["gender"].toLowerCase() + '_option' +'" class="css-label">' + get_gender_name(data["genders_distinct"][key]["gender"]) + '</label><br />'
                            );
                        } else {
                            $("#gender_filter").append(
                                '<input type="checkbox" name="g_' + data["genders_distinct"][key]["gender"] + '" id="g_' + data["genders_distinct"][key]["gender"].toLowerCase() + '_option' +'" value="' + data["genders_distinct"][key]["gender"] + '" class="css-checkbox"><label for="g_' + data["genders_distinct"][key]["gender"].toLowerCase() + '_option' +'" class="css-label">' + get_gender_name(data["genders_distinct"][key]["gender"]) + '</label><br />'
                            );
                        }

                        $('#g_' + data["genders_distinct"][key]["gender"].toLowerCase() + '_option').click(function(){
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
                    var chart_genders = new google.visualization.ColumnChart(document.getElementById('genderChart'));
                    chart_genders.draw(data_genders, chart_options_genders);  
                    update_progress();   

                    // Population Group Chart
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
        
                    $("#population-graph .spinner-block").hide();    
                    $("#population_group_filter").empty();
        
                    var data_population_groups = new google.visualization.DataTable();
                        data_population_groups.addColumn('string', 'Group');
                        data_population_groups.addColumn('number', 'Records');
                        data_population_groups.addColumn({type: 'string', role: 'annotation'});
        
                    var result_population_groups = Object.keys(data["population_groups"]).map(function(key) {
                        return [get_ethnic_name(data["population_groups"][key]["populationGroup"]), data["population_groups"][key]["audience"], kFormatter(data["population_groups"][key]["audience"])];
                    });
                
                    data_population_groups.addRows(result_population_groups);
                    // Set chart options
                    var chart_options_population_groups = {
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
        
                        for (var key in data["population_groups_distinct"]) {
                            if(target_population_groups.includes(data["population_groups_distinct"][key]["populationGroup"])) {
                                $("#population_group_filter").append(
                                    '<input type="checkbox" name="pop_' + data["population_groups_distinct"][key]["populationGroup"] + '" id="pop_' + data["population_groups_distinct"][key]["populationGroup"].toLowerCase() + '_option' +'" value="' + data["population_groups_distinct"][key]["populationGroup"] + '" class="css-checkbox" checked="checked"><label for="pop_' + data["population_groups_distinct"][key]["populationGroup"].toLowerCase() + '_option' +'" class="css-label">' + get_ethnic_name(data["population_groups_distinct"][key]["populationGroup"]) + '</label><br />'
                                );
                            } else {
                                $("#population_group_filter").append(
                                    '<input type="checkbox" name="pop_' + data["population_groups_distinct"][key]["populationGroup"] + '" id="pop_' + data["population_groups_distinct"][key]["populationGroup"].toLowerCase() + '_option' +'" value="' + data["population_groups_distinct"][key]["populationGroup"] + '" class="css-checkbox"><label for="pop_' + data["population_groups_distinct"][key]["populationGroup"].toLowerCase() + '_option' +'" class="css-label">' + get_ethnic_name(data["population_groups_distinct"][key]["populationGroup"]) + '</label><br />'
                                );
                            }

                            $('#pop_' + data["population_groups_distinct"][key]["populationGroup"].toLowerCase() + '_option').click(function(){
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
                        var chart_population_groups = new google.visualization.ColumnChart(document.getElementById('populationGroupChart'));
                        chart_population_groups.draw(data_population_groups, chart_options_population_groups);     
                        update_progress();

                        // Generation Data
                        $("#generation-graph .spinner-block").hide();    
                        $("#generation_filter").empty();
            
                        var data_generations = new google.visualization.DataTable();
                        data_generations.addColumn('string', 'Generation');
                        data_generations.addColumn('number', 'Records');
                        data_generations.addColumn({type: 'string', role: 'annotation'});
            
                        var result_generations = Object.keys(data["generations"]).map(function(key) {
                            return [data["generations"][key]["generation"], data["generations"][key]["audience"], kFormatter(data["generations"][key]["audience"])];
                        });
                    
                        data_generations.addRows(result_generations);
                        // Set chart options
                        var chart_options_generations = {
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
                            for (var key in data["generations_distinct"]) {
                                if(target_generations.includes(data["generations_distinct"][key]["generation"])) {
                                    $("#generation_filter").append(
                                        '<input type="checkbox" name="gen_' + data["generations_distinct"][key]["generation"] + '" id="gen_' + data["generations_distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option' +'" value="' + data["generations_distinct"][key]["generation"] + '" class="css-checkbox" checked="checked"><label for="gen_' + data["generations_distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option' +'" class="css-label">' + data["generations_distinct"][key]["generation"] + '</label><br />'
                                    );
                                } else {
                                    $("#generation_filter").append(
                                        '<input type="checkbox" name="gen_' + data["generations_distinct"][key]["generation"] + '" id="gen_' + data["generations_distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option' +'" value="' + data["generations_distinct"][key]["generation"] + '" class="css-checkbox"><label for="gen_' + data["generations_distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option' +'" class="css-label">' + data["generations_distinct"][key]["generation"] + '</label><br />'
                                    );
                                }
                                $('#gen_' + data["generations_distinct"][key]["generation"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
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
                            var chart_generations = new google.visualization.ColumnChart(document.getElementById('generationChart'));
                            chart_generations.draw(data_generations, chart_options_generations);
                            update_progress();

                            // Citizen VS Resident
                            $("#c-vs-r-graph .spinner-block").hide();    
                            $("#citizen_vs_resident_filter").empty();

                            var data_citizen_vs_resident = new google.visualization.DataTable();
                                data_citizen_vs_resident.addColumn('string', 'Citizen or Resident');
                                data_citizen_vs_resident.addColumn('number', 'Records');
                                data_citizen_vs_resident.addColumn({type: 'string', role: 'annotation'});

                                if(data["citizens_vs_residents"][0]["citizens"] && data["citizens_vs_residents"][0]["residents"]) {
                                    data_citizen_vs_resident.addRows([
                                        ["citizens", data["citizens_vs_residents"][0]["citizens"], kFormatter(data["citizens_vs_residents"][0]["citizens"])],
                                        ["residents", data["citizens_vs_residents"][0]["residents"], kFormatter(data["citizens_vs_residents"][0]["residents"])]
                                    ]);
                                } else if(data["citizens_vs_residents"][0]["residents"]) {
                                    data_citizen_vs_resident.addRows([
                                        ["residents", data["citizens_vs_residents"][0]["residents"], kFormatter(data["citizens_vs_residents"][0]["residents"])]
                                    ]);
                                
                                } else {
                                    data_citizen_vs_resident.addRows([
                                        ["citizens", data["citizens_vs_residents"][0]["citizens"], kFormatter(data["citizens_vs_residents"][0]["citizens"])]
                                    ]);
                                }
                                
                                // Set chart options
                                var chart_options_citizen_vs_resident = {
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

                                        $("#citizen_vs_resident_filters").append('<li id="filter_citizen_option">Citizen<i class="fas fa-window-close float-right"></i></li>');
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
                                var chart_citizen_vs_resident = new google.visualization.ColumnChart(document.getElementById('citizensVsResidentsChart'));
                                chart_citizen_vs_resident.draw(data_citizen_vs_resident, chart_options_citizen_vs_resident);    
                                update_progress();

                                // Marital Status
                                $("#marital-status-graph .spinner-block").hide();    
                                $("#marital_status_filter").empty();
                        
                                var data_marital_statuses = new google.visualization.DataTable();
                                data_marital_statuses.addColumn('string', 'Marital Status');
                                data_marital_statuses.addColumn('number', 'Records');
                                data_marital_statuses.addColumn({type: 'string', role: 'annotation'});
                        
                                var result_marital_statuses = Object.keys(data["marital_statuses"]).map(function(key) {
                                        return [keyChangerMaritalStatus(data["marital_statuses"][key]["maritalStatus"]), data["marital_statuses"][key]["audience"], kFormatter(data["marital_statuses"][key]["audience"])];
                                });
                            
                                data_marital_statuses.addRows(result_marital_statuses);
                                // Set chart options
                                var chart_options_marital_statuses = {
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
                                
                                for (var key in data["marital_statuses_distinct"]) {
                                    if(target_marital_statuses.includes(data["marital_statuses_distinct"][key]["maritalStatus"])) {
                                        $("#marital_status_filter").append(
                                            '<input type="checkbox" name="m_' + data["marital_statuses_distinct"][key]["maritalStatus"].toLowerCase() + '" id="m_' + data["marital_statuses_distinct"][key]["maritalStatus"].toLowerCase() + '_option' +'" value="' + data["marital_statuses_distinct"][key]["maritalStatus"] + '" class="css-checkbox" checked="checked"><label for="m_' + data["marital_statuses_distinct"][key]["maritalStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChangerMaritalStatus(data["marital_statuses_distinct"][key]["maritalStatus"]) + '</label><br />'
                                        );
                    
                                    } else {
                                        $("#marital_status_filter").append(
                                            '<input type="checkbox" name="m_' + data["marital_statuses_distinct"][key]["maritalStatus"].toLowerCase() + '" id="m_' + data["marital_statuses_distinct"][key]["maritalStatus"].toLowerCase() + '_option' +'" value="' + data["marital_statuses_distinct"][key]["maritalStatus"] + '" class="css-checkbox"><label for="m_' + data["marital_statuses_distinct"][key]["maritalStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChangerMaritalStatus(data["marital_statuses_distinct"][key]["maritalStatus"]) + '</label><br />'
                                        );
                                    }
                                    $('#m_' + data["marital_statuses_distinct"][key]["maritalStatus"].toLowerCase() + '_option').click(function(){
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
                                var chart_marital_statuses = new google.visualization.ColumnChart(document.getElementById('maritalStatusChart'));
                                chart_marital_statuses.draw(data_marital_statuses, chart_options_marital_statuses);    
                                update_progress();

        }

    }).done(function(data) {
        DrawAssetsGraphs();
    }).fail(function(error) {
        $("#age-graph .spinner-block").hide();
        $("#age-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#age_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        $("#gender-graph .spinner-block").hide();
        $("#gender-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#gender_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        $("#population-graph .spinner-block").hide();
        $("#population-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#population_group_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
        $("#generation-graph .spinner-block").hide();
        $("#generation-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#generation_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        $("#c-vs-r-graph .spinner-block").hide();
        $("#c-vs-r-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#citizen_vs_resident_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
        $("#marital-status-graph .spinner-block").hide();
        $("#marital-status-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#marital_status_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
        console.log(error);
    });
    

}

function DrawAssetsGraphs() {
    data = {    user_id: user_id_number, province: target_provinces.join(","),
                age_group: target_ages.join(","), gender: target_genders.join(","), 
                population_group: target_population_groups.join(","), generation: target_generations.join(","),
                marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
                risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
                directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
                municipality: target_municipalities.join(","), area: target_areas.join(","),
                vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
                lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
                primary_property_type: target_primary_property_types.join(","), custom_variable_1: target_branches.join(","), api_token: user_auth_token
            }
    // Home Owner
    $.ajax({
        url: "/api/meetpat-client/get-assets-data",
        type: "GET",
        data: data,
        success: function(data) {
            // Home Owner
            $("#home-owner-graph .spinner-block").hide();    
            $("#home_owner_filter").empty();

            var data_home_owners = new google.visualization.DataTable();
            data_home_owners.addColumn('string', 'Home Owner Status');
            data_home_owners.addColumn('number', 'Records');
            data_home_owners.addColumn({type: 'string', role: 'annotation'});

            var result_home_owners = Object.keys(data["home_owners"]).map(function(key) {
                return [keyChanger(data["home_owners"][key]["homeOwnershipStatus"]), data["home_owners"][key]["audience"], kFormatter(data["home_owners"][key]["audience"])];
            });
        
                data_home_owners.addRows(result_home_owners);
                // Set chart options
                var chart_options_home_owners = {
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
                for (var key in data["home_owners_distinct"]) {
                    if(target_home_owners.includes(data["home_owners_distinct"][key]["homeOwnershipStatus"].toLowerCase())) {
                        $("#home_owner_filter").append(
                            '<input type="checkbox" name="h_' + data["home_owners_distinct"][key]["homeOwnershipStatus"].toLowerCase() + '" id="h_' + data["home_owners_distinct"][key]["homeOwnershipStatus"].toLowerCase() + '_option' +'" value="' + data["home_owners_distinct"][key]["homeOwnershipStatus"] + '" class="css-checkbox" checked="checked"><label for="h_' + data["home_owners_distinct"][key]["homeOwnershipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(data["home_owners_distinct"][key]["homeOwnershipStatus"]) + '</label><br />'
                        );
                    } else {
                        $("#home_owner_filter").append(
                            '<input type="checkbox" name="h_' + data["home_owners_distinct"][key]["homeOwnershipStatus"].toLowerCase() + '" id="h_' + data["home_owners_distinct"][key]["homeOwnershipStatus"].toLowerCase() + '_option' +'" value="' + data["home_owners_distinct"][key]["homeOwnershipStatus"] + '" class="css-checkbox"><label for="h_' + data["home_owners_distinct"][key]["homeOwnershipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(data["home_owners_distinct"][key]["homeOwnershipStatus"]) + '</label><br />'
                        );
                    }
                    
                    $('#h_' + data["home_owners_distinct"][key]["homeOwnershipStatus"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
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
                var chart_home_owners = new google.visualization.ColumnChart(document.getElementById('homeOwnerChart'));
                chart_home_owners.draw(data_home_owners, chart_options_home_owners);    
                update_progress();

                // Property Count
                $("#property-count-bucket-graph .spinner-block").hide();    
                $("#property_count_bucket_filter").empty();

                var data_property_count = new google.visualization.DataTable();
                    data_property_count.addColumn('string', 'Property Count');
                    data_property_count.addColumn('number', 'Records');
                    data_property_count.addColumn({type: 'string', role: 'annotation'});

                var result_property_count = Object.keys(data["property_counts"]).map(function(key) {
                    return [data["property_counts"][key]["propertyCountBucket"], data["property_counts"][key]["audience"], kFormatter(data["property_counts"][key]["audience"])];
                });
            
                    data_property_count.addRows(result_property_count);
                    // Set chart options
                    var chart_options_property_counts = {
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
                    for (var key in data["property_counts_distinct"]) {
                        if(target_home_owners.includes(data["property_counts_distinct"][key]["propertyCountBucket"])) {
                            $("#property_count_bucket_filter").append(
                                '<input type="checkbox" name="pc_' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '" id="pc_' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '_option' +'" value="' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '" class="css-checkbox" checked="checked"><label for="pc_' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '_option' +'" class="css-label">' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '</label><br />'
                            );
                        } else {
                            $("#property_count_bucket_filter").append(
                                '<input type="checkbox" name="pc_' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '" id="pc_' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '_option' +'" value="' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '" class="css-checkbox"><label for="pc_' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '_option' +'" class="css-label">' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase() + '</label><br />'
                            );
                        }
                        
                        $('#pc_' + data["property_counts_distinct"][key]["propertyCountBucket"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
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
                    var chart_property_counts = new google.visualization.ColumnChart(document.getElementById('propertyCountBucketChart'));
                        chart_property_counts.draw(data_property_count, chart_options_property_counts);  
                    update_progress();

                    // Primary Property Type
                    $("#primary-property-type-graph .spinner-block").hide();    
                    $("#primary_property_type_filter").empty();
        
                    var data_primary_property_types = new google.visualization.DataTable();
                    data_primary_property_types.addColumn('string', 'Primary Property Type');
                    data_primary_property_types.addColumn('number', 'Records');
                    data_primary_property_types.addColumn({type: 'string', role: 'annotation'});
        
                    var result_primary_property_types = Object.keys(data["primary_property_types"]).map(function(key) {
                        return [data["primary_property_types"][key]["primaryPropertyType"], data["primary_property_types"][key]["audience"], kFormatter(data["primary_property_types"][key]["audience"])];
                    });
                
                        data_primary_property_types.addRows(result_primary_property_types);
                        // Set chart options
                        var chart_options_primary_property_types = {
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
                        for (var key in data["primary_property_type_distinct"]) {
                            if(target_home_owners.includes(data["primary_property_type_distinct"][key]["primaryPropertyType"])) {
                                $("#primary_property_type_filter").append(
                                    '<input type="checkbox" name="pt_' + data["primary_property_type_distinct"][key]["primaryPropertyType"].toLowerCase() + '" id="pt_' + data["primary_property_type_distinct"][key]["primaryPropertyType"].toLowerCase() + '_option' +'" value="' + data["primary_property_type_distinct"][key]["primaryPropertyType"].toLowerCase() + '" class="css-checkbox" checked="checked"><label for="pt_' + data["primary_property_type_distinct"][key]["primaryPropertyType"].toLowerCase() + '_option' +'" class="css-label">' + data["primary_property_type_distinct"][key]["primaryPropertyType"] + '</label><br />'
                                );
                            } else {
                                $("#primary_property_type_filter").append(
                                    '<input type="checkbox" name="pt_' + data["primary_property_type_distinct"][key]["primaryPropertyType"].toLowerCase() + '" id="pt_' + data["primary_property_type_distinct"][key]["primaryPropertyType"].toLowerCase() + '_option' +'" value="' + data["primary_property_type_distinct"][key]["primaryPropertyType"].toLowerCase() + '" class="css-checkbox"><label for="pt_' + data["primary_property_type_distinct"][key]["primaryPropertyType"].toLowerCase() + '_option' +'" class="css-label">' + data["primary_property_type_distinct"][key]["primaryPropertyType"] + '</label><br />'
                                );
                            }
                            
                            $('#pt_' + data["primary_property_type_distinct"][key]["primaryPropertyType"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                                if($('#pt_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                                    
                                    var parent = this;
                
                                    $("#primary_property_type_filters").append('<li id="filter_pt_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val().toUpperCase() +'<i class="fas fa-window-close float-right"></i></li>')
                                    $('#filter_pt_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                                        if($('#pt_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                            $('#filter_pt_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                            $("#pt_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                                        }
                                        checkForFilters();
        
                                    });
                                } else {
                                    
                
                                    if($('#filter_pt_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                                        $('#filter_pt_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                                    }
                                }
                                checkForFilters();
        
                            });
                        }              
                        // Instantiate and draw our chart, passing in some options.
                        var chart_primary_property_types = new google.visualization.BarChart(document.getElementById('primaryPropertyTypeChart'));
                            chart_primary_property_types.draw(data_primary_property_types, chart_options_primary_property_types);  

                        update_progress();
                    
                        // Vehicle Owner
                        $("#vehicle-owner-graph .spinner-block").hide();    
                        $("#vehicle_owner_filter").empty();

                        var data_vehicle_owners = new google.visualization.DataTable();
                            data_vehicle_owners.addColumn('string', 'Vehicle Owner Status');
                            data_vehicle_owners.addColumn('number', 'Records');
                            data_vehicle_owners.addColumn({type: 'string', role: 'annotation'});

                        var result_vehicle_owners = Object.keys(data["vehicle_owners"]).map(function(key) {
                            return [keyChanger(data["vehicle_owners"][key]["vehicleOwnershipStatus"]), data["vehicle_owners"][key]["audience"], kFormatter(data["vehicle_owners"][key]["audience"])];
                        });
                    
                            data_vehicle_owners.addRows(result_vehicle_owners);
                            // Set chart options
                            var chart_options_vehicle_owners = {
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
                            for (var key in data["vehicle_owners_distinct"]) {
                                if(target_vehicle_owners.includes(data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"])) {
                                    $("#vehicle_owner_filter").append(
                                        '<input type="checkbox" name="vo_' + data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '" id="vo_' + data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '_option' +'" value="' + data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"] + '" class="css-checkbox" checked="checked"><label for="vo_' + data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"]) + '</label><br />'
                                    );
                                } else {
                                    $("#vehicle_owner_filter").append(
                                        '<input type="checkbox" name="vo_' + data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '" id="vo_' + data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '_option' +'" value="' + data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"] + '" class="css-checkbox"><label for="vo_' + data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"]) + '</label><br />'
                                    );
                                }
                                
                                $('#vo_' + data["vehicle_owners_distinct"][key]["vehicleOwnershipStatus"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                                    
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
                            var chart_vehicle_owners = new google.visualization.ColumnChart(document.getElementById('vehicleOwnerChart'));
                                chart_vehicle_owners.draw(data_vehicle_owners, chart_options_vehicle_owners);    
                            update_progress();
        }

    }).done(function(data) {
        DrawFinancialCharts();
    }).fail(function(error) {
        $("#home-owner-graph .spinner-block").hide();
        $("#home-owner-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#home_owner_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        $("#property-count-bucket-graph .spinner-block").hide();
        $("#property-count-bucket-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#property_count_bucket_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        $("#primary-property-type-graph .spinner-block").hide();
        $("#primary-property-type-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#primary_property_type_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        $("#vehicle-owner-graph .spinner-block").hide();
        $("#vehicle-owner-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#vehicle_owner_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        console.log(error);
    });

}

function DrawFinancialCharts() {
    data = {    user_id: user_id_number, province: target_provinces.join(","),
                age_group: target_ages.join(","), gender: target_genders.join(","), 
                population_group: target_population_groups.join(","), generation: target_generations.join(","),
                marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
                risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
                directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
                municipality: target_municipalities.join(","), area: target_areas.join(","),
                vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
                lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
                primary_property_type: target_primary_property_types.join(","), custom_variable_1: target_branches.join(","), api_token: user_auth_token
            }
    // Risk Category
    $.ajax({
        url: "/api/meetpat-client/get-financial-data",
        type: "GET",
        data: data,
        success: function(data) {
            $("#risk-category-graph .spinner-block").hide();    
            $("#risk_category_filter").empty();
    
            var data_risk_categories = new google.visualization.DataTable();
                data_risk_categories.addColumn('string', 'Age');
                data_risk_categories.addColumn('number', 'Records');
                data_risk_categories.addColumn({type: 'string', role: 'annotation'});
    
            var result_risk_categories = Object.keys(data["risk_categories"]).map(function(key) {
                return [capitalizeFLetter(data["risk_categories"][key]["riskCategory"].toLowerCase().replace('_', ' ')),
                 data["risk_categories"][key]["audience"], kFormatter(data["risk_categories"][key]["audience"])];
              });
        
                data_risk_categories.addRows(result_risk_categories);
                // Set chart options
                var chart_options_risk_categories = {
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
                for (var key in data["risk_category_distinct"]) {
                    if(target_risk_categories.includes(data["risk_category_distinct"][key]["riskCategory"])) {
                        $("#risk_category_filter").append(
                            '<input type="checkbox" name="r_' + data["risk_category_distinct"][key]["riskCategory"] + '" id="r_' + data["risk_category_distinct"][key]["riskCategory"].toLowerCase() + '_option' +'" value="' + data["risk_category_distinct"][key]["riskCategory"] + '" class="css-checkbox" checked="checked"><label for="r_' + data["risk_category_distinct"][key]["riskCategory"].toLowerCase() + '_option' +'" class="css-label">' + capitalizeFLetter(data["risk_category_distinct"][key]["riskCategory"].toLowerCase().replace('_', ' ')) + '</label><br />'
                        );
                    } else {
                        $("#risk_category_filter").append(
                            '<input type="checkbox" name="r_' + data["risk_category_distinct"][key]["riskCategory"] + '" id="r_' + data["risk_category_distinct"][key]["riskCategory"].toLowerCase() + '_option' +'" value="' + data["risk_category_distinct"][key]["riskCategory"] + '" class="css-checkbox"><label for="r_' + data["risk_category_distinct"][key]["riskCategory"].toLowerCase() + '_option' +'" class="css-label">' + capitalizeFLetter(data["risk_category_distinct"][key]["riskCategory"].toLowerCase().replace('_', ' ')) + '</label><br />'
                        );
                    }
    
                    $('#r_' + data["risk_category_distinct"][key]["riskCategory"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
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
                var chart_risk_categories = new google.visualization.BarChart(document.getElementById('riskCategoryChart'));
                    chart_risk_categories.draw(data_risk_categories, chart_options_risk_categories);  
                update_progress();

                // LSM Group
                $("#lsm-group-graph .spinner-block").hide();    
                $("#lsm_group_filter").empty();

                var data_lsm_groups = new google.visualization.DataTable();
                    data_lsm_groups.addColumn('string', 'LSM Group');
                    data_lsm_groups.addColumn('number', 'Records');
                    data_lsm_groups.addColumn({type: 'string', role: 'annotation'});

                var result_lsm_groups = Object.keys(data["lsm_groups"]).map(function(key) {
                    return [data["lsm_groups"][key]["lsmGroup"], data["lsm_groups"][key]["audience"], kFormatter(data["lsm_groups"][key]["audience"])];
                });
            
                    data_lsm_groups.addRows(result_lsm_groups);
                    // Set chart options
                    var chart_options_lsm_groups = {
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
                    for (var key in data["lsm_groups_distinct"]) {
                        if(target_lsm_groups.includes(data["lsm_groups_distinct"][key]["lsmGroup"])) {
                            $("#lsm_group_filter").append(
                                '<input type="checkbox" name="lsm_' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '" id="lsm_' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '_option' +'" value="' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '" class="css-checkbox" checked="checked"><label for="lsm_' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '_option' +'" class="css-label">' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '</label><br />'
                            );
                        } else {
                            $("#lsm_group_filter").append(
                                '<input type="checkbox" name="lsm_' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '" id="lsm_' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '_option' +'" value="' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '" class="css-checkbox"><label for="lsm_' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '_option' +'" class="css-label">' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase() + '</label><br />'
                            );
                        }
                        
                        $('#lsm_' + data["lsm_groups_distinct"][key]["lsmGroup"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                            
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
                    var chart_lsm_groups = new google.visualization.BarChart(document.getElementById('lsmGroupChart'));
                        chart_lsm_groups.draw(data_lsm_groups, chart_options_lsm_groups);    
                    update_progress();

                    // Household Income

                    $("#income-graph .spinner-block").hide();    
                    $("#household_income_filter").empty();

                    var data_household_incomes = new google.visualization.DataTable();
                        data_household_incomes.addColumn('string', 'Income');
                        data_household_incomes.addColumn('number', 'Records');
                        data_household_incomes.addColumn({type: 'string', role: 'annotation'});

                    var result_household_incomes = Object.keys(data["household_incomes"]).map(function(key) {
                        return [keyChangerHsIncBucket(data["household_incomes"][key]["incomeBucket"]), data["household_incomes"][key]["audience"], kFormatter(data["household_incomes"][key]["audience"])];
                    });
                
                        data_household_incomes.addRows(result_household_incomes);
                        // Set chart options
                        var chart_options_household_incomes = {
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
                        for (var key in data["household_incomes_distinct"]) {
                            if(target_incomes.includes(data["household_incomes_distinct"][key]["incomeBucket"])) {
                                $("#household_income_filter").append(
                                    '<input type="checkbox" name="hi_' + data["household_incomes_distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '" id="hi_' + data["household_incomes_distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" value="' + data["household_incomes_distinct"][key]["incomeBucket"] + '" class="css-checkbox" checked="checked"><label for="hi_' + data["household_incomes_distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" class="css-label">' + keyChangerHsIncBucket(data["household_incomes_distinct"][key]["incomeBucket"]) + '</label><br />'
                                );
                            } else {
                                $("#household_income_filter").append(
                                    '<input type="checkbox" name="hi_' + data["household_incomes_distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '" id="hi_' + data["household_incomes_distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" value="' + data["household_incomes_distinct"][key]["incomeBucket"] + '" class="css-checkbox"><label for="hi_' + data["household_incomes_distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option' +'" class="css-label">' + keyChangerHsIncBucket(data["household_incomes_distinct"][key]["incomeBucket"]) + '</label><br />'
                                );
                            }

                            $('#hi_' + data["household_incomes_distinct"][key]["incomeBucket"].toLowerCase().replace(/ /g, "_").replace('-', '').replace('+', 'plus') + '_option').click(function(){
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
                        var chart_household_incomes = new google.visualization.BarChart(document.getElementById('householdIncomeChart'));
                            chart_household_incomes.draw(data_household_incomes, chart_options_household_incomes);     
                        update_progress();

                        // Company Directors

                        $("#directors-graph .spinner-block").hide();    
            $("#directors_filter").empty();

            var data_directors = new google.visualization.DataTable();
                data_directors.addColumn('string', 'Director of Business');
                data_directors.addColumn('number', 'Records');
                data_directors.addColumn({type: 'string', role: 'annotation'});

            var result_directors = Object.keys(data["company_directors"]).map(function(key) {
                return [keyChanger(data["company_directors"][key]["directorshipStatus"]), data["company_directors"][key]["audience"], kFormatter(data["company_directors"][key]["audience"])];
            });
        
                data_directors.addRows(result_directors);
                // Set chart options
                var chart_options_directors = {
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
                for (var key in data["company_directors_distinct"]) {
                    if(target_directors.includes(data["company_directors_distinct"][key]["directorshipStatus"])) {
                        $("#directors_filter").append(
                            '<input type="checkbox" name="d_' + data["company_directors_distinct"][key]["directorshipStatus"] + '" id="d_' + data["company_directors_distinct"][key]["directorshipStatus"].toLowerCase() + '_option' +'" value="' + data["company_directors_distinct"][key]["directorshipStatus"] + '" class="css-checkbox" checked="checked"><label for="d_' + data["company_directors_distinct"][key]["directorshipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(data["company_directors_distinct"][key]["directorshipStatus"]) + '</label><br />'
                        );
                    } else {
                        $("#directors_filter").append(
                            '<input type="checkbox" name="d_' + data["company_directors_distinct"][key]["directorshipStatus"] + '" id="d_' + data["company_directors_distinct"][key]["directorshipStatus"].toLowerCase() + '_option' +'" value="' + data["company_directors_distinct"][key]["directorshipStatus"] + '" class="css-checkbox"><label for="d_' + data["company_directors_distinct"][key]["directorshipStatus"].toLowerCase() + '_option' +'" class="css-label">' + keyChanger(data["company_directors_distinct"][key]["directorshipStatus"]) + '</label><br />'
                        );
                    }

                    $('#d_' + data["company_directors_distinct"][key]["directorshipStatus"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
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
                var chart_directors = new google.visualization.ColumnChart(document.getElementById('directorOfBusinessChart'));
                    chart_directors.draw(data_directors, chart_options_directors);    
                update_progress();
                $(".apply-filter-button").prop("disabled", false);
                $('.apply-filter-button').html("apply");
                $('#sidebarSubmitBtn').html('<i class="fas fa-sync-alt"></i>&nbsp;Apply Filters');
                $('#sidebarSubmitBtn').prop("disabled", false);
                $("#resetFilterToastBtn").prop("disabled", false);
                $("#audienceSubmitBtn").prop("disabled", false);
                $("#resetFilterToastBtn").html('<i class="fas fa-undo-alt"></i>&nbsp;Reset Filters');
                $("#downloadSubmitBtn").prop("disabled", false);
        }

    }).done(function(data) {
        DrawCustomMetricsCharts();
    }).fail(function(error) {
        $("#risk-category-graph .spinner-block").hide();
        $("#risk-category-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#risk_category_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
        
        $("#lsm-group-graph .spinner-block").hide();
        $("#lsm-group-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#lsm_group_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        $("#income-graph .spinner-block").hide();
        $("#income-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#household_income_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        $("#directors-graph .spinner-block").hide();
        $("#directors-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#directors_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');

        console.log(error);
    });
}

function DrawCustomMetricsCharts() {
    data = {    user_id: user_id_number, province: target_provinces.join(","),
                age_group: target_ages.join(","), gender: target_genders.join(","), 
                population_group: target_population_groups.join(","), generation: target_generations.join(","),
                marital_status: target_marital_statuses.join(","), home_ownership_status: target_home_owners.join(","),
                risk_category: target_risk_categories.join(","), income_bucket: target_incomes.join(","),
                directorship_status: target_directors.join(","), citizen_vs_resident: target_citizen_vs_residents.join(","),
                municipality: target_municipalities.join(","), area: target_areas.join(","),
                vehicle_ownership_status: target_vehicle_owners.join(","), property_valuation_bucket: target_property_valuations.join(","),
                lsm_group: target_lsm_groups.join(","), property_count_bucket: target_property_count_buckets.join(","),
                primary_property_type: target_primary_property_types.join(","), custom_variable_1: target_branches.join(","), api_token: user_auth_token
            }
    // Branches
    $.ajax({
        url: '/api/meetpat-client/get-custom-metrics-data',
        type: 'GET',
        data: data,
        success: function(data) {
            if(!data["branches"].length)
            {
                $("#metrics-heading").hide();
                $("#metrics-graphs").hide();
            }
            $("#branch-graph .spinner-block").hide();    
            $("#branch_filter").empty();
    
            var data_branches = new google.visualization.DataTable();
                data_branches.addColumn('string', 'Branch');
                data_branches.addColumn('number', 'Records');
                data_branches.addColumn({type: 'string', role: 'annotation'});
    
            var result_branches = Object.keys(data["branches"]).map(function(key) {
                return [capitalizeFLetter(data["branches"][key]["branch"].toLowerCase().replace('_', ' ')),
                 data["branches"][key]["audience"], kFormatter(data["branches"][key]["audience"])];
              });
        
                data_branches.addRows(result_branches);
                // Set chart options
                if(result_branches.length > 6) {
                    var chart_options_branches = {
                        'height': result_branches.length * 40,
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
                } else {
                    var chart_options_branches = {
                        //'height': result_municipality.length * 25,
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
                }
                
                for (var key in data["branches_distinct"]) {
                    if(target_branches.includes(data["branches_distinct"][key]["branch"])) {
                        $("#branch_filter").append(
                            '<input type="checkbox" name="branch_' + data["branches_distinct"][key]["branch"].toLowerCase().replace(/ /g, "_").replace('-', '') + '" id="branch_' + data["branches_distinct"][key]["branch"].toLowerCase().replace(/ /g, "_").replace('-', '') + '_option' +'" value="' + data["branches_distinct"][key]["branch"] + '" class="css-checkbox" checked="checked"><label for="branch_' + data["branches_distinct"][key]["branch"].toLowerCase().replace(/ /g, "_").replace('-', '') + '_option' +'" class="css-label">' + capitalizeFLetter(data["branches_distinct"][key]["branch"].toLowerCase().replace('_', ' ')) + '</label><br />'
                        );
                    } else {
                        $("#branch_filter").append(
                            '<input type="checkbox" name="branch_' + data["branches_distinct"][key]["branch"].toLowerCase().replace(/ /g, "_").replace('-', '') + '" id="branch_' + data["branches_distinct"][key]["branch"].toLowerCase().replace(/ /g, "_").replace('-', '') + '_option' +'" value="' + data["branches_distinct"][key]["branch"] + '" class="css-checkbox"><label for="branch_' + data["branches_distinct"][key]["branch"].toLowerCase().replace(/ /g, "_").replace('-', '') + '_option' +'" class="css-label">' + capitalizeFLetter(data["branches_distinct"][key]["branch"].toLowerCase().replace('_', ' ')) + '</label><br />'
                        );
                    }
    
                    $('#branch_' + data["branches_distinct"][key]["branch"].toLowerCase().replace(/ /g, "_") + '_option').click(function(){
                        if($('#branch_' + $(this).val().toLowerCase().replace(/ /g, "_") + '_option').is(":checked")) { 
                            
                            var parent = this;
        
                            $("#branches_filters").append('<li id="filter_branch_' + $(this).val().toLowerCase().replace(/ /g, "_") + '">'+ $(this).val() +'<i class="fas fa-window-close float-right"></i></li>')
                            $('#filter_branch_' + $(this).val().toLowerCase().replace(/ /g, "_") + ' i').click(function() {
                                if($('#branch_' + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').length) {
                                    $('#filter_branch_' + $(parent).val().toLowerCase().replace(/ /g, "_")).remove();
                                    $("#branch_" + $(parent).val().toLowerCase().replace(/ /g, "_") + '_option').prop("checked", false);
                                }
                                checkForFilters();
    
                            });
                        } else {
                            
        
                            if($('#filter_branch_' + $(this).val().toLowerCase().replace(/ /g, "_") )) {
                                $('#filter_branch_' + $(this).val().toLowerCase().replace(/ /g, "_") ).remove();
                            }
                        }
                        checkForFilters();
    
                    });
        
                }        
                // Instantiate and draw our chart, passing in some options.
                var chart_branches = new google.visualization.BarChart(document.getElementById('branchChart'));
                    chart_branches.draw(data_branches, chart_options_branches);  
                update_progress();
        }
    }).done(function() {
        hide_progress();
    }).fail(function(error) {
        console.log(error);
        $("#branches-graph .spinner-block").hide();
        $("#branches-graph .graph-container").append('<div class="p-3"><p><i class="fas fa-exclamation-circle text-danger"></i> There was a problem fetching the data. The connection might have been lost.</p><p>If the problem persists please contact MeetPAT Support.</p></div>');
        $("#branch_filter").html('<i class="fas fa-exclamation-circle text-danger"></i>');
    });
}


/** END Draw Graphs asynchronously */

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
    $("#primary-property-type-graph .spinner-block").show(); $("#primaryPropertyTypeChart").empty(); $("#primary_property_type_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');
    $("#branch-graph .spinner-block").show(); $("#branchChart").empty(); $("#branch_filter").html('<div class="text-center"><div class="spinner-border mb-2" role="status"><span class="sr-only">Loading...</span></div></div>');

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
    close_side_bar();
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
    target_primary_property_types = [];   
    target_branches = [];

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

    $("#primary-property-type-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_primary_property_types.push($(this).val());
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
            if(!target_areas.includes($(this).val())) {
                target_areas.push($(this).val());
            }
        
        }
    });

    $("#branch-filter-form input[type='checkbox']").each(function() {
        if(this.checked) {
            target_branches.push($(this).val());
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
    $("#primaryPropertyTypeContactsId").val(target_primary_property_types);
    $("#branchContactsId").val(target_branches);
    
    apply_filters();
    //get_provinces();
    DrawLocationCharts();
});

$("#resetFilterToastBtn, #reset-toggle-button").click(function() {
    close_side_bar();
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
    target_primary_property_types = [];
    target_branches = [];

    count_G = 0;
    count_WC = 0;
    count_KN = 0;
    count_M = 0;
    count_EC = 0;
    count_FS = 0;
    count_NC = 0;
    count_NW = 0;
    count_L = 0;

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
    $("#primaryPropertyTypeContactsId").val(target_primary_property_types);
    $("#hidden-area-filter-form").empty();
    $("#hidden-branch-filter-form").empty();
    $('input:checkbox').each(function(el) {
        if($(el).is(':checked')) {
            $(el).prop('checked', false);
        }
    });

    apply_filters();
    //get_provinces();
    DrawLocationCharts();
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
            $("#userSavedFiles").append('<div class="col-12"><div class="alert alert-info">You have not saved any audiences yet.</div></div>');
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

    //get_provinces(); // Starts sequence to fetch data and draw charts.
    DrawLocationCharts();
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

    if($(window).width() <= 475) {
        initial_scroll = 160;

        window.addEventListener("scroll",function() { 
            if(window.scrollY < 120 && window.scrollY > 0) {
                
                $('#progress_popup').css({"padding-top": initial_scroll - window.scrollY + "px"});
            }
            else if(window.scrollY > 120) {
                $('#progress_popup').css({"padding-top": '25px'});
                
            }
            else {
                initial_scroll = 160;
                $('#progress_popup').css({"padding-top": '160px'});
            }
        },false);

    } else {
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
    }
    
    

});

