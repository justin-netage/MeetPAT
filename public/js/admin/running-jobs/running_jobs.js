Date.prototype.addHours = function(h) {
    this.setTime(this.getTime() + (h*60*60*1000));
    return this;
  }

  function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

$(document).ready(function() {

    var api_token = $("#ApiToken").val();

    var get_table_data = function() {
        $("#refreshBtn").addClass("disabled");
        $("#tableBody").html(
            "<tr>" +
                "<td colspan=\"7\">" +
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
                                if(data[key]["process_tracking"][p_key]["records_result"]) {
                                    pt_matches = numberWithCommas(data[key]["process_tracking"][p_key]["records_result"]);
                                } else {
                                    pt_matches = "<i class=\"fas fa-check-circle text-success\"></i>";
                                }
                                
                            } else {
                                pt_matches = data[key]["process_tracking"][p_key]["status"];
                            }
                            
                        } else if( data[key]["process_tracking"][p_key]["job"] == "enriched_import" ) {
                            if(data[key]["process_tracking"][p_key]["status"] == "complete") {
                                if(data[key]["process_tracking"][p_key]["records_result"]) {
                                    pt_enrichment = numberWithCommas(data[key]["process_tracking"][p_key]["records_result"]);
                                } else {
                                    pt_enrichment = "<i class=\"fas fa-check-circle text-success\"></i>";
                                }
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

               var months = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];

               var created_at = new Date(data[key]["created_at"]);
                   created_at.addHours(2);


                var cancelButton = "<i class=\"fas fa-ban\"></i>";

                if(data[key].status !== 'done') {
                    cancelButton = "<i class=\"far fa-window-close cancel-job-request-button\" id=\"cancel_job_" + data[key]["id"] + "\" data-id=\"" + data[key]["id"] + "\"></i>";
                }
                   
                $("#tableBody").append(
                    "<tr>" +
                        "<td class=\"text-center\">" + (parseInt(key) + 1) + "</td>" +
                        "<td>" + 
                            created_at.getFullYear() + "-" + months[created_at.getMonth()] + "-" + created_at.getDate() + " " +
                            ('0' + created_at.getHours()).slice(-2) + ":" + ('0' + created_at.getMinutes()).slice(-2) + ":" + ('0' + created_at.getSeconds()).slice(-2) + 
                        "</td>" +
                        "<td>" + data[key]["user"]["name"] + "</td>" +
                        "<td class=\"text-center\">" + pt_matches + "</td>" +
                        "<td class=\"text-center\">" + pt_enrichment + "</td>" +
                        "<td class=\"text-center\">" + status + "</td>" +
                        "<td class=\"text-center\" id=\"cancelJobCol-" + data[key]["id"] + "\">" + cancelButton + "</td>" +
                    "</tr>"
                )                
        }

        $(".cancel-job-request-button").click(function() {
            $("#modalsContainer").html(
            "<div id=\"cancelJobModal-" + $(this).data("id") + "\" class=\"modal\" data-backdrop=\"static\" data-keyboard=\"false\" tabindex=\"-1\" role=\"dialog\">" +
                "<div class=\"modal-dialog\" role=\"document\">" +
                    "<div class=\"modal-content\">" +
                    "<div class=\"modal-header\">" +
                        "<h5 class=\"modal-title\">Cancel Job</h5>" +
                        "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">" +
                        "<span aria-hidden=\"true\">&times;</span>" +
                        "</button>" +
                    "</div>" +
                    "<div class=\"modal-body\" id=\"modalBody\">" +
                    "<div class=\"d-flex justify-content-center\">" +
                        "<p>Are you sure that you want to cancel this job?</p>" +
                    "</div>" +
                    "<div class=\"modal-footer\">" +
                        "<button data-id=\"" + $(this).data("id") + "\" type=\"button\" class=\"btn btn-primary cancel-job-request\"><strong>Yes</strong></button>" +
                        "<button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\"><strong>No</strong></button>" +
                    "</div>" +
                    "</div>" +
                "</div>" +
            "</div>"
        );

        $("#cancelJobModal-" + $(this).data("id")).modal("show");
        $("#cancelJobModal-" + $(this).data("id")).on("hidden.bs.modal", function(event) {
            $("#modalsContainer").empty();
        });

        $(".cancel-job-request").click(function() {
            var col = $("#cancelJobCol-" + $(this).data("id"));
            var job_client =  col.parent().children("td")[2].textContent;
            $(this).prop("disabled", 1);
            $("#cancelJobModal-" + $(this).data("id")).modal('hide');
            col.html("<i class=\"fas fa-sync-alt fa-spin\"></i>");
            $.post("/api/meetpat-admin/cancel-job", 
            {api_token: api_token, job_id: $(this).data("id")},
            function(data) {
                $("#alertContainer .col-12").html(
                    "<div class=\"alert alert-success alert-dismissible fade show\" role=\"alert\">" +
                    "<strong>" + job_client + "'s</strong> job has been successfully cancelled." +
                    "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">" +
                        "<span aria-hidden=\"true\">&times;</span>" +
                    "</button>" +
                "</div>"
                );
                col.parent().remove();
                console.log(data);
            });
        });

    });

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