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
            <h3>Uploaded Audience Files</h3>
        </div>
        <div class="col-3 col-md-2 col-lg-1">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="/meetpat-client/files" class="btn btn-light"><i class="fas fa-arrow-left"></i></a>
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
                        <th>Original Data Source</th>
                        <th class="text-center">Size</th>
                        <th class="text-center">Download</th>
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
                    <th class="text-center show-more col-10">Audience Name</th>
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

@section('scripts')
<script>
    var user_id = $("#UserId").val();
    var auth_token = $("#ApiToken").val();

    function getPagingRange(current, {min = 1, total = 20, length = 5} = {}) {
        if (length > total) length = total;

        let start = current - Math.floor(length / 2);
        start = Math.max(start, min);
        start = Math.min(start, min + total - length);
        
        return Array.from({length: length}, (el, i) => start + i);
    }

    $(document).ready(function() {
        //var refresh_links = window.setInterval(function() { get_table_data($("#InputSearchTerm").val(), $("li.active a").text()) }, 300000);
        var get_table_data = function(search_term, page) {
            //window.clearInterval(refresh_links);
            //refresh_links = window.setInterval(function() { get_table_data($("#InputSearchTerm").val(), $("li.active a").text()) }, 300000);

            search_term = search_term || "";
            page = page || 1;

            $("#entriesInfo").empty();
            $("#paginationContainer").empty();

            $("#refreshBtn").prop("disabled", 1);
            $("#InputSearchTerm").prop("disabled", 1);

            $("#tableBody").html(
                "<tr>" +
                    "<td colspan=\"6\">" +
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

            $.get("/api/meetpat-client/files/get-uploaded-audiences",
             {api_token: auth_token, user_id: user_id, page: page, search_term: search_term}, function(data, textStatus,jqXHR) {

                $("#tableBody").empty();
                $("#mobileTableData tbody").empty();
                $("#refreshBtn").prop("disabled", 0);
                $("#InputSearchTerm").prop("disabled", 0);
                $("#InputSearchTerm").focus();
                
                if(data.data.length) {
                    for(var key in data.data) {
                        $("#tableBody").append(
                            "<tr>" +
                                "<td class=\"text-center\">" + (parseInt(key, 10) + 1) + "</td>" +
                                "<td>" + data.data[key].created_at + "</td>" +
                                "<td>" + data.data[key].audience_name + "</td>" +
                                "<td>" + data.data[key].file_source_origin + "</td>" +
                                "<td class=\"text-center\">" + data.data[key].size + "</td>" +
                                "<td class=\"text-center\">" + "<a href=\"" + data.data[key].download + "\"><i class=\"fas fa-file-csv\"></i></a></td>" +
                            "</tr>" 
                        );

                        $("#mobileTableData tbody").append(
                            "<tr class=\"mainData d-flex\">" +
                                "<td class=\"text-center show-more col-2\"><i class=\"fas fa-plus-circle mr-0\"></i></td>" +
                                "<td class=\"col-10\">" + data.data[key].audience_name + "</td>" +
                            "</tr>" +
                            "<tr class=\"secondaryData d-none\">" +
                                "<td class=\"col-2\"></td>" +
                                "<td class=\"col-10\">" +
                                    "<ul class=\"list-unstyled\">" +
                                        "<li><strong>#</strong> " + (parseInt(key, 10) + 1) + "</li>" +
                                        "<li><strong>Date</strong> " + data.data[key].created_at + "</li>" +
                                        "<li><strong data-toggle=\"tooltip\" title=\"Original Data Source\" data-trigger=\"click\">ODS</strong> " + data.data[key].file_source_origin + " </li>" +
                                        "<li><strong>Size</strong> " + data.data[key].size + "</li>" +
                                        "<li><strong>Download</strong> <a href=\"" + data.data[key].download + "\"><i class=\"fas fa-file-csv\"></i></a></li>" +
                                    "</ul>" +
                                "</td>" +
                            "</tr>"
                        );

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
                            $(this).next(".secondaryData", this).removeClass("d-flex");
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
                                "<td colspan=\"6\">" +
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

             }).fail(function(error) {
                 //console.log(error);
             });
        }

        get_table_data($("#InputSearchTerm").val(), $("li.active a").text());

        $("#refreshBtn").click(function(event) {
            event.preventDefault();
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

        // Enabled tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        
    });
</script>
@endsection
