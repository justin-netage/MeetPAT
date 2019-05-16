var get_percentage = function($records, $records_completed) {

    $percentage = ($records_completed / $records) * 100;

    return Math.trunc($percentage);
}

// Validation 
function checkForm(current_form_el) {
    // if(!$(current_form_el)[0].valid()) {
    //     $(".message-container").prepend(`<div id="AlertBoxTemp" class="alert alert-danger alert-dismissible fade show" role="alert">
    //                                         <strong>Error!</strong> Please make sure that all fields are correct.
    //                                         <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    //                                         <span aria-hidden="true">&times;</span>
    //                                         </button>
    //                                      </div>`);
    // } 
    current_form_el.addEventListener("onsubmit", function(e) {
        if(e)
        {
            console.log(e);
        } else {
            console.log('form submited');
        }
    })
}

function validateEmail(current_input_el) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    var input_element = $(current_input_el);
    var email = input_element.val();
    var user_id = input_element[0].dataset.user;

    if(!re.test(String(email).toLowerCase()))
    {
        input_element.addClass('is-invalid');
        input_element[0].setCustomValidity('email is invalid');
        $("#save_changes_uid_" + user_id).prop("disabled", true);

    } else {
        input_element.removeClass('is-invalid');
        input_element.addClass('is-valid');
        input_element[0].setCustomValidity('');
        $("#save_changes_uid_" + user_id).prop("disabled", false);

        const Http_Email = new XMLHttpRequest();
        const url_email = '/api/meetpat-admin/users/unique-email';

        Http_Email.overrideMimeType("application/json");    

        var params = 'email=' + email + '&user_id=' + user_id;
        Http_Email.open('POST', url_email, true);
        Http_Email.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        Http_Email.send(params);
        Http_Email.onload = function() {
            var jsonResponse = JSON.parse(Http_Email.responseText);

            if(jsonResponse['email_used'] == 'true')
            {
                $(".modal-mesage-container_email_taken_" + user_id).css("display", "block");
                input_element.removeClass('is-valid');
                input_element.addClass('is-invalid');
                $("#save_changes_uid_" + user_id).prop("disabled", true);

            } else {
                $(".modal-mesage-container_email_taken_" + user_id).css("display", "none");
                input_element.removeClass('is-invalid');
                input_element.addClass('is-valid');
                $("#save_changes_uid_" + user_id).prop("disabled", false);

            }

            // console.log(jsonResponse);
            
        }

    }

};

function validatePassword(current_input_el) {
    var re = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/
    var input_element = $(current_input_el);
    var password = input_element.val();
    var user_id = input_element[0].dataset.user;
    
    input_element.removeClass('is-valid');
    input_element.removeClass('is-invalid');

    if(!re.test(String(password)) && password != '') {

        input_element.addClass('is-invalid');
        input_element.removeClass('is-valid');
        input_element[0].setCustomValidity('Password is invalid');
        $("#save_changes_uid_" + user_id).prop("disabled", true);

    } else {
        input_element.removeClass('is-invalid');
        input_element.addClass('is-valid');       
        input_element[0].setCustomValidity('');         
        $("#save_changes_uid_" + user_id).prop("disabled", false);

    }

    //console.log(input_element[0].checkValidity());

}

function validateRequired(current_input_el) {
    var input_element = $(current_input_el);
    var user_id = current_input_el.dataset.user;

    if(input_element.val().length === 0)
    {
        input_element.addClass('is-invalid');
        input_element.removeClass('is-valid');
        input_element[0].setCustomValidity('field is required');
        $("#save_changes_uid_" + user_id).prop("disabled", true);


    } else {
        input_element.removeClass('is-invalid');
        input_element.addClass('is-valid');
        input_element[0].setCustomValidity('');
        $("#save_changes_uid_" + user_id).prop("disabled", false);


    }

    //console.log(input_element[0].checkValidity());
}

function generatedPassword(el_id) {

var input_element = $("#PasswordInput_" + el_id);
var user_id = input_element[0].dataset.user;

input_element.removeClass('is-invalid');
input_element.addClass('is-valid');
input_element[0].setCustomValidity('');
$("#save_changes_uid_" + user_id).prop("disabled", false);

}

