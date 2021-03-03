@extends('layouts.app')

@section('content')

<!-- User token -->
<form id="user_token_form">
    <input type="hidden" id="ApiToken" name="api_token" value="{{\Auth::user()->api_token}}">
    <input type="hidden" id="UserId" name="user_id" value="{{\Auth::user()->id}}">
</form>
<!-- End -->
<div class="container" id="tableContainer">
    <div class="row" id="tableControls">
        <div class="col-12 col-md-6">
            <h3>Saved Audiences</h3>
        </div>
        <div class="col-3 col-md-2 col-lg-1">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="/meetpat-client/audiences" class="btn btn-light"><i class="fas fa-arrow-left"></i></a>
                <a href="#" id="refreshBtn" class="btn btn-light"><i class="fas fa-sync-alt"></i></a>
            </div>
        </div>
        <div class="col-9 col-md-4 col-lg-5">
            <div class="input-group mb-2">
                <div class="input-group-prepend">
                    <div class="input-group-text"><i class="fas fa-search"></i></div>
                </div>
                <input type="text" class="form-control" id="InputSearchTerm" placeholder="search">
            </div>        
        </div>
    </div>
    <div class="row d-none d-sm-block" id="tableData">
        <div class="col-12">
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Date</th>
                        <th>Audience Name</th>
                        <th class="text-center">Audience</th>
                        <th class="text-center">Facebook</th>
                        <th class="text-center">Google</th>
                        <!-- <th class="text-center">Size</th> -->
                        <!-- <th class="text-center">Download</th> -->
                        <th class="text-center">Delete</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <tr>
                        <td colspan="8">
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
    <div class="row d-sm-none" id="mobileTableData">
        <table class="table table-bordered table-striped table-hover table-sm">
            <thead>
                <tr class="d-flex">
                    <th class="text-center col-2"><i class="fas fa-equals"></i></th>
                    <th class="text-center show-more col-10">File Name</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="2">
                        <div class="d-flex align-items-center">
                            <strong class="loading">Loading</strong>
                            <div class="spinner-border ml-auto spinner-border-sm" role="status" aria-hidden="true"></div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-12 col-md-9">
            <nav aria-label="..." class="mt-2">
                <ul class="pagination" id="paginationContainer">
                    <!-- pages -->
                </ul>
            </nav>
        </div>
        <div class="col-12 col-md-3 d-flex align-items-center">
            <span id="entriesInfo"></span>
        </div>
    </div>
</div>

@endsection

@section('modals')

<div id="uploadToFBContainer"></div>
<div id="uploadToGoogleContainer"></div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/files/saved_audience_files.js')}}"></script>
@endsection
