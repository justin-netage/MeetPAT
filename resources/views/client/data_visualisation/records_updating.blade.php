@extends('layouts.app')

@section('content')
<form style="display:none">
    <input type="hidden" id="user_id" value="{{\Auth::user()->id}}">
</form>
<div id="loader"></div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Records Updating</div>
                <div class="card-body" id="records-status">
                    <br />
                    <p>Updating large amounts of data can take time. Please be patient while your records are being processed.</p>
                    <br />
                    <div class="text-center mb-4" id="status-loader">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    progress tracking
                </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">

    var get_percentage = function($records, $records_completed) {

            $percentage = ($records_completed / $records) * 100;

        return Math.trunc($percentage);
    }
    $(document).ready(function() {
            
            var user_id_number = $("#user_id").val();

            $.post("/api/meetpat-client/get-job-que", {user_id: user_id_number}, function( data ) {
                    }).fail(function(data) {
                        console.log(data)

                    }).done(function(data) {
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
                                '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width:' + get_percentage(data["jobs"][key].records, data["jobs"][key].records_completed) + '%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">'+ 
                                'Pending...'+
                                '</div>' +
                                '</div> <br />'
                            );
                            } else {
                                $("#records-status").append(
                                '<h5 class="card-title progress-title" id="progress_title_'+key+'" style="display: none";>'+data["jobs"][key]["audience_file"]["audience_name"]+'</h5>' +
                                '<div class="d-flex align-items-center loader_title mb-1" id="loader_title_'+key+'">' +
                                '<strong>'+data["jobs"][key]["audience_file"]["audience_name"]+'</strong>' +
                                '<div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>' +
                                '</div>' +
                                '<div class="progress" id="job_' + key + '">' +
                                '<div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width:' + get_percentage(data["jobs"][key].records, data["jobs"][key].records_completed) + '%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">'+ get_percentage(data["jobs"][key].records, data["jobs"][key].records_completed) + "%" +'</div>' +
                                '</div> <br />'
                                );
                            }
                            

                        }

                        $("#status-loader").hide();
                        
                });

                window.setInterval(function() {

                    $.post("/api/meetpat-client/get-job-que", {user_id: user_id_number}, function( data ) {
                    }).fail(function(data) {
                        console.log(data)
                        window.clearInterval();

                    }).done(function(data) {
                        
                        //console.log(data)

                        for (var key in data["jobs"]) {
                            if(data["jobs"][key].records !== data["jobs"][key].records_completed && data["jobs"][key]["status"] == "running") {
                                $("#job_" + key + " .progress-bar").attr("aria-valuenow", get_percentage(data["jobs"][key].records, data["jobs"][key].records_completed));
                                $("#job_" + key + " .progress-bar").html(get_percentage(data["jobs"][key].records, data["jobs"][key].records_completed) + "%");
                                $("#job_" + key + " .progress-bar").width(get_percentage(data["jobs"][key].records, data["jobs"][key].records_completed) + "%");
                            } else if(data["jobs"][key]["status"] == "done") {
                                $("#loader_title_" + key).remove();
                                $("#progress_title_" + key).show();
                                $("#job_" + key + " .progress-bar").attr("aria-valuenow", get_percentage(data["jobs"][key].records, data["jobs"][key].records_completed));
                                $("#job_" + key + " .progress-bar").html("complete");
                                $("#job_" + key + " .progress-bar").width(get_percentage(data["jobs"][key].records, data["jobs"][key].records_completed) + "%");
                                $("#job_" + key + " .progress-bar").removeClass('bg-info');
                                $("#job_" + key + " .progress-bar").removeClass('progress-bar-animated');
                                $("#job_" + key + " .progress-bar").addClass('bg-success');
                                
                            }        
                            
                        }

                        if(data["jobs_running"] == 0) {
                            window.clearInterval();
                            $("#loader").css("display", "block");

                            window.location = '/meetpat-client/data-visualisation';
                        }

                });
                        // if(data["jobs_running"] == 0) {
                        //     window.location = '/meetpat-client/data-visualisation';
                        // }
            }, 5000);
            

    });

</script>

@endsection