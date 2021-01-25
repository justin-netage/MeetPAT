@extends('layouts.app')

@section('content')

<!-- User token -->
<form id="user_token_form">
    <input type="hidden" id="ApiToken" name="api_token" value="{{\Auth::user()->api_token}}">
    <input type="hidden" id="UserId" name="user_id" value="{{\Auth::user()->id}}">
</form>
<!-- End -->
<div class="container">
    
</div>
<div class="container" id="tableContainer">
    <div class="row">
        <div class="col-12 col-md-6">
            <div class="col-12 col-md-6">
                <h3>Running Jobs</h3>
            </div>
        </div>
    </div>
    <div class="row" id="alertContainer">
        <div class="col-12">

        </div>
    </div>
    <div class="row" id="tableControlls">
        
        <div class="col-12">
            <div class="btn-group float-right" role="group" aria-label="Basic example">
                <a href="/" class="btn btn-light"><i class="fas fa-arrow-left"></i></a>
                <a href="#" id="refreshBtn" class="btn btn-light"><i class="fas fa-sync-alt"></i></a>
            </div>
        </div>
    </div>
    <div class="row d-none d-sm-block">
        <div class="col-12 table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Date</th>
                        <th>Client</th>
                        <th class="text-center">Matches</th>
                        <th class="text-center">Enriched</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Cancel</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <tr>
                        <td colspan="7">
                            <div class="d-flex align-items-center">
                                <strong class="loading">Loading</strong>
                                <div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('modals')
<div id="modalsContainer"></div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/admin/running-jobs/running_jobs.js')}}"></script>
@endsection