var get_records_count =  function(records_data) {
    var records_count = document.getElementById("number_of_records");
        records_count.innerHTML = records_data["contacts"] + "K<br />Contacts";
}   

var make_chart_provinces = function(records_data) {

    data_records = [];

    Object.keys(records_data["provinces"]).forEach(function(key) {
        if(key) {
            data_records.push({"country": key, "records": records_data["provinces"][key]});

        } else {
            data_records.push({"country": "Unkown", "records": records_data["provinces"][key]});

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

    
}

// var make_chart_municipality = function(records_data) {
//     data_records = [];

//     Object.keys(records_data["municipality"]).forEach(function(key) {
//         if(key) {
//             data_records.push({"municipality": key, "records": records_data["municipality"][key]});

//         } else {
//             data_records.push({"municipality": "Unkown", "records": records_data["municipality"][key]});

//         }
//     })
//     // Themes begin
//     am4core.useTheme(am4themes_animated);
//     // Themes end

//     // Create chart instance
//     var chart = am4core.create("municipalityChart", am4charts.XYChart);

//     // Add data
//     chart.data = data_records;

//     // Create axes

//     var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
//     categoryAxis.dataFields.category = "municipality";
//     categoryAxis.renderer.grid.template.location = 0;
//     categoryAxis.renderer.minGridDistance = 30;

//     var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());

//     // Create series
//     var series = chart.series.push(new am4charts.ColumnSeries());
//     series.dataFields.valueX = "records";
//     series.dataFields.categoryY = "municipality";
//     series.name = "Records";
//     series.columns.template.tooltipText = "{categoryY}: [bold]{valueX}[/]";
//     series.columns.template.fillOpacity = .8;

//     var columnTemplate = series.columns.template;
//     columnTemplate.strokeWidth = 3;
//     columnTemplate.strokeOpacity = 1; 
    
// }

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
        value: records_data["provinces"]["L"]
    },
    {
        id: "ZA-EC",
        value: records_data["provinces"]["EC"]
    },
    {
        id: "ZA-NC",
        value: records_data["provinces"]["NC"]
    },
    {
        id: "ZA-NW",
        value: records_data["provinces"]["NW"]
    },
    {
        id: "ZA-NL",
        value: records_data["provinces"]["KN"]
    },
    {
        id: "ZA-WC",
        value: records_data["provinces"]["WC"]
    },
    {
        id: "ZA-MP",
        value: records_data["provinces"]["M"]
    },
    {
        id: "ZA-FS",
        value: records_data["provinces"]["FS"]
    },
    {
        id: "ZA-GT",
        value: records_data["provinces"]["G"]
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

    
}

var get_age_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data["ages"]).forEach(function(key) {
        data_records.push({"age": key, "records": records_data["ages"][key]});
    })
    // Create chart instance
    var chart = am4core.create("agesChart", am4charts.PieChart);

    // Add data
    chart.data = data_records;

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "records";
    pieSeries.dataFields.category = "age";

    
}

var get_gender_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data["genders"]).forEach(function(key) {
        data_records.push({"gender": key, "records": records_data["genders"][key]});
    })
    // Create chart instance
    var chart = am4core.create("genderChart", am4charts.PieChart);

    // Add data
    chart.data = data_records;

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "records";
    pieSeries.dataFields.category = "gender";
    
    
}

var get_population_group = function(records_data) {
    data_records = [];

    Object.keys(records_data["population_groups"]).forEach(function(key) {
        if(key) {
            data_records.push({"group": key, "records": records_data["population_groups"][key]});

        } else {
            data_records.push({"group": "Unkown", "records": records_data["population_groups"][key]});

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
            "records": records_data["citizens_vs_residents"][0]
        },
        {
            "label": "residents",
            "records": records_data["citizens_vs_residents"][1]
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

    
}

var get_marital_status_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data["marital_statuses"]).forEach(function(key) {
        data_records.push({"status": key, "records": records_data["marital_statuses"][key]});
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

    
}

var get_generation_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data["generation"]).forEach(function(key) {
        data_records.push({"generation": key, "records": records_data["generation"][key]});
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

    
}

var get_home_owner_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data["home_owner"]).forEach(function(key) {
        data_records.push({"status": key, "records": records_data["home_owner"][key]});
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
    
    
}

var get_risk_category_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data["risk_categories"]).forEach(function(key) {
        data_records.push({"category": key, "records": records_data["risk_categories"][key]});
    })
    // Create chart instance
    var chart = am4core.create("riskCategoryChart", am4charts.PieChart);

    // Add data
    chart.data = data_records;

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "records";
    pieSeries.dataFields.category = "category";  
    
    
}

var get_household_income_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data["household_income"]).forEach(function(key) {
        data_records.push({"bucket": key, "records": records_data["household_income"][key]});
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
    
    
}

var get_director_of_business_chart = function(records_data) {
    data_records = [];

    Object.keys(records_data["director_of_business"]).forEach(function(key) {
        data_records.push({"director": key, "records": records_data["director_of_business"][key]});
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

    
}

$(document).ready(function() {
    var site_url = window.location.protocol + "//" + window.location.host;

    var user_id_number = $("#user_id").val();
    // var file_unique_id = $("#file_id").val();

    function get_data() {
        $('#loader').show();
        $.post("/api/meetpat-client/get-records", {user_id: user_id_number}, function( data ) {
        }).fail(function(data) {
            $('#loader').hide();
            console.log(data)
        }).done(function(data) {
            console.log(data);
            $('#loader').hide();
                get_records_count(data);
                make_chart_provinces(data);
                get_chart_map(data);
                get_age_chart(data);
                get_gender_chart(data);
                get_population_group(data);
                get_citizen_vs_resident_chart(data);
                get_marital_status_chart(data);
                get_generation_chart(data);
                get_home_owner_chart(data);
                get_risk_category_chart(data);
                get_household_income_chart(data);
                get_director_of_business_chart(data);

                $(".spinner-block").hide();

        });
    }
    get_data();
});