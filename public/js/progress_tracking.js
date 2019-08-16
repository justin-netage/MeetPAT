var get_percentage = function($records, $records_completed) {

    $percentage = ($records_completed / $records) * 100;

return Math.trunc($percentage);
}
$(document).ready(function() {
    
    var user_id_number = $("#user_id").val();

    $.post("/api/meetpat-client/get-job-que", {user_id: user_id_number}, function( data ) {
        for (var key in data["jobs"]) {
            if(data["jobs"][key]['status'] == 'done') {
                $("#records-status").append(
                '<h5 class="card-title progress-title">'+data["jobs"][key]["audience_file"]["audience_name"]+'</h5>' +
                '<div class="progress" id="job_' + key + '">' +
                '<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">complete</div>' +
                '</div> <br />'
            );
            } else if(data["jobs"][key]["status"] == 'pending') {
                $("#records-status").append(
                '<h5 class="card-title progress-title" id="progress_title_'+key+'" style="display: none";>'+data["jobs"][key]["audience_file"]["audience_name"]+'</h5>' +
                '<div class="d-flex align-items-center loader_title mb-1" id="loader_title_'+key+'">' +
                '<strong>'+data["jobs"][key]["audience_file"]["audience_name"]+'</strong>' +
                '<div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>' +
                '</div>' +    
                '<div class="progress" id="job_' + key + '">' +
                '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width:' + get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']) + '%" aria-valuenow="' + get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']) + '" aria-valuemin="0" aria-valuemax="100">'+ 
                'Pending...'+
                '</div>' +
                '</div> <br />'+
                '<p class="badge badge-pill badge-warning">Checking and removing duplicate records: <span id="recordsChecked_'+ key + '">' + data["jobs"][key]["records_checked"] + '/' + data["jobs"][key]["records"] + '</span></p>'

            );
            } else {
                $("#records-status").append(
                '<h5 class="card-title progress-title" id="progress_title_'+key+'" style="display: none";>'+data["jobs"][key]["audience_file"]["audience_name"]+'</h5>' +
                '<div class="d-flex align-items-center loader_title mb-1" id="loader_title_'+key+'">' +
                '<strong>'+data["jobs"][key]["audience_file"]["audience_name"]+'</strong>' +
                '<div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>' +
                '</div>' +
                '<div class="progress" id="job_' + key + '">' +
                '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width:' + get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']) + '%" aria-valuenow="' + get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']) + '" aria-valuemin="0" aria-valuemax="100">'+ get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']) + "%" +'</div>' +
                '</div> <br />'+
                '<p class="badge badge-pill badge-warning">Checking and removing duplicate records: <span id="recordsChecked_'+ key + '">' + data["jobs"][key]["records_checked"] + '/' + data["jobs"][key]["records"] + '</span></p>'
                );
            }
            

        }
            }).fail(function(data) {
                console.log(data)

            }).done(function(data) {
                

                $("#status-loader").hide();
                
        });

        window.setInterval(function() {

            $.post("/api/meetpat-client/get-job-que", {user_id: user_id_number}, function( data ) {
                for (var key in data["jobs"]) {
                    if(data["jobs"][key]["records"] !== data["jobs"][key]['records_completed'] && data["jobs"][key]["status"] == "running") {
                        $("#job_" + key + " .progress-bar").attr("aria-valuenow", get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']));
                        $("#job_" + key + " .progress-bar").html(get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']) + "%");
                        $("#job_" + key + " .progress-bar").width(get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']) + "%");
                    } else if(data["jobs"][key]["status"] == "done") {
                        $("#loader_title_" + key).remove();
                        $("#progress_title_" + key).show();
                        $("#job_" + key + " .progress-bar").attr("aria-valuenow", get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']));
                        $("#job_" + key + " .progress-bar").html("complete");
                        $("#job_" + key + " .progress-bar").width(get_percentage(data["jobs"][key]["records"], data["jobs"][key]['records_completed']) + "%");
                        $("#job_" + key + " .progress-bar").removeClass('bg-info');
                        $("#job_" + key + " .progress-bar").removeClass('progress-bar-animated');
                        $("#job_" + key + " .progress-bar").addClass('bg-success');
                        
                    }        
                    $("#recordsChecked_" + key ).html(data["jobs"][key]["records_checked"] + '/' + data["jobs"][key]["records"]);

                }

                if(data["jobs_running"] == 0) {

                    window.clearInterval();
                    $("#loader").css("display", "block");

                    window.location = '/meetpat-client/data-visualisation';
                }
            }).fail(function(data) {
                console.log(data)
                window.clearInterval();

            }).done(function(data) {
                
                console.log(data)

                

        });
                // if(data["jobs_running"] == 0) {
                //     window.location = '/meetpat-client/data-visualisation';
                // }
    }, 5000);
    

});