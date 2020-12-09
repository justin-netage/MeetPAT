@extends('layouts.app')

@section('content')

<div class="container" style="margin-bottom: 320px;">
    <div class="row justify-content-center">

        <a class="card-link card-link-secondary" href="/meetpat-client/upload-client-file-data">
            <div class="card border-secondary">
                <div class="card-header">Upload Audience</div>
                <div class="card-body text-secondary">
                    <h5 class="card-title text-center"><i class="fas fa-file-upload"></i></h5>
                    <p class="card-text">Upload a new audience to view in dashboard.</p>
                </div>
            </div>
        </a>

        <!-- <a class="card-link card-link-secondary" href="/meetpat-client/upload/update-custom-metrics">
            <div class="card border-secondary">
                <div class="card-header">Update Audience</div>
                <div class="card-body text-secondary">
                    <h5 class="card-title text-center"><i class="fas fa-sync-alt"></i></h5>
                    <p class="card-text">Update your current audience custom metric data.</p>
                </div>
            </div>
        </a> -->

    </div>
</div>

<div role="alert" aria-live="assertive" aria-atomic="true" class="toast d-none d-sm-block" data-autohide="false" style="position: absolute; top: 175px; right: 0;">
  <div class="toast-header">
    <strong class="mr-auto"><i class="fas fa-download"></i>&nbsp;<a href="https://s3.amazonaws.com/dashboard.meetpat/public/sample/MeetPAT Template.csv">Download Template File</a></strong>
    <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {

        $('.toast').toast('show')

    });
</script>
@endsection