//function checkEmailAvailablility

// Change active status
function change_status(current_el) {
    $("#loader").css("display", "block");
    $('.message-container').empty();

    const Http_Active = new XMLHttpRequest();
    const url_active = '/api/meetpat-admin/users/active-status-change';

    Http_Active.overrideMimeType("application/json");    

    var user_id = current_el.dataset.user;
    var params = 'user_id=' + user_id;

    Http_Active.open('POST', url_active, true);
    Http_Active.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    Http_Active.send(params);
    Http_Active.onload = function() {
        var jsonResponse = JSON.parse(Http_Active.responseText);

        if(jsonResponse["user_type"] == "client")
        {
            $("i", current_el).removeClass();

            if(jsonResponse['user_was_active'] == 0)
            {
                $("i", current_el).addClass('fas fa-toggle-on');

            } else {
                $("i", current_el).addClass('fas fa-toggle-off');

            }

            $(".message-container").prepend(`<div id="AlertBoxTemp" class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong> The active status of the selected user has been changed
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`);
        } else {
            $(".message-container").prepend(`<div id="AlertBoxTemp" class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> There is an issue with changing the status of the selected users. Please contact support.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`);
        }

    }

    Http_Active.onreadystatechange = function () {
        if(Http_Active.readyState === 4) {
            
            // console.log('request complete');
            $("#loader").css("display", "none");

        }
    };        



};

// Save User Changes
function saveChanges(current_form_el) {
    $("#loader").css("display", "block");
    $('.message-container').empty();

    const Http_Edit = new XMLHttpRequest();
    const url_edit = '/api/meetpat-admin/users/edit';
    
    Http_Edit.overrideMimeType("application/json");  

    var user_id = $("input[name='user_id'] ", current_form_el).val();
    var user_name = $("input[name='user_name'] ", current_form_el).val();
    var user_email = $("input[name='user_email'] ", current_form_el).val();
    var new_password = $("input[name='new_password'] ", current_form_el).val();
    var send_mail = $("input[name='send_mail'] ", current_form_el).is(':checked');
    var params = 'user_id=' + user_id + '&user_name=' + user_name + '&user_email=' + user_email + '&new_password=' + new_password + '&send_mail=' + send_mail;
    var jsonResponse;

    Http_Edit.open("POST", url_edit, true);
    Http_Edit.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    Http_Edit.onload = function() {
        jsonResponse = JSON.parse(Http_Edit.responseText);

    if(jsonResponse["email_valid"] == "true" && jsonResponse["user_name_valid"] == "true")
    {
        if(jsonResponse["password_change"] == "true")
        {
            if(jsonResponse["password_valid"] == "false")
            {
                $("input[name='new_password'] ", current_form_el).addClass('is-invalid');
                $(".modal-mesage-container_" + jsonResponse["users_id"]).css("display", "block");

            } else {
                $('#EditUser_' + user_id).modal('hide');
            }

        } else {
            $('#EditUser_' + user_id).modal('hide');
        }

        if(jsonResponse["sent_mail"] == "true")
        {
            $(".message-container").prepend(`<div id="AlertBoxTemp" class="alert alert-success alert-dismissible fade show" role="alert">
                                                <strong>Success!</strong> Changes to the selected user has been saved and an email has been sent.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>`);
        } else {
            $(".message-container").prepend(`<div id="AlertBoxTemp" class="alert alert-success alert-dismissible fade show" role="alert">
                                                <strong>Success!</strong> Changes to the selected user has been saved.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>`);
        }

     } else {
        
        $(".modal-mesage-container_" + jsonResponse["users_id"]).css("display", "block");
    }
        
        // console.log(jsonResponse);
    };


    Http_Edit.onreadystatechange = function () {
        if(Http_Edit.readyState === 4) {
            
            // console.log('request complete');
            $("#loader").css("display", "none");

        }
    };
    Http_Edit.send(params);
    
}

