// Make charts

var make_chart_provinces = function(records_data) {

    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        if(key) {
            data_records.push({"country": key, "records": records_data[key]});

        } else {
            data_records.push({"country": "Unkown", "records": records_data[key]});

        }
    })
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("provincesChart", am4charts.XYChart);

    // Add data
    chart.data = data_records;

    // Create axes

    var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "country";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueX = "records";
    series.dataFields.categoryY = "country";
    series.name = "Records";
    series.columns.template.tooltipText = "{categoryY}: [bold]{valueX}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 3;
    columnTemplate.strokeOpacity = 1; 

    $("#province-graph .spinner-block").hide();
}

var make_chart_municipality = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        if(key) {
            data_records.push({"municipality": key, "records": records_data[key]});

        } else {
            data_records.push({"municipality": "Unkown", "records": records_data[key]});

        }
    })
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("municipalityChart", am4charts.XYChart);

    // Add data
    chart.data = data_records;

    // Create axes

    var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "municipality";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueX = "records";
    series.dataFields.categoryY = "municipality";
    series.name = "Records";
    series.columns.template.tooltipText = "{categoryY}: [bold]{valueX}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 3;
    columnTemplate.strokeOpacity = 1; 
    
    $("#municipality-graph .spinner-block").hide();

}

var get_chart_map = function(records_data) {
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create map instance
    var chart = am4core.create("chartdiv", am4maps.MapChart);

    // Set map definition
    chart.geodata = am4geodata_southAfricaLow;

    // Set projection
    chart.projection = new am4maps.projections.Miller();

    // Create map polygon series
    var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());

    chart.events.on("ready", function(ev) {
    chart.zoomToMapObject(polygonSeries.getPolygonById("ZA"));
    });
    //Set min/max fill color for each area
    polygonSeries.heatRules.push({
    property: "fill",
    target: polygonSeries.mapPolygons.template,
    min: chart.colors.getIndex(1).brighten(1),
    max: chart.colors.getIndex(1).brighten(-0.3)
    });
    chart.homeZoomLevel = 1.2;
    chart.homeGeoPoint = {
    latitude:  -28.633997464,
    longitude:  24.875330232
    };

    // Make map load polygon data (state shapes and names) from GeoJSON
    polygonSeries.useGeodata = true;

    // Set heatmap values for each state
    polygonSeries.data = [
    {
        id: "ZA-LP",
        value: records_data["L"]
    },
    {
        id: "ZA-EC",
        value: records_data["EC"]
    },
    {
        id: "ZA-NC",
        value: records_data["NC"]
    },
    {
        id: "ZA-NW",
        value: records_data["NW"]
    },
    {
        id: "ZA-NL",
        value: records_data["KN"]
    },
    {
        id: "ZA-WC",
        value: records_data["WC"]
    },
    {
        id: "ZA-MP",
        value: records_data["M"]
    },
    {
        id: "ZA-FS",
        value: records_data["FS"]
    },
    {
        id: "ZA-GT",
        value: records_data["G"]
    }

    ];

    // Configure series tooltip
    var polygonTemplate = polygonSeries.mapPolygons.template;
    polygonTemplate.tooltipText = "{name}: {value}";
    polygonTemplate.nonScalingStroke = true;
    polygonTemplate.strokeWidth = 0.5;

    // Create hover state and set alternative fill color
    var hs = polygonTemplate.states.create("hover");
    hs.properties.fill = am4core.color("#3490DC");

    $("#map-graph .spinner-block").hide();

}

var get_age_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        data_records.push({"age": key, "records": records_data[key]});
    })
    // Create chart instance
    var chart = am4core.create("agesChart", am4charts.PieChart);

    // Add data
    chart.data = data_records;

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "records";
    pieSeries.dataFields.category = "age";

    $("#age-graph .spinner-block").hide();

}

var get_gender_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        data_records.push({"gender": key, "records": records_data[key]});
    })
    // Create chart instance
    var chart = am4core.create("genderChart", am4charts.PieChart);

    // Add data
    chart.data = data_records;

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "records";
    pieSeries.dataFields.category = "gender";
    
    $("#gender-graph .spinner-block").hide();

}

var get_population_group_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        if(key) {
            data_records.push({"group": key, "records": records_data[key]});

        } else {
            data_records.push({"group": "Unkown", "records": records_data[key]});

        }
    })
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("populationGroupChart", am4charts.XYChart);

    // Add data
    chart.data = data_records;

    // Create axes

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "group";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "records";
    series.dataFields.categoryX = "group";
    series.name = "Groups";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 3;
    columnTemplate.strokeOpacity = 1;    

    $("#population-graph .spinner-block").hide();

}

var get_citizen_vs_resident_chart = function(records_data) {

    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("citizensVsResidentsChart", am4charts.XYChart);

    // Add data
    chart.data = [
        {
            "label": "citizens",
            "records": records_data[0]
        },
        {
            "label": "residents",
            "records": records_data[1]
        }
    ];

    // Create axes

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "label";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "records";
    series.dataFields.categoryX = "label";
    series.name = "Label";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 3;
    columnTemplate.strokeOpacity = 1;

    $("#c-vs-v-graph .spinner-block").hide();

}

