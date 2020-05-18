Date.prototype.addHours = function(h) {
    this.setTime(this.getTime() + (h*60*60*1000));
    return this;
  }

$(document).ready(function() {

    var api_token = $("#ApiToken").val();

    var get_table_data = function() {
        $("#refreshBtn").addClass("disabled");
        $("#tableBody").html(
            "<tr>" +
                "<td colspan=\"6\">" +
                    "<div class=\"d-flex align-items-center\">" +
                        "<strong class=\"loading\">Loading</strong>" +
                        "<div class=\"spinner-border ml-auto spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></div>" +
                    "</div>" +
                "</td>" +
            "</tr>"
        );



        $.get("/api/meetpat-admin/running-jobs",
        {api_token: api_token},
        function(data) {
            console.log(data);

           $("#tableBody").html("");
           for(var key in data) {

               var pt_matches = "";
               var pt_enrichment = "";
               var status = "";

               if(data[key]["process_tracking"]) {
                    for( var p_key in data[key]["process_tracking"] ) {
                        if( data[key]["process_tracking"][p_key]["job"] == "matches_import" ) {
                            if(data[key]["process_tracking"][p_key]["status"] == "complete") {
                                pt_matches = "<i class=\"fas fa-check-circle text-success\"></i>";
                            } else {
                                pt_matches = data[key]["process_tracking"][p_key]["status"];
                            }
                            
                        } else if( data[key]["process_tracking"][p_key]["job"] == "enriched_import" ) {
                            if(data[key]["process_tracking"][p_key]["status"] == "complete") {
                                pt_enrichment = "<i class=\"fas fa-check-circle text-success\"></i>";
                            } else {
                                pt_enrichment = data[key]["process_tracking"][p_key]["status"];
                            }
                        }
                    }
               } else {
                pt_matches = "N\A";
                pt_enrichment = "N\A";
               }

               if(pt_matches == "") {
                pt_matches = "No Matches";
               }

               if(pt_enrichment == "") {
                pt_enrichment = "No Upload";
               }

               if(data[key]["status"] == "running") {
                    status = "running";
               } else if(data[key]["status"] == "done") {
                    status = "<i class=\"fas fa-check-circle text-success\"></i>";
               } else {
                    status = "<i class=\"fas fa-times-circle text-danger\"></i>";
               }

               var created_at = new Date(data[key]["created_at"]);
                   created_at.addHours(2);
                   
                $("#tableBody").append(
                    "<tr>" +
                        "<td class=\"text-center\">" + (parseInt(key) + 1) + "</td>" +
                        "<td>" + 
                            created_at.getFullYear() + "-" + ('0' + created_at.getMonth()).slice(-2) + "-" + ('0' + created_at.getDate()).slice(-2) + " " +
                            ('0' + created_at.getHours()).slice(-2) + ":" + ('0' + created_at.getMinutes()).slice(-2) + ":" + ('0' + created_at.getSeconds()).slice(-2) + 
                        "</td>" +
                        "<td>" + data[key]["user"]["name"] + "</td>" +
                        "<td class=\"text-center\">" + pt_matches + "</td>" +
                        "<td class=\"text-center\">" + pt_enrichment + "</td>" +
                        "<td class=\"text-center\">" + status + "</td>" +
                    "</tr>"
                )
           }

           $("#refreshBtn").removeClass("disabled");
       }).fail(function(error) {
           console.log(error);
       })

    }

    get_table_data();

    $("#refreshBtn").click(function(event) {
        event.preventDefault();
        get_table_data();
    });

});