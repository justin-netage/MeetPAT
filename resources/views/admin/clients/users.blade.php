@extends('layouts.app')

@section('content')
<!-- <div id="users"></div> -->
<div id="loader"></div>
<div class="message-container"></div>
<div class="container">
    <div class="row">
        <div class="col-12">
            <table class="table table-responsive-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">name</th>
                        <th scope="col">email</th>
                        <th scope="col">edit</th>
                        <th scope="col">active</th>
                        <th scope="col">delete</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        @if($user->client)
                        <tr>
                            <td>{{$key}}</td>
                            <td>{{$user->name}}</td> 
                            <td><a href="mailto:{{$user->email}}?Subject=MeetPat%20">{{$user->email}}</td>
                            <td>
                                <button class="edit-tooltip table_button" data-toggle="modal" data-target="#EditUser_{{$user->id}}" data-toggle="tooltip" data-html="true" title="<em>edit</em>"><i class="fas fa-pen-alt action-link"></i></button>
                            </td>
                            <td>
                                <button id="ActiveStatusBtn_{{$user->id}}" onclick="change_status(this)" type="submit" class="active-tooltip table_button ActiveStatusBtn" data-toggle="tooltip" data-html="true" title="<em>status</em>" data-user="{{$user->id}}">
                                @if($user->client)
                                    @if($user->client->active)
                                    <i class="fas fa-toggle-on"></i>
                                    @else
                                    <i class="fas fa-toggle-off"></i>
                                    @endif
                                @endif
                                </button>
                            </td>
                            <td>
                                <button class="delete-tooltip table_button" data-toggle="modal" data-target="#DeleteUser__{{$user->id}}" data-toggle="tooltip" data-html="true" title="<em>delete</em>">
                                    <i class="far fa-trash-alt action-link"></i>
                                </button>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('modals')

<!-- Modals  Edit -->
@foreach($users as $key => $user_edit)
<div class="modal fade" id="EditUser_{{$user_edit->id}}" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Edit User</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form onsubmit="saveChanges(this); checkForm(this); return false;" novalidate>
      <div class="modal-body">
          <div class="modal-mesage-container_{{$user_edit->id}}" style="display: none;">
          <div id="AlertBoxTemp" class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> Please fix validation errors.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
          </div>
          </div>
          <div class="modal-mesage-container_email_taken_{{$user_edit->id}}" style="display: none;">
          <div id="AlertBoxTemp" class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> The email presented has already been taken by another user.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
          </div>
          </div>
          <input type="hidden" name="user_id" value="{{$user_edit->id}}">
          <div class="form-group">
            <label for="user-name" class="form-label">Name</label>
            <input type="text" name="user_name" class="form-control is-valid" id="user-name_{{$user_edit->id}}" data-user="{{$user_edit->id}}" onkeyup="validateRequired(this);" value="{{$user_edit->name}}">
            <div class="invalid-feedback">
                This field is required.
            </div>
          </div>
          <div class="form-group">
            <label for="user-email" class="form-label">Email</label>
            <input type="text" name="user_email" class="form-control is-valid" id="user-email_{{$user_edit->id}}" data-user="{{$user_edit->id}}" onkeyup="validateEmail(this);" onfocusout="validateEmail(this);" value="{{$user_edit->email}}">
            <div class="invalid-feedback">
                Please choose a valid email address.
            </div>
          </div>
          <div class="form-group d-flex justify-content-end">
            <button class="btn btn-warning ChangePasswordBtn" type="button" id="ChangePasswordBtn_{{$user_edit->id}}" type="button" data-toggle="collapse" data-target=".collapsePasswordChange" aria-expanded="false" aria-controls="collapseExample">
                Change Password
            </button>
          </div>
          <div class="collapse collapsePasswordChange">
            <div class="card card-body">
                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <div class="input-group">
                        <input type="password" id="PasswordInput_{{$user_edit->id}}" data-user="{{$user_edit->id}}" onkeyup="validatePassword(this);" data-user="{{$user_edit->id}}" name="new_password" class="form-control PasswordInput">

                        <div class="input-group-append">
                            <button id="GeneratePassword_{{$user_edit->id}}" class="btn btn-outline-secondary view-password generate-password GeneratePassword" data-user="{{$user_edit->id}}" onclick="generatePassword(this)" type="button" data-toggle="tooltip" data-placement="top" title="generate password"><i class="fas fa-random"></i></button>
                        </div>
                        <div class="input-group-append">
                            <button id="ViewPassword_{{$user_edit->id}}" class="btn btn-outline-secondary view-password ViewPassword" onclick="viewPassword(this);" data-user="{{$user_edit->id}}" type="button" data-toggle="tooltip" data-placement="top" title="view password"><i class="fas fa-eye"></i></button>
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
                    <input type="checkbox" name="send_mail">
                    <span class="checkmark"></span>
                    </label>
                </div>
            </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" id="save_changes_uid_{{$user_edit->id}}" class="btn btn-primary">Save Changes</button>
      </div>
      </form>

    </div>
  </div>
</div>
@endforeach
<!-- End Modals Edit -->

<!-- Modals Delete -->
@foreach($users as $key => $user_delete)
<div class="modal fade" id="DeleteUser__{{$user_delete->id}}" tabindex="-1" role="dialog" aria-labelledby="EditModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">Delete User</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger" role="alert">
            <p>Are you sure that you want to delete the user</p> 
            <p class="warning-text-bolder"> {{$user_delete->email}} ?</p>
        </div>
      </div>
      <div class="modal-footer">
        <form name="delete_form" id="DeleteFrom_{{$key}}" data-user="{{$user_delete->id}}" onsubmit="deleteUser(this); return false;">
            <input type="hidden" name="user_id" value="{{$user_delete->id}}">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Yes</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endforeach
<!-- End Modals Delete -->

@endsection

@section('scripts')
<script type="text/javascript">

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
                    $("#loader").css("display", "none");

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

</script>
@endsection

