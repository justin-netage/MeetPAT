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
        <div class="col-12 col-md-6">
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <div class="input-group-text"><i class="fas fa-search"></i></div>
            </div>
            <input type="text" class="form-control" id="InputSearchTerm" placeholder="search">
        </div>        
        </div>
    </div>
    <div class="row" id="tableData">
        <div class="col-12 table-responsive">
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
        
        var get_table_data = function(search_term, page) {
            search_term = search_term || "";
            page = page || 1;

            $("#entriesInfo").empty();
            $("#paginationContainer").empty();

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

            $.get("/api/meetpat-client/files/get-uploaded-audiences",
             {api_token: auth_token, user_id: user_id, page: page, search_term: search_term}, function(data, textStatus,jqXHR) {

                $("#tableBody").empty();

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
                    } 

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
                                "<a class=\"page-link\" href=\"#\" tabindex=\"-1\" aria-disabled=\"false\">Previous</a>" +
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
                                    "<a class=\"page-link\" data-page-number=\"" + data.last_page + "\" href=\"#\" aria-disabled=\"false\">Next</a>" +
                                "</li>"   
                            )
                        }

                } else {
                        $("#tableBody").html(
                            "<tr>" +
                                "<td colspan=\"6\">" +
                                    "<strong>No results found for \"" + $("#InputSearchTerm").val() + "\"</strong>" +
                                "</td>" +
                            "</tr>"
                        );
                    }

                    $(".page-link").click(function(event) {
                        event.preventDefault();
                        get_table_data($("#InputSearchTerm").val(), $(this).attr("data-page-number"));
                    });

             }).fail(function(error) {
                 console.log(error);
             });
        }

        get_table_data();
            
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
