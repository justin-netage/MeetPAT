@extends('layouts.app')

@section('content')

<form style="display:none">
    <input type="hidden" id="user_id" value="{{\Auth::user()->id}}">
</form>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Records Updating</div>

                <div class="card-body" id="records-status">
                    <br />
                    <p>Updating large amounts of data can take time. Please be patient while your records are being processed.</p>
                    @if(Auth::user()->client_notification_detail)
                    <p>An email will be sent to <span style="color:#2196F3">{{Auth::user()->client_notification_detail->contact_email}}</span> as soon as the process has completed.</p>
                    @else
                    <p>An email will be sent to <span style="color:#2196F3">{{Auth::user()->email}}</span> as soon as the process has completed.</p>
                    @endif
                    <br />
                    <div class="text-center mb-4" id="status-loader">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-muted">
                    Process running
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
var get_percentage = function($records, $records_completed) {

$percentage = ($records_completed / $records) * 100;

return Math.trunc($percentage);
}
$(document).ready(function() {

var user_id_number = $("#user_id").val();

    var check_job_status = window.setInterval(function() {

        $.post("/api/meetpat-client/update/get-job-queue", {user_id: user_id_number}, function( data ) {

        }).fail(function(data) {
            console.log(data)
            window.clearInterval(check_job_status);

        }).done(function(data) {     
            if(data["jobs_running"] == 0) {

                window.clearInterval(check_job_status);
                $("#loader").css("display", "block");

                window.location = '/meetpat-client/data-visualisation';
            }
        });

}, 5000);


});
</script>
@endsection