function deleteUser(current_el) {
    $("#loader").css("display", "block");

    var user_id = current_el.dataset.user;

    const Http_Delete = new XMLHttpRequest();
    const url_delete = '/api/meetpat-admin/users/delete';

    Http_Delete.overrideMimeType("application/json");  
    Http_Delete.open('POST', url_delete, true);
    Http_Delete.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    params = 'user_id=' + user_id;

    Http_Delete.send(params);

    Http_Delete.onload = function() {
    var jsonResponse = JSON.parse(Http_Delete.responseText);

    $('#DeleteUser__' + user_id).modal('hide');
    $('.message-container').empty();

    if(jsonResponse["deleted"] == true)
        {
            $(".message-container").prepend(`<div id="AlertBoxTemp" class="alert alert-success alert-dismissible fade show" role="alert">
                                                <strong>Success!</strong> The selected user has been deleted.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>`);
        } else {
            $(".message-container").prepend(`<div id="AlertBoxTemp" class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <strong>Error!</strong> The selected user could not be deleted.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                                </div>`);
        }

    }

    Http_Delete.onreadystatechange = function () {
        if(Http_Delete.readyState === 4) {
            
            // console.log('request complete');
            // $("#loader").css("display", "none");
            location.reload(true);

        }
    };        
}

// FeatureMethods

function viewPassword(current_el) {

var user_id = current_el.dataset.user;
var password_el = $('#PasswordInput_' + user_id);
var password_icon_el = $('i', '#ViewPassword_' + user_id);

if(password_el.attr('type') == 'password')
{
    password_el.attr({type: 'text'});
    password_icon_el.removeClass();
    password_icon_el.addClass('fas fa-eye-slash');

} else {
    password_el.attr({type: 'password'});
    password_icon_el.removeClass();
    password_icon_el.addClass('fas fa-eye');
}

}

function generatePassword(current_el) {

    var user_id = current_el.dataset.user;

    const Http = new XMLHttpRequest();
    const url = '/api/meetpat-admin/generate-password';
    Http.overrideMimeType("application/json");    
    Http.open("GET", url, true);
    Http.send();
    Http.onload = function() {
        var jsonResponse = JSON.parse(Http.responseText);
        $('#PasswordInput_' + user_id).val(jsonResponse["password"]);
        $('#PasswordInput_' + user_id).removeClass("is-invalid");
        $('#PasswordInput_' + user_id).removeClass("is-valid");
        $('#PasswordInput_' + user_id).addClass("is-valid");
        $("#save_changes_uid_" + user_id).prop("disabled", false);
    }
};

