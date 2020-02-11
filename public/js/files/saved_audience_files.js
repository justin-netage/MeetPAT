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
    // check if there are pending jobs 
    var check_pending_jobs = window.setInterval(function() {
        console.log(Date.now());
        $.get("/api/meetpat-client/files/get-saved-audiences",
            {api_token: auth_token, user_id: user_id, page: 1, search_term: ''} ,function(data, textStatus,jqXHR) {
            if(data.data.length) {
                    for(var key in data.data) {
                        if(data.data[key].fb_audience_upload_job.length) {

                            var has_pending_job = false;

                            for(var job_key in data.data[key].fb_audience_upload_job) {
                                
                                if(data.data[key].fb_audience_upload_job[job_key].status == 'pending' || data.data[key].fb_audience_upload_job[job_key].status == 'processing')
                                {
                                    has_pending_job = true;
                                } 
                            }

                            if(has_pending_job) {
                                $("#uploadToFb-" + data.data[key].id).html("<div><div class=\"bars3\" title=\"uploading\"><span></span><span></span><span></span><span></span><span></span</div></div>");
                            } else {
                                $("#uploadToFb-" + data.data[key].id).html("<i class=\"fab upload-to-fb fa-facebook-square text-facebook\" data-filter-id=\"" + data.data[key].id + "\"></i>");
                            }
                        }
                    }

                    $(".upload-to-fb").unbind();

                    $(".upload-to-fb").click(function() {
                        filtered_audience_id = $(this).data("filter-id");
                        var confirmed = confirm("Are you sure that you want to upload \"" + $("#uploadToFb-" + filtered_audience_id).prev().html() + "\" to you custom audience lists?");
                        if(confirmed) {
                            $("#uploadToFBContainer").html(
                                "<div class=\"modal mt-5\" id=\"modalUploadToFB-" + filtered_audience_id + "\" tabindex=\"-1\" role=\"dialog\">" +
                                    "<div class=\"modal-dialog\" role=\"document\">" +
                                        "<div class=\"modal-content\">" +
                                        "<div class=\"modal-header\">" +
                                            "<h5 class=\"modal-title\">Facebook Custom Audience Upload</h5>" +
                                            "<button type=\"button\" class=\"close d-none\" data-dismiss=\"modal\" aria-label=\"Close\">" +
                                            "<span aria-hidden=\"true\">&times;</span>" +
                                            "</button>" +
                                        "</div>" +
                                        "<div class=\"modal-body\">" +
                                            "<div class=\"d-flex align-items-center\">" +
                                                "<strong class=\"text-facebook loading\">Processing data for upload</strong>" +
                                                "<div class=\"spinner-container spinner-border spinner-border-sm ml-auto\" role=\"status\" aria-hidden=\"true\"></div>" +
                                            "</div>" +
                                            "<div id=\"help-text\"><div class=\"alert alert-info mt-2\"><strong>Info</strong> - Please note that once the upload has completed it will still take up to an hour (or more) for facebook to get matches.</div></div>" +
                                        "</div>" +
                                        "</div>" +
                                    "</div>" +
                                "</div>"
                            );

                            $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id).modal({
                                backdrop: 'static',
                                keyboard: false,
                                show: true
                            });
                            
                            $.post("/api/meetpat-client/facebook/custom-audience/create", {user_id: user_id, filtered_audience_id: filtered_audience_id, api_token: auth_token}, function(data) {

                                if(data["status"] == "success") {
                                    $("#uploadToFb-" + filtered_audience_id).html("<div><div class=\"bars3\" title=\"uploading\"><span></span><span></span><span></span><span></span><span></span</div></div>");
                                    $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .spinner-container").removeClass('spinner-border').removeClass('spinner-border-sm').html('<i class="fas fa-check-circle text-success"></i>');
                                    $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body strong").removeClass('loading');
                                    setTimeout(() => {
                                        $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id).modal('hide');
                                    }, 2000);
                                } else {
                                    $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .spinner-container").removeClass('spinner-border').removeClass('spinner-border-sm').html('<i class="text-warning fas fa-exclamation-triangle"></i>');
                                    $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body #help-text").html('<div class="alert alert-warning mt-2"><strong>Warning</strong> - Your account has not been linked with a Facebook Ad Account. Follow this <a href="/meetpat-client/sync/facebook">link</a> to connect your Facebook Ad Account</div>');
                                    $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body strong").removeClass('loading');
                                    $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-header button").removeClass('d-none');
                                }

                            }).fail(function(error) {
                                
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .spinner-container").removeClass('spinner-border').removeClass('spinner-border-sm').html('<i class="text-danger fas fa-times-circle"></i>');
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body #help-text").html('<div class="alert alert-danger mt-2"><strong>Error</strong> - Make sure that your Ad Account ID is correct and linked with a business account. Contact MeetPAT Support for more assistance</div>');
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body strong").removeClass('loading');
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-header button").removeClass('d-none');
                                console.log(error);
                            });
                        }

                    });

                }

        }).fail(function(error) {
            console.log(error);
        });
        }, 120000);
    var get_table_data = function(search_term, page) {

        //window.clearInterval(check_pending_jobs);

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

        $.get("/api/meetpat-client/files/get-saved-audiences",
            {api_token: auth_token, user_id: user_id, page: page, search_term: search_term}, function(data, textStatus,jqXHR) {

            $("#tableBody").empty();
            $("#mobileTableData tbody").empty();
            $("#refreshBtn").prop("disabled", 0);
            $("#InputSearchTerm").prop("disabled", 0);
            $("#InputSearchTerm").focus();

            if(data.data.length) {
                
                for(var key in data.data) {
                    
                    fb_upload_html = "<td id=\"uploadToFb-" + data.data[key].id + "\" class=\"text-center\"><i class=\"fab upload-to-fb fa-facebook-square text-facebook\" data-filter-id=\"" + data.data[key].id + "\"></i></td>";
                    
                    if(data.data[key].fb_audience_upload_job.length) {
                        
                        var has_job_in_queue = false;

                        for(var job_key in data.data[key].fb_audience_upload_job)
                        {
                            if(data.data[key].fb_audience_upload_job[job_key].status === 'pending') {
                                has_job_in_queue = true;
                            } 
                        }

                        if(has_job_in_queue) {
                            fb_upload_html = "<td id=\"uploadToFb-" + data.data[key].id + "\" class=\"text-center\"><div><div class=\"bars3\" data-filter-id=\"" + data.data[key].id + "\"><span></span><span></span><span></span><span></span><span></span></div></div></td>";
                        }

                    } 
                    
                    $("#tableBody").append(
                        "<tr>" +
                            "<td class=\"text-center\">" + (parseInt(key, 10) + 1) + "</td>" +
                            "<td>" + data.data[key].created_at + "</td>" +
                            "<td class=\"text-truncate\" style=\"max-width: 125px;\" title=\"" + data.data[key].file_name + "\">" + data.data[key].file_name + "</td>" +
                                fb_upload_html +
                            "<td class=\"text-center\">" + data.data[key].size + "</td>" +
                            "<td class=\"text-center\">" + "<a href=\"" + data.data[key].download + "\"><i class=\"fas fa-file-csv\"></i></a></td>" +
                            "<td class=\"text-center\">" + "<a href=\"#\" class=\"delete-file\" data-file-uuid=\"" + data.data[key].file_unique_name + "\" data-filename=\"" + data.data[key].file_name + "\"><i class=\"fas fa-trash-alt text-danger\"></i></a></td>" +
                        "</tr>" 
                    );

                    $("#mobileTableData tbody").append(
                        "<tr class=\"mainData d-flex\">" +
                            "<td class=\"text-center show-more col-2\"><i class=\"fas fa-plus-circle mr-0\"></i></td>" +
                            "<td class=\"col-10\">" + data.data[key].file_name + "</td>" +
                        "</tr>" +
                        "<tr class=\"secondaryData d-none\">" +
                            "<td class=\"col-2\"></td>" +
                            "<td class=\"col-10\">" +
                                "<ul class=\"list-unstyled\">" +
                                    "<li><strong>#</strong> " + (parseInt(key, 10) + 1) + "</li>" +
                                    "<li><strong>Date</strong> " + data.data[key].created_at + "</li>" +
                                    "<li><strong>Size</strong> " + data.data[key].size + "</li>" +
                                    "<li><strong>Upload</strong><td><button class=\"btn btn-success\"><i class=\"fab fa-facebook-f\"></i></button></td></li>" +
                                    "<li><strong>Download</strong> <a href=\"" + data.data[key].download + "\"><i class=\"fas fa-file-csv\"></i></a></li>" +
                                    "<li><strong>Delete</strong> <a href=\"#\" class=\"delete-file\" data-file-uuid=\"" + data.data[key].file_unique_name + "\" data-filename=\"" + data.data[key].file_name + "\"><i class=\"fas fa-trash-alt text-danger\"></i></a></li>" +
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
                        $(this).next(".secondaryData").removeClass("d-flex");
                    }
                    
                });

                $(".upload-to-fb").unbind();

                $(".upload-to-fb").click(function() {
                    var filtered_audience_id = $(this).data("filter-id");

                    var confirmed = confirm("Are you sure that you want to upload \"" + $("#uploadToFb-" + filtered_audience_id).prev().html() + "\" to you custom audience lists?");

                    if(confirmed) {
                        $("#uploadToFBContainer").html(
                            "<div class=\"modal mt-5\" id=\"modalUploadToFB-" + filtered_audience_id + "\" tabindex=\"-1\" role=\"dialog\">" +
                                "<div class=\"modal-dialog\" role=\"document\">" +
                                    "<div class=\"modal-content\">" +
                                    "<div class=\"modal-header\">" +
                                        "<h5 class=\"modal-title\">Facebook Custom Audience Upload</h5>" +
                                        "<button type=\"button\" class=\"close d-none\" data-dismiss=\"modal\" aria-label=\"Close\">" +
                                        "<span aria-hidden=\"true\">&times;</span>" +
                                        "</button>" +
                                    "</div>" +
                                    "<div class=\"modal-body\">" +
                                        "<div class=\"d-flex align-items-center\">" +
                                            "<strong class=\"text-facebook loading\">Processing data for upload</strong>" +
                                            "<div class=\"spinner-container spinner-border spinner-border-sm ml-auto\" role=\"status\" aria-hidden=\"true\"></div>" +
                                        "</div>" +
                                        "<div id=\"help-text\"><div class=\"alert alert-info mt-2\"><strong>Info</strong> - Please note that once the upload has completed it will still take up to an hour (or more) for facebook to get matches.</div></div>" +
                                    "</div>" +
                                    "</div>" +
                                "</div>" +
                            "</div>"
                        );

                        $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id).modal({
                            backdrop: 'static',
                            keyboard: false,
                            show: true
                        });
                        
                        $.post("/api/meetpat-client/facebook/custom-audience/create", {user_id: user_id, filtered_audience_id: filtered_audience_id, api_token: auth_token}, function(data) {
                            
                            if(data["status"] == "success") {
                                $("#uploadToFb-" + filtered_audience_id).html("<div><div class=\"bars3\" title=\"uploading\"><span></span><span></span><span></span><span></span><span></span</div></div>");
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .spinner-container").removeClass('spinner-border').removeClass('spinner-border-sm').html('<i class="fas fa-check-circle text-success"></i>');
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body strong").removeClass('loading');
                                setTimeout(() => {
                                    $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id).modal('hide');
                                }, 2000);
                            } else {
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .spinner-container").removeClass('spinner-border').removeClass('spinner-border-sm').html('<i class="text-warning fas fa-exclamation-triangle"></i>');
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body #help-text").html('<div class="alert alert-warning mt-2"><strong>Warning</strong> - Your account has not been linked with a Facebook Ad Account. Follow this <a href="/meetpat-client/sync/facebook">link</a> to connect your Facebook Ad Account</div>');
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body strong").removeClass('loading');
                                $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-header button").removeClass('d-none');
                            }

                        }).fail(function(error) {
                            
                            $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .spinner-container").removeClass('spinner-border').removeClass('spinner-border-sm').html('<i class="text-danger fas fa-times-circle"  data-dismiss="modal" aria-label="Close"></i>');
                            $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body #help-text").html('<div class="alert alert-danger mt-2"><strong>Error</strong> - Make sure that your Ad Account ID is correct and linked with a business account. Contact MeetPAT Support for more assistance</div>');
                            $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-body strong").removeClass('loading');
                            $("#uploadToFBContainer #modalUploadToFB-" + filtered_audience_id + " .modal-header button").removeClass('d-none');
                            console.log(error);
                        });

                    }
                    
                });

                $("#entriesInfo").html(data.from + " to " + data.to + " of " + data.total + " entries");
                var pagination_range = getPagingRange(data.current_page, {total: data.last_page, length: 3});
                    if(data.current_page == 1) {
                        $("#paginationContainer").append(
                        "<li class=\"page-item disabled\">" +
                            "<a class=\"page-link\"  href=\"#\" tabindex=\"-1\" aria-disabled=\"true\">Previous</a>" +
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

                $(".delete-file").click(function(event) {
                    event.preventDefault();
                    var confirmed = confirm("Are you sure that you would like to delete \"" + $(this).attr('data-filename') + "\"?");

                        if(confirmed == true) {
                            $(this).html(
                                "<div class=\"spinner-border spinner-border-sm\" role=\"status\">" +
                                    "<span class=\"sr-only\">Loading...</span>" +
                                "</div>"
                            )

                            $.post("/api/meetpat-client/delete-saved-audience-file", {file_unique_name: $(this).attr('data-file-uuid'), user_id: user_id}, function() {

                                if(data.current_page) {
                                    get_table_data($("#InputSearchTerm").val(), data.current_page);
                                } else {
                                    get_table_data($("#InputSearchTerm").val(), 1);
                                }

                            }).fail(function(error) {
                                // console.log("error");
                            });
                        } 
                });
                
            }).fail(function(error) {
                // console.log(error);
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

});