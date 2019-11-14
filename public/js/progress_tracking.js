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

                    window.clearInterval(check_job_status);
                    $("#loader").css("display", "block");

                    window.location = '/meetpat-client/data-visualisation';
                }
            });

    }, 5000);
    

});