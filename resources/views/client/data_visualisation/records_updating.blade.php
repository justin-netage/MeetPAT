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

                <div class="card-body">
                    <div class="progress">
                        <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
                    </div>
                    <p>Updating large ammounts of data can take time. Please be patient while your records are being processed.</p>
                </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">

    var get_percentage = function($records, $records_completed) {

            $percentage = ($records_completed / $records) * 100;

        return $percentage;
    }
    $(document).ready(function() {

        var get_qued_jobs = function() {

            var user_id_number = $("#user_id").val();

            $.post("/api/meetpat-client/get-job-que", {user_id: user_id_number}, function( data ) {
                }).fail(function(data) {
                    console.log(data)
                    clearInterval();

                }).done(function(data) {
                    var job = data[0];

                    if(job.records !== job.records_completed) {
                        $(".progress-bar").attr("aria-valuenow", get_percentage(job.records, job.records_completed));
                        $(".progress-bar").html(get_percentage(job.records, job.records_completed) + "%");
                        $(".progress-bar").width(get_percentage(job.records, job.records_completed) + "%");
                    } else {
                        $(".progress-bar").attr("aria-valuenow", get_percentage(data.records, data.records_completed));
                        $(".progress-bar").html(get_percentage(job.records, job.records_completed) + "%");
                        $(".progress-bar").width(get_percentage(job.records, job.records_completed) + "%");

                        clearInterval();
                    }

                    console.log(data);
                });

            }   

            window.setInterval(function() {
                get_qued_jobs();
            }, 5000);

    });

</script>

@endsection