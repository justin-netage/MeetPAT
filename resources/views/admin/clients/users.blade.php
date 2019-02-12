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
                        <th scope="col">Audience Files</th>
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
                                <a href="/meetpat-admin/users/files/{{$user->id}}"><i class="fas fa-folder"></i></a>
                            </td>
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
<script type="text/javascript" src="{{ asset('js/admin_user_ctrl.js') }}" defer></script>
@endsection