var get_marital_status_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        data_records.push({"status": key, "records": records_data[key]});
    });
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("maritalStatusChart", am4charts.XYChart);

    // Add data
    chart.data = data_records;

    // Create axes

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "status";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "records";
    series.dataFields.categoryX = "status";
    series.name = "Statuses";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 3;
    columnTemplate.strokeOpacity = 1;      

    $("#marital-status-graph .spinner-block").hide();

}

var get_generation_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        data_records.push({"generation": key, "records": records_data[key]});
    });
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("generationChart", am4charts.XYChart);

    // Add data
    chart.data = data_records;

    // Create axes

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "generation";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "records";
    series.dataFields.categoryX = "generation";
    series.name = "Generation";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 3;
    columnTemplate.strokeOpacity = 1;      

    $("#generation-graph .spinner-block").hide();

}

var get_home_owner_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        data_records.push({"status": key, "records": records_data[key]});
    });
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("homeOwnerChart", am4charts.XYChart);

    // Add data
    chart.data = data_records;

    // Create axes

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "status";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "records";
    series.dataFields.categoryX = "status";
    series.name = "Statuses";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 3;
    columnTemplate.strokeOpacity = 1;    
    
    $("#home-owner-graph .spinner-block").hide();

}

var get_risk_category_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        data_records.push({"category": key, "records": records_data[key]});
    })
    // Create chart instance
    var chart = am4core.create("riskCategoryChart", am4charts.PieChart);

    // Add data
    chart.data = data_records;

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "records";
    pieSeries.dataFields.category = "category";  
    
    $("#risk-category-graph .spinner-block").hide();

}

var get_household_income_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        data_records.push({"bucket": key, "records": records_data[key]});
    });
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("householdIncomeChart", am4charts.XYChart);

    // Add data
    chart.data = data_records;

    // Create axes

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "bucket";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "records";
    series.dataFields.categoryX = "bucket";
    series.name = "Buckets";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 3;
    columnTemplate.strokeOpacity = 1;     
    
    $("#income-graph .spinner-block").hide();

}

var get_director_of_business_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data).forEach(function(key) {
        data_records.push({"director": key, "records": records_data[key]});
    });
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart = am4core.create("directorOfBusinessChart", am4charts.XYChart);

    // Add data
    chart.data = data_records;

    // Create axes

    var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "director";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;

    var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

    // Create series
    var series = chart.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueY = "records";
    series.dataFields.categoryX = "director";
    series.name = "Director";
    series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
    series.columns.template.fillOpacity = .8;

    var columnTemplate = series.columns.template;
    columnTemplate.strokeWidth = 3;
    columnTemplate.strokeOpacity = 1;      

    $("#directors-graph .spinner-block").hide();

}
var user_id_number = $("#user_id").val();

var get_records_count =  function(records_data) {
        
    var records_count = document.getElementById("number_of_records");
        
    $.post("/api/meetpat-client/get-records/count", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        records_count.innerHTML = data + "K<br />Contacts";
        $("#contacts-number .spinner-block").hide();

    });
}   

get_records_count();

var get_municipalities = function() {

    $.post("/api/meetpat-client/get-records/municipalities", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        make_chart_municipality(data);
        $("#municipality-graph .spinner-block").hide();
        get_provinces();

    });

}

var get_provinces = function() {

    $.post("/api/meetpat-client/get-records/provinces", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        make_chart_provinces(data);
        get_chart_map(data);
        get_ages();
        $("#municipality-graph .spinner-block").hide();
    });

}

var get_ages = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/ages", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_age_chart(data);
        $("#municipality-graph .spinner-block").hide();
        get_genders();
    });

}

var get_genders = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/genders", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_gender_chart(data);
        $("#municipality-graph .spinner-block").hide();
        get_population_groups();
    });

}

var get_population_groups = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/population-groups", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_population_group_chart(data);
        $("#municipality-graph .spinner-block").hide();
        get_generations();
    });

}

var get_home_owner = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/home-owner", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_home_owner_chart(data);
        $("#municipality-graph .spinner-block").hide();
        get_risk_category();
    });

}

var get_household_income = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/household-income", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_household_income_chart(data);
        $("#municipality-graph .spinner-block").hide();
        get_director_of_business();
    });

}

var get_risk_category = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/risk-category", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_risk_category_chart(data);
        $("#municipality-graph .spinner-block").hide();
        get_household_income();
    });

}

var get_director_of_business = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/director-of-business", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_director_of_business_chart(data);
        $("#municipality-graph .spinner-block").hide();
        
    });

}

var get_citizens_and_residents = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/citizens-and-residents", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_citizen_vs_resident_chart(data);
        $("#municipality-graph .spinner-block").hide();
        get_marital_statuses();
    });

}

var get_generations = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/generations", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_generation_chart(data);
        $("#municipality-graph .spinner-block").hide();
        get_citizens_and_residents();
    });

}

var get_marital_statuses = function() {
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-records/marital-statuses", {user_id: user_id_number}, function( data ) {
    }).fail(function(data) {
        $('#loader').hide();
        console.log(data)
    }).done(function(data) {
        console.log(data);
        get_marital_status_chart(data);
        $("#municipality-graph .spinner-block").hide();
        get_home_owner();
    });

}



$(document).ready(function() {
    //var site_url = window.location.protocol + "//" + window.location.host;

    get_municipalities();
});