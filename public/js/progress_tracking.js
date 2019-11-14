var get_percentage = function($records, $records_completed) {

    $percentage = ($records_completed / $records) * 100;

return Math.trunc($percentage);
}
$(document).ready(function() {
    
    var user_id_number = $("#user_id").val();

        var check_job_status = window.setInterval(function() {

            $.post("/api/meetpat-client/get-job-que", {user_id: user_id_number}, function( data ) {

            }).fail(function(data) {
                console.log(data)
                window.clearInterval(check_job_status);

            }).done(function(data) {     
                if(data["jobs_running"] == 0 && data["jobs_processing"] == 0) {
                    $(".alert-box").html("<div class=\"alert alert-success\" role=\"alert\">The process has completed. <span class=\"loading\">Loading dashboard</span></div>");
                    window.clearInterval(check_job_status);
                    $("#loader").css("display", "block");

                    window.location = '/meetpat-client/data-visualisation';
                } else {
                    if(data["jobs_running"] == 0 && data["jobs_processing"] != 0) {
                        $(".alert-box").html("<div class=\"alert alert-primary\" role=\"alert\">The process is about to complete in a few <span class=\"loading\">seconds</span></div>");
                    }
                }
            });

    }, 5000);
    

});