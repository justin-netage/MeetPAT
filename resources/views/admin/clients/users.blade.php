@extends('layouts.app')
@section('styles')
<link href="{{asset('bower_components/tabulator/dist/css/semantic-ui/tabulator_semantic-ui.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<!-- <div id="users"></div> -->
<div id="loader"></div>
<div class="message-container"></div>
<div class="container">
    <div class="row">
        <div class="col-12">
          <div id="users-table">
            <div class="d-flex justify-content-center">
              <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
              </div>
            </div>
          </div>
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
<!-- Modals Settings -->
@foreach($users as $key => $user_setting)
<div class="modal fade" id="SettingsUser__{{$user_setting->id}}" tabindex="-1" role="dialog" aria-labelledby="SettingsModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">User Settings</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="alert alert-warning" role="alert">
                <strong>Warning</strong> &mdash; These actions can not be undone.
              </div>
            </div>
            <div class="col-12" id="alertSectionSettings__{{$user_setting->id}}"></div>
          </div>
          <div class="row">
            <label for="clearUploads" class="col-sm-8 col-form-label">User Uploads</label>
            <div class="col-sm-4">
              @if($user_setting->client_uploads and $user_setting->client_uploads->uploads)
              <button class="btn btn-warning float-right" id="clearUploads__{{$user_setting->id}}" value="Reset">
                <i class="fas fa-undo"></i>&nbsp;Reset
              </button>
              @else
              <button class="btn btn-warning float-right" disabled="disabled" id="clearUploads__{{$user_setting->id}}">
                <i class="fas fa-undo"></i>&nbsp;Reset
              </button>
              @endif
            </div>
          </div>
          <div class="row pt-2">
            <label for="rmvUsrFrmAffRecs" class="col-sm-8 col-form-label">Remove User From Affiliated Records</label>
            <div class="col-sm-4">
              <button class="btn btn-warning float-right" id="rmvUsrFrmAffRecs__{{$user_setting->id}}">
                <i class="fas fa-eraser"></i>&nbsp;&nbsp;remove
              </button>
            </div>
          </div>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>
@endforeach
<!-- End Modals Settings -->
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('bower_components/tabulator/dist/js/tabulator.min.js')}}" defer></script>
<script type="text/javascript" src="{{ asset('js/admin_user_ctrl.js') }}" defer></script>
@endsection