$(document).ready(function() {

    $.get('/api/meetpat-admin/users', function() {

    }).done(function(data) {

        data.forEach(function(obj) { 
            //assigning usable variables for dom elements
            obj.audience_files = "/meetpat-admin/users/files/" + obj.id
            obj.edit = obj.id;
            obj.active = {active: obj.client.active, user_id: obj.id};
            obj.delete = obj.id;
            obj.settings = obj.id;

            if(obj.client_uploads) {
                obj.uploads = {user_id: obj.id, uploads_percentage: get_percentage(obj.client_uploads.upload_limit, obj.client_uploads.uploads), client_upload_limit: obj.client_uploads.upload_limit, client_uploads: obj.client_uploads.uploads};
            } else {
                obj.uploads = {user_id: obj.id, uploads_percentage: 0, client_upload_limit: 0, client_uploads: 0};
            }

            // Post functions for user settings based on table data

            // Reset uploads buttond
            $("#clearUploads__" + obj.id).click(function(data) {
                $("#clearUploads__" + obj.id + ' i').addClass("fa-spin");
                $.post('/api/meetpat-admin/settings/clear-uploads', {user_id: obj.id}, function(data) {
                    // console.log(data);
                }).fail(function(error) {
                    console.log(error);
                }).done(function(data) {
                    if(data.message == 'cleared') {
                        $("#alertSectionSettings__" + obj.id).html(
                            `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success</strong>  &mdash; User uploads have been cleared.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`
                        );

                        $("#userUploadsPercentage__" + obj.id).html("0%");
                        $("#userUploadsPercentage__" + obj.id).attr("aria-valuenow", 0);
                        $("#userUploadsPercentage__" + obj.id).removeAttr("style").css("width", "0%");
                    } else {
                        $("#alertSectionSettings__" + obj.id).html(
                            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong>  &mdash; An error has occured please contact support.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`
                        );
                    }
                    $("#clearUploads__" + obj.id + ' i').removeClass("fa-spin");
                })
            });

            $("#rmvUsrFrmAffRecs__" + obj.id).click(function() {
                $("#rmvUsrFrmAffRecs__" + obj.id + ' i').addClass("eraser");
                $("#rmvUsrFrmAffRecs__" + obj.id).prop("disabled", true);

                $.post('/api/meetpat-admin/settings/remove-affiliate', {'user_id': obj.id}, function(data) {
                    // console.log(data);
                }).fail(function(error) {
                    $("#rmvUsrFrmAffRecs__" + obj.id + ' i').removeClass("eraser");
                    $("#rmvUsrFrmAffRecs__" + obj.id).prop("disabled", false);
                    console.log(error);
                }).done(function(data) {
                    if(data.message == 'success') {
                        $("#alertSectionSettings__" + obj.id).html(
                            `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success</strong>  &mdash; User has been removed from affiliated records.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`
                        );
                    } else {
                        $("#alertSectionSettings__" + obj.id).html(
                            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong>  &mdash; An error has occured please contact support.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`
                        );
                    }
                    $("#rmvUsrFrmAffRecs__" + obj.id + ' i').removeClass("eraser");
                    $("#rmvUsrFrmAffRecs__" + obj.id).prop("disabled", false);
                });
            });

            $("#updateClientUploadLimit__" + obj.id).click(function() {
                var parent = this;
                $(this).prop('disabled', true);
                $(this).html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                       Saving...`
                );
                if($('#newUploadLimit__' + obj.id).val() >= obj.client_uploads.uploads) {
                    $('#newUploadLimit__' + obj.id).removeClass('is-invalid');
                    $.post('/api/meetpat-admin/settings/updated-upload-limit', {user_id: obj.id, new_upload_limit: $('#newUploadLimit__' + obj.id).val()}, function(data) {
                        // console.log(data);
                    }).fail(function(error) {
                        console.log(error);
                        $(parent).prop('disabled', false);
                        $(parent).html("Save");
                        $("#alertSectionSettings__" + obj.id).html(
                            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong>  &mdash; An error has occured please contact support.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`
                        );
                    }).done(function(data) {
                        $(parent).prop('disabled', false);
                        $(parent).html("Save");
    
                        if(data.status == 'success')
                        {
                            $("#alertSectionSettings__" + obj.id).html(
                                `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success!</strong>  &mdash; User upload limit has been updated.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>`
                            );
    
                            $("#userUploadsPercentage__" + obj.id).html(obj.uploads.client_uploads + "/" + $('#newUploadLimit__' + obj.id).val());
                            $("#userUploadsPercentage__" + obj.id).attr("aria-valuenow", get_percentage($('#newUploadLimit__' + obj.id).val(), obj.uploads.client_uploads));
                            $("#userUploadsPercentage__" + obj.id).removeAttr("style").css("width", get_percentage($('#newUploadLimit__' + obj.id).val(), obj.uploads.client_uploads) + "%");
                        } else {
                            $("#alertSectionSettings__" + obj.id).html(
                                `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong>  &mdash; An error has occured please contact support.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>`
                            );
                        }
                    });
                } else {
                    $(parent).prop('disabled', false);
                    $(parent).html("Save");

                    $('#newUploadLimit__' + obj.id).addClass('is-invalid');
                   
                }

                
            });

            $("#updateClientCreditLimit__" + obj.id).click(function() {
                var parent = this;
                $(this).prop('disabled', true);
                $(this).html(
                    `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                       Saving...`
                );

                $.post('/api/meetpat-admin/settings/updated-credit-limit', {user_id: obj.id, new_credit_limit: $('#newCreditLimit__' + obj.id).val()}, function(data) {
                    // console.log(data);
                }).fail(function(error) {
                    $(parent).prop('disabled', false);
                    $(parent).html("Save");
                    $("#alertSectionSettings__" + obj.id).html(
                        `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>  &mdash; An error has occured please contact support.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>`
                    );
                    console.log(error);
                }).done(function(data) {
                    $(parent).prop('disabled', false);
                    $(parent).html("Save");

                    if(data.status == 'success')
                    {
                        $("#alertSectionSettings__" + obj.id).html(
                            `<div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success!</strong>  &mdash; User credit limit has been updated.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`
                        );

                        $("#useCreditPercentage__" + obj.id).html(obj.similar_audience_credits.used_credits + "/" + $('#newCreditLimit__' + obj.id).val());
                        $("#useCreditPercentage__" + obj.id).attr("aria-valuenow", get_percentage($('#newCreditLimit__' + obj.id).val(), obj.similar_audience_credits.used_credits));
                        $("#useCreditPercentage__" + obj.id).removeAttr("style").css("width", get_percentage($('#newCreditLimit__' + obj.id).val(), obj.similar_audience_credits.used_credits) + "%");

                    } else {
                        $("#alertSectionSettings__" + obj.id).html(
                            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong>  &mdash; An error has occured please contact support.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>`
                        );
                    }
                })
            });

        });
        var tabledata = data;

        var table = new Tabulator("#users-table", {
            data:tabledata,
            layout:"fitDataFill",
            pagination:"local",
            paginationSize:12,
            columns: [
                {title:"ID", field:"id"},
                {title:"Name", field:"name"},
                {title: "Email", field:"email"},
                {title: "Audience Files", field: "audience_files", "align": "center", formatter:function(cell, formatterParams) {
                    var value = cell.getValue();
                    return "<a href='"+ cell.getValue() + "'><i class='fas fa-folder action-link'></i></a>";
                }},
                {title: "Edit", field: "edit", formatter:function(cell, formatterParams) {
                    var value = cell.getValue();
                    return '<button class="edit-tooltip table_button" data-toggle="modal" data-target="#EditUser_'+ cell.getValue() +'" data-toggle="tooltip" data-html="true" title="<em>edit</em>"><i class="fas fa-pen-alt action-link"></i></button>';
                }},
                {title: "Active", field: "active", formatter:function(cell, formatterParams) {
                    var value = cell.getValue();
                    if(cell.getValue().active) {
                        return '<button id="ActiveStatusBtn_'+cell.getValue().user_id+'" onclick="change_status(this)" type="submit" class="active-tooltip table_button ActiveStatusBtn" data-toggle="tooltip" data-html="true" title="<em>status</em>" data-user="'+cell.getValue().user_id+'"><i class="fas fa-toggle-on action-link"></i></button>'
                    } else {
                        return '<button id="ActiveStatusBtn_'+cell.getValue().user_id+'" onclick="change_status(this)" type="submit" class="active-tooltip table_button ActiveStatusBtn" data-toggle="tooltip" data-html="true" title="<em>status</em>" data-user="'+cell.getValue().user_id+'"><i class="fas fa-toggle-off action-link"></i></button>'
                    }
                    
                }},
                {title: "Delete", field: "delete", formatter:function(cell, formatterParams) {
                    var value = cell.getValue();
                    return `<button class="delete-tooltip table_button" data-toggle="modal" data-target="#DeleteUser__${value}" data-toggle="tooltip" data-html="true" title="<em>delete</em>">
                                <i class="far fa-trash-alt action-link"></i>
                            </button>`;
                }},
                {title: "Settings", field: "settings", formatter:function(cell, formatterParams) {
                    var value = cell.getValue();
                    return `<button class="settings-tooltip table_button" data-toggle="modal" data-target="#SettingsUser__${value}" data-toggle="tooltip" data-html="true" title="settings">
                                <i class="fas fa-cogs action-link"></i>
                            </button>`
                }}
            ]
        });

        // console.log(tabledata);

    }).fail(function(error) {
        console.log(error);

    });

});