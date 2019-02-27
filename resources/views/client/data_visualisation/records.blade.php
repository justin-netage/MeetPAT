@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<div id="loader" style="display:none;"></div>
<div id="alert-section"></div>
<form id="credentials">
    <input type="hidden" id="user_id" name="user_id" value="{{Auth::user()->id}}">
    <input type="hidden" id="file_id" name="file_id" value="{{$file_id}}">
</form>

<div class="container">
    <div class="row p-5">
        <div class="col-12 col-md-2 d-flex align-items-center">
            <div id="number_of_records">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <h3 class="display-3 text-center">Where? <br /><small>Location Targeting</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>
    </div>
    <div class="row p-5">
        <div class="col-12 col-md-6">
            <h3 class="display-4">Province</h3>
            <hr>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="provincesChart" style="height:100%; max-height: 600px; width: 100%;"></div>
        </div>
        <div class="col-12 col-md-6">
            <h3 class="display-4">Municipality</h3>
            <hr>
            <div class="graph-container" class="graph-container" style="overflow-y: scroll; height: 600px; max-height: 600px;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="municipalityChart" style="height: 100%; min-height: 2000px; width: 100%;"></div>
            </div>
        </div>
    </div>
    <div class="row p-5">
        <div class="col-12 col-md-6">
            <h3 class="display-4">Map</h3>
            <hr>
            <div id="chartdiv" style="width: 100%; height: 368px; border: 2px solid #6C757D; border-radius: 5px;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
            </div>      
        </div>
        <div class="col-12 col-md-6">
            <!-- <h3 class="display-4">Areas</h3>
            <hr>
            <div class="graph-container" class="graph-container" style="overflow-y: scroll; height: 500px;">
                <div id="areasChart" style="height: 20000px; min-height: 10000; width: 100%;"></div>
            </div> -->
        </div>
    </div>
</div>



@endsection

@section('scripts')

<!-- <script src="{{asset('bower_components/chart.js/dist/Chart.min.js')}}"></script> -->
<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/maps.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/geodata/southAfricaLow.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<script type="text/javascript">

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

        // categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
        // if (target.dataItem && target.dataItem.index & 2 == 2) {
        //     return dy + 25;
        // }
        // return dy;
        // });

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

    var make_chart_municipality = function(records_data) {
        data_records = [];

        Object.keys(records_data["municipality"]).forEach(function(key) {
            if(key) {
                data_records.push({"municipality": key, "records": records_data["municipality"][key]});

            } else {
                data_records.push({"municipality": "Unkown", "records": records_data["municipality"][key]});

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

        // categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
        // if (target.dataItem && target.dataItem.index & 2 == 2) {
        //     return dy + 25;
        // }
        // return dy;
        // });

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
        chart.homeZoomLevel = 1.5;
        chart.homeGeoPoint = {
        latitude:  -28.454111,
        longitude:  26.796785
        };

        // Make map load polygon data (state shapes and names) from GeoJSON
        polygonSeries.useGeodata = true;

        // Set heatmap values for each state
        polygonSeries.data = [
        {
            id: "ZA-LP",
            value: records_data["provinces"]["Limpopo"]
        },
        {
            id: "ZA-EC",
            value: records_data["provinces"]["Eastern Cape"]
        },
        {
            id: "ZA-NC",
            value: records_data["provinces"]["Northern Cape"]
        },
        {
            id: "ZA-NW",
            value: records_data["provinces"]["North West"]
        },
        {
            id: "ZA-NL",
            value: records_data["provinces"]["KwaZulu-Natal"]
        },
        {
            id: "ZA-WC",
            value: records_data["provinces"]["Western Cape"]
        },
        {
            id: "ZA-MP",
            value: records_data["provinces"]["Mpumalanga"]
        },
        {
            id: "ZA-FS",
            value: records_data["provinces"]["Free State"]
        },
        {
            id: "ZA-GT",
            value: records_data["provinces"]["Gauteng"]
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

    // var make_chart_area = function(records_data) {
    //     data_records = [];

    //     Object.keys(records_data["areas"]).forEach(function(key) {
    //         if(key) {
    //             data_records.push({"area": key, "records": records_data["areas"][key]});

    //         } else {
    //             data_records.push({"area": "Unkown", "records": records_data["areas"][key]});

    //         }
    //     })
    //     // Themes begin
    //     am4core.useTheme(am4themes_animated);
    //     // Themes end

    //     // Create chart instance
    //     var chart = am4core.create("areasChart", am4charts.XYChart);

    //     // Add data
    //     chart.data = data_records;

    //     // Create axes

    //     var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
    //     categoryAxis.dataFields.category = "area";
    //     categoryAxis.renderer.grid.template.location = 0;
    //     categoryAxis.renderer.minGridDistance = 30;

    //     // categoryAxis.renderer.labels.template.adapter.add("dy", function(dy, target) {
    //     // if (target.dataItem && target.dataItem.index & 2 == 2) {
    //     //     return dy + 25;
    //     // }
    //     // return dy;
    //     // });

    //     var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());

    //     // Create series
    //     var series = chart.series.push(new am4charts.ColumnSeries());
    //     series.dataFields.valueX = "records";
    //     series.dataFields.categoryY = "area";
    //     series.name = "Records";
    //     series.columns.template.tooltipText = "{categoryY}: [bold]{valueX}[/]";
    //     series.columns.template.fillOpacity = .8;

    //     var columnTemplate = series.columns.template;
    //     columnTemplate.strokeWidth = 3;
    //     columnTemplate.strokeOpacity = 1; 
        
    // }

    $(document).ready(function() {
        var site_url = window.location.protocol + "//" + window.location.host;

        var user_id_number = $("#user_id").val();
        var file_unique_id = $("#file_id").val();

        function get_data() {
            $('#loader').show();
            $.post("/api/meetpat-client/get-records", {user_id: user_id_number, file_id: file_unique_id}, function( data ) {
            }).fail(function(data) {
                $('#loader').hide();
                console.log(data.responseJSON)
            }).done(function(data) {
                $('#loader').hide();
                console.log(data);
                    get_records_count(data);
                // First graph Provinces
                    make_chart_provinces(data);
                // Second graph municipality
                    make_chart_municipality(data);
                // Third graph map
                    get_chart_map(data);
                // Fourth graph area
                    // make_chart_area(data);
                    $(".spinner-block").hide();

            });
        }
        get_data();
    });
    



</script>
@endsection