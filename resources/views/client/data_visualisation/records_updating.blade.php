@extends('layouts.app')

@section('content')
<form style="display:none">
    <input type="hidden" id="user_id" value="{{\Auth::user()->id}}">
</form>
<div class="container">
    @if(\MeetPAT\ThirdPartyService::find(1)->status == 'offline')
    <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-warning" role="alert">
                    <p><i class="fas fa-exclamation-triangle"></i> &nbsp;Due to a high volume of queries, were are experiencing a temporary delay with processing.</p>
                    <p>Please feel free to leave this page. We will notify you via email to 
                    <strong>
                        @if(Auth::user()->client_notification_detail)
                        {{Auth::user()->client_notification_detail->contact_email}}
                        @else
                        {{Auth::user()->email}}
                        @endif
                    </strong> once your dashboard is ready.</p>
                </div>
            </div>
        </div>
    @endif
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
<script type="text/javascript" src="{{asset('js/progress_tracking.min.js')}}"></script>
@endsection