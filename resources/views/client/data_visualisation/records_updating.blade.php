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
                    <p>And email will be sent to <span style="color:#2196F3">{{Auth::user()->email}}</span> as soon as the process has completed.</p>
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

@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/progress_tracking.min.js')}}"></script>
@endsection