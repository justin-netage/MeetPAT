@extends('layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{asset('bower_components/jquery.bootgrid/dist/jquery.bootgrid.min.css')}}"/>
@endsection

@section('content')

<!-- User token -->
<form id="user_token_form">
    <input type="hidden" id="ApiToken" name="api_token" value="{{$user_api_token}}">
</form>
<!-- End -->

<div class="container" id="tableContainer">
    @if(\MeetPAT\ThirdPartyService::find(1)->status == 'offline')
    <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-warning" role="alert">
                    <p><i class="fas fa-exclamation-triangle"></i> BSA's SFTP Server is currently offline.</p>
                </div>
            </div>
        </div>
    @endif
    <div class="row" id="tableControls">
        <div class="col-12 col-md-6">
            <h3>MeetPAT Clients</h3>
        </div>
        <div class="col-3 col-md-2 col-lg-1">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="/" class="btn btn-light"><i class="fas fa-arrow-left"></i></a>
                <button type="button" id="refreshBtn" class="btn btn-light"><i class="fas fa-sync-alt"></i></button>
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
        <div class="col-12 table-responsive">
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                    <th class="text-center">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="text-center">Active</th>
                    <th class="text-center">Files</th>  
                    <th class="text-center">Edit</th>
                    <th class="text-center">Settings</th>
                    </tr>
                </thead>

                <tbody id="tableBody">
                    <tr>
                        <td colspan="6">
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
                    <th class="text-center show-more col-10">Name</th>
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
<div id="modalsContainer"></div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/meetpat_clients.min.js')}}"></script>
<script type="text/javascript">
    var auth_token = $("#ApiToken").val();    

    function getPagingRange(current, {min = 1, total = 20, length = 5} = {}) {
        if (length > total) length = total;

        let start = current - Math.floor(length / 2);
        start = Math.max(start, min);
        start = Math.min(start, min + total - length);
        
        return Array.from({length: length}, (el, i) => start + i);
    }

    $(document).ready(function() {
        var refresh_links = window.setInterval(function() { get_table_data($("#InputSearchTerm").val(), $("li.active a").text()) }, 300000);
        var get_table_data = function(search_term, page) {

            window.clearInterval(refresh_links);
            refresh_links = window.setInterval(function() { get_table_data($("#InputSearchTerm").val(), $("li.active a").text()) }, 300000);

            search_term = search_term || "";
            page = page || 1;

            $("#entriesInfo").empty();
            $("#paginationContainer").empty();

            $("#refreshBtn").prop("disabled", 1);
            $("#InputSearchTerm").prop("disabled", 1);

            $("#tableBody").html(
                "<tr>" +
                    "<td colspan=\"7\">" +
                        "<div class=\"d-flex align-items-center\">" +
                            "<strong class=\"loading\">Loading</strong>" +
                            "<div class=\"spinner-border ml-auto spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></div>" +
                        "</div>" +
                    "</td>" +
                "</tr>"
            );

            $("#mobileTableData tbody").html(
                "<tr>" +
                    "<td colspan=\"2\">" +
                        "<div class=\"d-flex align-items-center\">" +
                            "<strong class=\"loading\">Loading</strong>" +
                            "<div class=\"spinner-border ml-auto spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></div>" +
                        "</div>" +
                    "</td>" +
                "</tr>"
            );

            $.get("/api/meetpat-admin/get-users",
             {api_token: auth_token, page: page, search_term: search_term}, function(data, textStatus,jqXHR) {

                $("#tableBody").empty();
                $("#mobileTableData tbody").empty();
                $("#refreshBtn").prop("disabled", 0);
                $("#InputSearchTerm").prop("disabled", 0);
                $("#InputSearchTerm").focus();
                
                if(data.data.length) {
                    for(var key in data.data) {
                        
                        if(data.data[key].client.status = "true") {
                        $("#tableBody").append(
                            "<tr>" +
                                "<td class=\"text-center\">" + data.data[key].id + "</td>" +
                                "<td>" + data.data[key].name + "</td>" +
                                "<td><a href=\"mailto:" + data.data[key].email + "?Subject=MeetPAT\" target=\"_top\">" + data.data[key].email + "</a></td>" +
                                "<td class=\"text-center\">" +
                                "<i class=\"fas fa-toggle-on mr-0\" data-user-id=\"" + data.data[key].id + "\" onclick=\"set_status(this)\"></i>" +
                                "</td>" +
                                "<td class=\"text-center\"><a href=\"/meetpat-admin/users/files/" + data.data[key].id + "\"><i class=\"fas fa-folder mr-0\"></i></a></td>" +
                                "<td class=\"text-center\"><i class=\"fas fa-pen mr-0\" data-user-id=\"" + data.data[key].id + "\" onclick=\"open_edit(this)\"></i></td>" +
                                "<td class=\"text-center\"><i class=\"fas fa-sliders-h mr-0\" data-user-id=\"" + data.data[key].id + "\" onclick=\"open_settings(this)\"></i></td>" +
                            "</tr>"
                        );

                        $("#mobileTableData tbody").append(
                            "<tr class=\"mainData d-flex\">" +
                                "<td class=\"text-center show-more col-2\"><i class=\"fas fa-plus-circle mr-0\"></i></td>" +
                                "<td class=\"col-10\">" + data.data[key].name + "</td>" +
                            "</tr>" +
                            "<tr class=\"secondaryData d-none\">" +
                                "<td class=\"col-2\"></td>" +
                                "<td class=\"col-10\">" +
                                    "<ul class=\"list-unstyled\">" +
                                        "<li><strong>#</strong> " + data.data[key].id + "</li>" +
                                        "<li><strong>Email</strong> " + data.data[key].email + "</li>" +
                                        "<li><strong>Active</strong> <i class=\"fas fa-toggle-on\" data-user-id=\"" + data.data[key].id + "\" onclick=\"set_status(this)\"></i> </li>" +
                                        "<li><strong>Files</strong> <a href=\"/meetpat-admin/users/files/" + data.data[key].id + "\"><i class=\"fas fa-folder\"></i></a></li>" +
                                        "<li><strong>Edit</strong> <i class=\"fas fa-pen\" data-user-id=\"" + data.data[key].id + "\" onclick=\"open_edit(this)\"></i></li>" +
                                        "<li><strong>Settings</strong> <i class=\"fas fa-sliders-h\" data-user-id=\"" + data.data[key].id + "\" onclick=\"open_settings(this)\"></i></li>" +
                                    "</ul>" +
                                "</td>" +
                            "</tr>"
                        );
                        } else {
                            $("#tableBody").append(
                                "<tr>" +
                                    "<td class=\"text-center\">" + data.data[key].id + "</td>" +
                                    "<td>" + data.data[key].name + "</td>" +
                                    "<td><a href=\"mailto:" + data.data[key].email + "?Subject=MeetPAT\" target=\"_top\">" + data.data[key].email + "</a></td>" +
                                    "<td class=\"text-center\">" +
                                    "<i class=\"fas fa-toggle-off\" data-user-id=\"" + data.data[key].id + "\" onclick=\"set_status(this)\"></i>" +
                                    "</td>" +
                                    "<td class=\"text-center\"><a href=\"/meetpat-admin/users/files/" + data.data[key].id + "\"><i class=\"fas fa-folder mr-0\"></i></a></td>" +
                                    "<td class=\"text-center\"><i class=\"fas fa-pen mr-0\" data-user-id=\"" + data.data[key].id + "\" onclick=\"open_edit(this)\"></i></td>" +
                                    "<td class=\"text-center\"><i class=\"fas fa-sliders-h mr-0\" data-user-id=\"" + data.data[key].id + "\" onclick=\"open_settings(this)\"></i></td>" +
                                "</tr>"
                            );

                            $("#mobileTableData tbody").append(
                                "<tr class=\"mainData d-flex\">" +
                                    "<td class=\"text-center show-more col-2\"><i class=\"fas fa-plus-circle mr-0\"></i></td>" +
                                    "<td class=\"col-10\">" + data.data[key].name + "</td>" +
                                "</tr>" +
                                "<tr class=\"secondaryData d-none\">" +
                                    "<td class=\"col-2\"></td>" +
                                    "<td class=\"col-10\">" +
                                        "<ul class=\"list-unstyled\">" +
                                            "<li><strong>#</strong> " + data.data[key].id + "</li>" +
                                            "<li><strong>Email</strong> " + data.data[key].email + "</li>" +
                                            "<li><strong>Active</strong> <i class=\"fas fa-toggle-off\" data-user-id=\"" + data.data[key].id + "\" onclick=\"set_status(this)\"></i> </li>" +
                                            "<li><strong>Files</strong> <a href=\"/meetpat-admin/users/files/" + data.data[key].id + "\"><i class=\"fas fa-folder\"></i></a></li>" +
                                            "<li><strong>Edit</strong> <i class=\"fas fa-pen\" data-user-id=\"" + data.data[key].id + "\" onclick=\"open_edit(this)\"></i></li>" +
                                            "<li><strong>Settings</strong> <i class=\"fas fa-sliders-h\" data-user-id=\"" + data.data[key].id + "\" onclick=\"open_settings(this)\"></i></li>" +
                                        "</ul>" +
                                    "</td>" +
                                "</tr>"
                            );
                        }
                        
                    } 

                    $(".mainData" ).click(function() {
        
                        if($("i", this).hasClass("fa-plus-circle")) {
                            $("i", this).removeClass("fa-plus-circle");
                            $("i", this).addClass("text-danger");
                            $("i", this).addClass("fa-minus-circle");

                            $(this).next(".secondaryData", this).removeClass("d-none");
                            $(this).next(".secondaryData").addClass("d-flex");
                        } else {
                            $("i", this).addClass("fa-plus-circle");
                            $("i", this).removeClass("fa-minus-circle");
                            $("i", this).removeClass("text-danger");

                            $(this).next(".secondaryData").addClass("d-none");
                            $(this).next(".secondaryData").removeClass("d-flex");
                        }
                        
                    });

                    $("#entriesInfo").html(data.from + " to " + data.to + " of " + data.total + " entries");
                    var pagination_range = getPagingRange(data.current_page, {total: data.last_page, length: 3});
                        if(data.current_page == 1) {
                            $("#paginationContainer").append(
                            "<li class=\"page-item disabled\">" +
                                "<a class=\"page-link\" href=\"#\" tabindex=\"-1\" aria-disabled=\"true\">Previous</a>" +
                            "</li>"
                        );
                        } else {
                            $("#paginationContainer").append(
                            "<li class=\"page-item\">" +
                                "<a class=\"page-link\" data-page-number=\"" + (data.current_page - 1) + "\" href=\"#\" tabindex=\"-1\" aria-disabled=\"false\">Previous</a>" +
                            "</li>"
                            );
                        }
                        
                        pagination_range.forEach(function(item) {
                        
                            if(data.current_page == item) {
                                $("#paginationContainer").append(
                                "<li class=\"page-item active\" aria-current=\"page\">" +
                                    "<a class=\"page-link\" data-page-number=\"" + item + "\"  href=\"#\">" + item + " <span class=\"sr-only\">(current)</span></a>" +
                                "</li>"
                                )
                            } else {
                                $("#paginationContainer").append(
                                "<li class=\"page-item\">" +
                                    "<a class=\"page-link\" data-page-number=\"" + item + "\" href=\"#\">" + item + " </a>" +
                                "</li>"
                                )
                            }
                            
                        });
                        if(data.last_page == data.current_page) {
                            $("#paginationContainer").append(
                                "<li class=\"page-item disabled\">" +
                                    "<a class=\"page-link\" href=\"#\" aria-disabled=\"true\">Next</a>" +
                                "</li>"
                            )
                        } else {
                            $("#paginationContainer").append(
                                "<li class=\"page-item\">" +
                                    "<a class=\"page-link\" data-page-number=\"" + (data.current_page + 1) + "\" href=\"#\" aria-disabled=\"false\">Next</a>" +
                                "</li>"   
                            )
                        }

                } else {
                        $("#tableBody").html(
                            "<tr>" +
                                "<td colspan=\"7\">" +
                                    "<strong>No results found</strong>" +
                                "</td>" +
                            "</tr>"
                        );

                        $("#mobileTableData tbody").html(
                            "<tr>" +
                                "<td colspan=\"2\">" +
                                    "<strong>No results found</strong>" +
                                "</td>" +
                            "</tr>"
                        );
                    }

                    $(".page-link").click(function(event) {
                        event.preventDefault();
                        get_table_data($("#InputSearchTerm").val(), $(this).attr("data-page-number"));
                    });

                    $(".delete-file").click(function(event) {
                        event.preventDefault();
                        var confirmed = confirm("Are you sure that you would like to delete \"" + $(this).attr('data-filename') + "\"?");

                            if(confirmed == true) {
                                $(this).html(
                                    "<div class=\"spinner-border spinner-border-sm\" role=\"status\">" +
                                        "<span class=\"sr-only\">Loading...</span>" +
                                    "</div>"
                                )

                                $.post("/api/meetpat-admin/delete-file", {file_id: $(this).attr('data-file-id'), user_id: user_id, api_token: auth_token}, function() {

                                    if(data.current_page) {
                                        get_table_data($("#InputSearchTerm").val(), data.current_page);
                                    } else {
                                        get_table_data($("#InputSearchTerm").val(), 1);
                                    }
 
                                }).fail(function(error) {
                                    console.log("error");
                                    get_table_data($("#InputSearchTerm").val(), data.current_page);
                                });
                            } 
                    });

             }).fail(function(error) {
                 console.log(error);
             });
        }

        get_table_data($("#InputSearchTerm").val(), $("li.active a").text());

        $("#refreshBtn").click(function() {
            get_table_data($("#InputSearchTerm").val(), $("li.active a").text());
        });
            
        //setup before functions
        var typingTimer;                //timer identifier
        var doneTypingInterval = 500;  
        var $input = $('#InputSearchTerm');

        //on keyup, start the countdown
        $input.on('keyup', function () {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        //on keydown, clear the countdown 
        $input.on('keydown', function () {
            clearTimeout(typingTimer);
        });

        //user is "finished typing," do something
        function doneTyping () {
            //do something
            if($("#InputSearchTerm").val().length >= 2 || $("#InputSearchTerm").val().length == 0) {
                get_table_data($("#InputSearchTerm").val());
            }
        }
    });
    
</script>

@endsection