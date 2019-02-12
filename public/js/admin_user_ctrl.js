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

            console.log(jsonResponse);
            
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
            
            console.log('request complete');
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
        
        console.log(jsonResponse);
    };


    Http_Edit.onreadystatechange = function () {
        if(Http_Edit.readyState === 4) {
            
            console.log('request complete');
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
            
            console.log('request complete');
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