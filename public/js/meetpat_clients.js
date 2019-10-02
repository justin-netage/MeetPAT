var auth_token = $("#ApiToken").val();

// Methods
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Feature Methods

var checkFormValidity = function() {

    var invalid_inputs = [];
    $("#clientEditForm :input").each(function() {
        if(!this.checkValidity()) {
            invalid_inputs.push(this);
        }
    });

    if(invalid_inputs.length) {
        $("#saveEditClient").addClass("disabled");
        $("#saveEditClient").prop("disabled", 1);
    } else {
        $("#saveEditClient").removeClass("disabled");
        $("#saveEditClient").prop("disabled", 0);
    }

}

function viewPassword() {
    var password_el = $('#PasswordInput');
    var password_icon_el = $('i', '#ViewPassword');
    
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

function generatePassword() {
    const Http = new XMLHttpRequest();
    const url = '/api/meetpat-admin/generate-password';
    Http.overrideMimeType("application/json");    
    Http.open("GET", url, true);
    Http.send();
    Http.onload = function() {
        var jsonResponse = JSON.parse(Http.responseText);
        $('#PasswordInput').val(jsonResponse["password"]);
        $('#PasswordInput').removeClass("is-invalid");
        $('#PasswordInput').removeClass("is-valid");
        $('#PasswordInput').addClass("is-valid");
        document.getElementById("PasswordInput").setCustomValidity("");

        checkFormValidity();
    }
    
}

// Change client active status.
var set_status = function(status) {
    var users_id = status.getAttribute("data-user-id");
    
    $(".spinner-border", status).removeClass("d-none");
    $.ajax({
        url: '/api/meetpat-admin/users/active-status-change',
        data: {user_id: users_id, api_token: auth_token},
        method: "POST",
        success: function(data) {
            
            $(".spinner-border", status).addClass("d-none");
            if(data.user_was_active) {
                
                $( status).removeClass("fa-toggle-on");
                $(status).addClass("fa-toggle-off");
                
            } else {
                
                $(status).removeClass("fa-toggle-off");
                $(status).addClass("fa-toggle-on");
            }
        
        },
        error: function(error) {
            $("#mainAlertSection").html(`
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error</strong> - An error has occured while attempting to update the current users status. Please contact support if the problem persists.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            `)
            $(".spinner-border", status).addClass("d-none");    
            $("i", status).show();
            //console.log(error);
        }
    });
}

// Open edit client modal
var open_edit = function(client) {
    var users_id = client.getAttribute("data-user-id");

    $("#modalsContainer").html(`
        <div class="modal" tabindex="-1" id="editModal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Client Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBody">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">&nbsp;Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="saveEditClient" type="button" class="btn btn-primary disabled"><strong>Save Changes</strong></button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Close</strong></button>
                </div>
                </div>
            </div>
        </div>
    `);

    $("#saveEditClient").prop("disabled", 1);
    $("#editModal").modal("show");

    var xhr_edit_users = $.ajax({
        url: '/api/meetpat-admin/client/get',
        data: {api_token: auth_token, user_id: users_id},
        method: 'GET',
        success: function(data) {
            
            $("#modalBody").html(`
            <form id="clientEditForm" autocomplete="off" onsubmit="return false;" novalidate="">
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning</strong> — These actions can not be undone.
                        </div>
                    </div>
                    <div class="col-12" id="alertSectionDetails"></div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="clientName">User Name</label>
                            <input id="clientName" name="clientName" class="form-control is-valid" type="text" value="${data.client.name}" autofocus/>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="clientEmail">Email Address</label>
                            <input id="clientEmail" autocomplete="email" name="clientEmail" class="form-control is-valid" type="email" value="${data.client.email}" />
                        </div>
                        <div class="form-group d-flex justify-content-end">
                            <button class="btn ChangePasswordBtn PasswordInput collapsed btn-warning" type="button" id="ChangePasswordBtn" data-toggle="collapse" data-target=".collapsePasswordChange" aria-expanded="false" aria-controls="collapseExample" value="">Change Password</button>
                        </div>
                        <div id="collapsePasswordChange" class="collapsePasswordChange collapse" style="">
                            <div class="card card-body">
                                <div class="form-group">
                                    <label for="new-password">New Password</label>
                                    <div class="input-group">
                                        <input type="password" autocomplete="new-password" id="PasswordInput" data-user="1" name="new_password" class="form-control PasswordInput">
                
                                        <div class="input-group-append">
                                            <button id="GeneratePassword" onclick="generatePassword(this)" class="btn btn-outline-secondary view-password generate-password GeneratePassword" data-user="1" onclick="generatePassword(this)" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="generate password"><i class="fas fa-random"></i></button>
                                        </div>
                                        <div class="input-group-append">
                                            <button id="ViewPassword" onclick="viewPassword(this)" class="btn btn-outline-secondary view-password ViewPassword" onclick="viewPassword(this);" data-user="1" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="view password"><i class="fas fa-eye"></i></button>
                                        </div>
                                        <div class="invalid-feedback">
                                        This field is required.
                                    </div>
                                    </div>
                                    <small id="passwordHelpBlock" class="form-text text-muted">
                                        Your password must be 8-20 characters long, contain letters, at least one number and symbol.
                                    </small>
                    
                                </div>
                                
                                <div class="form-group mt-3">
                                    <label class="container-label">Send password to user email address
                                    <input id="sendMail" type="checkbox" name="send_mail">
                                    <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            `);

            $('#clientName').on('keyup change', function() {

                if($(this).val().length < 2 || !$(this).val().match(/^[a-zA-Z0-9]([_a-zA-Z0-9-& ])*[a-zA-Z0-9]$/g)) {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                    this.setCustomValidity("invalid"); 
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                    this.setCustomValidity("");
                }

                checkFormValidity();
            });
            $('#clientEmail').on('keyup change', function() {

                if($(this).val().length < 2 || !$(this).val().match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/g)) {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                    this.setCustomValidity("invalid");
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                    this.setCustomValidity("");
                }

                checkFormValidity();
            });

            $('#collapsePasswordChange').on('hidden.bs.collapse', function () {
                $('#ChangePasswordBtn').html("Change Password");
                $('#ChangePasswordBtn').removeClass("btn-danger");
                $('#ChangePasswordBtn').addClass("btn-warning");

                $("#PasswordInput").removeClass('is-valid');
                $("#PasswordInput").removeClass('is-invalid');
                $("#PasswordInput").val("");
                document.getElementById("PasswordInput").setCustomValidity("");

                $("#sendMail").prop("checked", 0);

                checkFormValidity();
            });

            $('#collapsePasswordChange').on('show.bs.collapse', function () {
                $('#ChangePasswordBtn').html("Cancel");
                $('#ChangePasswordBtn').removeClass("btn-warning");
                $('#ChangePasswordBtn').addClass("btn-danger");
                document.getElementById("PasswordInput").setCustomValidity("invalid");
                checkFormValidity();
                $("#PasswordInput").on('keyup change', function() {
                    if(!$(this).val().match(/^(?=.*[A-Za-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/)) {
                        $(this).removeClass('is-valid');
                        $(this).addClass('is-invalid');
                        this.setCustomValidity("invalid");
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).addClass('is-valid');
                        this.setCustomValidity("");
                    }

                    checkFormValidity();
                });
            
            });
            
            checkFormValidity();
            $("#saveEditClient").removeClass("disabled");
            $("#saveEditClient").prop("disabled", 0);
        }, 
        error: function(error) {
            //console.log(error);
            $("#modalBody").html(`
                <div class="alert alert-danger" role="alert"><strong>Error</strong> - An error occured getting user data. Reload the current page or contact support for assistance.</div>
            `);

        }
    });

    $("#saveEditClient").click(function() {

        $(this).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <strong>&nbsp;Saving...</strong>
            `);

        $(this).prop("disabled", 1);
        var user_name = $("input[name='clientName']").val();
        var user_email = $("input[name='clientEmail']").val();
        var new_password = $("input[name='new_password']").val();
        var send_mail = $("input[name='send_mail']").is(':checked');

        var xhr_save_details = $.ajax({
            url: '/api/meetpat-admin/users/edit',
            method: 'POST',
            data: {user_id: users_id, user_name: user_name, user_email: user_email, new_password: new_password, send_mail: send_mail},
            success: function(data) {

                if(data.sent_mail == "true") {
                    $("#alertSectionDetails").html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success</strong> - The current users details have been updated. An email with the new password has been sent to <strong>${user_email}</strong>.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                } else {
                    $("#alertSectionDetails").html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success</strong> - The current users details have been updated.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `);

                }
                
                $("#saveEditClient").html("<strong>Save Changes</strong>");
                $("#saveEditClient").prop("disabled", 0);
                
                //console.log(data);
            },
            error: function(error) {
                $("#alertSectionDetails").html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error</strong> - An error has occured while trying to update the current users details. Please contact support if the problem persists.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `)
                $("#saveEditClient").html("<strong>Save Changes</strong>");
                $("#saveEditClient").prop("disabled", 0);
                //console.log(error);
            }
        });

        $('#editModal').on('hidden.bs.modal', function () {
            xhr_save_details.abort();
            $("#modalsContainer").empty();
        });

    });

    $('#editModal').on('hidden.bs.modal', function () {
        xhr_edit_users.abort();
        $("#modalsContainer").empty();
    });
   
}

var open_settings = function(client) {
    var users_id = client.getAttribute("data-user-id");

    $("#modalsContainer").html(`
        <div class="modal" tabindex="-1" id="settingsModal" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Client Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBody">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><strong>Close</strong></button>
                </div>
                </div>
            </div>
        </div>
    `);

    $("#saveSettingsClient").prop("disabled", 1);
    $("#settingsModal").modal("show");

    var xhr_settings_user = $.ajax({
        url: '/api/meetpat-admin/client/get',
        data: {api_token: auth_token, user_id: users_id},
        method: 'GET',
        success: function(data) {
            //console.log(data);

            $("#modalBody").html(`
                <form id="clientSettingsForm" onsubmit="return false;" novalidate="">
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                <strong>Warning</strong> — These actions can not be undone.
                            </div>
                        </div>
                        <div class="col-12" id="alertSectionSettings"></div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-label" for="clientUploadLimit">Upload Limit</label>
                                <div class="progress">
                                    <div class="progress-bar" id="userUploadsPercentage" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0/0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-8 col-sm-6 col-xs-6">
                            <input id="newUploadLimit" type="number" min="0" max="10000000" class="form-control" name="new_upload_limit" value="0">
                            <div class="invalid-feedback">
                                A new limit cant be less than the current users uploads.
                            </div>
                        </div>
                        <div class="col-6 col-md-4 col-sm-6 col-xs-6">
                            <button id="saveNewUploadLimit" class="btn btn-primary btn-block float-right"><strong>Save</strong></button>
                        </div>
                    </div>
                </form>
                <div class="row mt-3">
                    <div class="col-12">
                        <button id="resetUploads" class="btn btn-warning btn-block"><strong>Reset Uploads</strong></button>
                    </div>
                </div>

            `);

            $("#userUploadsPercentage").width(((data.client.client_uploads.uploads/data.client.client_uploads.upload_limit)*100) + "%");
            $("#userUploadsPercentage").html(data.client.client_uploads.uploads + "/" + data.client.client_uploads.upload_limit);
            $("#newUploadLimit").val(data.client.client_uploads.upload_limit);
            $("#newUploadLimit").attr({"min": data.client.client_uploads.uploads});
            $("#newUploadLimit").on('change keyup', function() {
                if($(this).val() < data.client.client_uploads.uploads) {
                    $(this).removeClass('is-valid');
                    $(this).addClass('is-invalid');
                    $("#saveNewUploadLimit").prop("disabled", 1);
                } else {
                    $(this).addClass('is-valid');
                    $(this).removeClass('is-invalid');
                    $("#saveNewUploadLimit").prop("disabled", 0);
                }
            });           

            $("#saveNewUploadLimit").click(function() {

                var confirmed = confirm("Are you sure that you want to change this users upload limit to " + numberWithCommas($("#newUploadLimit").val()) + "?");

                if(confirmed == true) {
                    $(this).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><strong>&nbsp;Saving...</strong>`);
                    $(this).prop("disabled", 1);
                    $("#resetUploads").prop("disabled", 1);
    
                    var xhr_update_limit = $.ajax({
                        url: '/api/meetpat-admin/settings/updated-upload-limit',
                        method: 'POST',
                        data: {api_token: auth_token, user_id: users_id, new_upload_limit: $("#newUploadLimit").val()},
                        success: function(data) {

                            $("#userUploadsPercentage").width(((data.client_uploads.uploads/$("#newUploadLimit").val())*100) + "%");
                            $("#userUploadsPercentage").html(data.client_uploads.uploads + "/" + $("#newUploadLimit").val());

                            $("#saveNewUploadLimit").html("<strong>Save</strong>");
                            $("#saveNewUploadLimit").prop("disabled", 0);
                            $("#resetUploads").prop("disabled", 0);

                            $("#newUploadLimit").attr({"min": data.client_uploads.uploads});
                            $("#newUploadLimit").on('change keyup', function() {
                                if($(this).val() < data.client_uploads.uploads) {
                                    $(this).removeClass('is-valid');
                                    $(this).addClass('is-invalid');
                                    $("#saveNewUploadLimit").prop("disabled", 1);
                                } else {
                                    $(this).addClass('is-valid');
                                    $(this).removeClass('is-invalid');
                                    $("#saveNewUploadLimit").prop("disabled", 0);
                                }
                            });

                            //console.log(data)
                        },
                        error: function(error) {
                            
                            $("#alertSectionSettings").html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error</strong> - An error has occured while trying to update the current users limits. Please contact support if the problem persists.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `)
                            $("#saveNewUploadLimit").prop("disabled", 0);
                            $("#resetUploads").prop("disabled", 0);

                            //console.log(error)
                        }
                    });
                    $('#settingsModal').on('hidden.bs.modal', function () {
                        xhr_update_limit.abort();
                        $("#modalsContainer").empty();
                    });
                }
                
            });

            $("#resetUploads").click(function() {
                var confirmed = confirm("Are you sure that you want to set this users uploads back to 0?");

                if(confirmed == true) {
                    $("#saveNewUploadLimit").prop("disabled", 1);
                    $(this).prop("disabled", 1);

                    $(this).html(`
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            <strong>&nbsp;Resetting Uploads...</strong>`);

                    var xhr_reset_uploads = $.ajax({
                        url: '/api/meetpat-admin/settings/clear-uploads',
                        method: 'POST',
                        data: {api_token: auth_token, user_id: users_id},
                        success: function(data) {

                            $("#alertSectionSettings").html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success</strong> - User limits have been updated.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `);

                            $("#userUploadsPercentage").width("0%");
                            $("#userUploadsPercentage").html("0/" + $("#newUploadLimit").val());

                            $("#saveNewUploadLimit").prop("disabled", 0);
                            $("#resetUploads").prop("disabled", 0);

                            $("#resetUploads").html("<strong>Reset Uploads</strong>");

                            //console.log(data);
                        },
                        error: function(error) {
                            $("#alertSectionSettings").html(`
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error</strong> - An error has occured while trying to update the current users limits. Please contact support if the problem persists.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `);
                            $("#saveNewUploadLimit").prop("disabled", 1);
                            $("#saveNewUploadLimit").prop("disabled", 1);

                            $("#saveNewUploadLimit").html("Reset Uploads");

                            //console.log(error);
                        }
                    });

                    $('#settingsModal').on('hidden.bs.modal', function () {
                        xhr_reset_uploads.abort();
                        $("#modalsContainer").empty();
                    });
                }

                
            });

        },
        error: function(error) {
            //console.log(error);
            $("#modalBody").html(`
                <div class="alert alert-danger" role="alert"><strong>Error</strong> - An error occured getting user data. Reload the current page or contact support for assistance.</div>
            `);
        }
    });

    $('#settingsModal').on('hidden.bs.modal', function () {
        xhr_settings_user.abort();
        $("#modalsContainer").empty();
    });
}

$(document).ready(function() {
    
    $('#clients_table').DataTable({
        responsive: true
    });
    
})

    
    