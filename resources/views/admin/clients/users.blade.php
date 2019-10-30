<?php 
    function get_percentage($total, $number)
    {
      if ( $total > 0 ) {
       return round($number / ($total / 100),2);
      } else {
        return 0;
      }
    }
?>

@extends('layouts.app')
@section('styles')
<link href="{{asset('bower_components/tabulator/dist/css/semantic-ui/tabulator_semantic-ui.min.css')}}" rel="stylesheet">
@endsection
@section('content')
<!-- <div id="users"></div> -->
<div class="message-container"></div>
<div class="container">
  @if(\MeetPAT\ThirdPartyService::find(1)->status == 'offline')
  <div class="row justify-content-center">
      <div class="col-md-8">
          <div class="alert alert-warning" role="alert">
              <p><i class="fas fa-exclamation-triangle"></i> BSA's SFTP Server is currently offline.</p>
          </div>
      </div>
  </div>
  @endif
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
          <label for="AdjustUploadCredits"><strong>Upload Limit</strong></label>
          @if($user_setting->client_uploads)
          <div class="progress">
              <div class="progress-bar" id="userUploadsPercentage__{{$user_setting->id}}" role="progressbar" style="width: {{get_percentage($user_setting->client_uploads->upload_limit, $user_setting->client_uploads->uploads)}}%;" aria-valuenow="{{get_percentage($user_setting->client_uploads->upload_limit, $user_setting->client_uploads->uploads)}}" aria-valuemin="0" aria-valuemax="100">{{$user_setting->client_uploads->uploads}}/{{$user_setting->client_uploads->upload_limit}}</div>
          </div>
          @else
          <div class="progress">
              <div class="progress-bar" id="userUploadsPercentage__{{$user_setting->id}}" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0/0</div>
          </div>
          @endif
          <div class="row pb-2 pt-2">
            <div class="col-sm-8">
              @if($user_setting->client_uploads)
              <input type="number" class="form-control" id="newUploadLimit__{{$user_setting->id}}" name="new_upload_limit__{{$user_setting->id}}" value="{{$user_setting->client_uploads->upload_limit}}">
              <div class="invalid-feedback">
                New limits must be more than current uploads.
              </div>
              @else
              <input type="number" class="form-control" id="newUploadLimit__{{$user_setting->id}}" value="0">
              @endif
            </div>
            <div class="col-sm-4">
              <button class="btn btn-primary btn-block float-right" id="updateClientUploadLimit__{{$user_setting->id}}" type="button">Save</button>
            </div>
          </div>
          <label for="AdjustUploadCredits"><strong>Potential Audience Credit Limit</strong></label>
          @if($user_setting->similar_audience_credits)
          <div class="progress">
              <div class="progress-bar" id="useCreditPercentage__{{$user_setting->id}}" role="progressbar" style="width: {{get_percentage($user_setting->similar_audience_credits->credit_limit, $user_setting->similar_audience_credits->used_credits)}}%;" aria-valuenow="{{get_percentage($user_setting->similar_audience_credits->credit_limit, $user_setting->similar_audience_credits->used_credits)}}" aria-valuemin="0" aria-valuemax="100">{{$user_setting->similar_audience_credits->used_credits}}/{{$user_setting->similar_audience_credits->credit_limit}}</div>
          </div>
          @else
          <div class="progress">
              <div class="progress-bar" id="useCreditPercentage__{{$user_setting->id}}" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0/0</div>
          </div>
          @endif
          <div class="row pb-2 pt-2">
            <div class="col-sm-8">
              @if($user_setting->similar_audience_credits)
              <input type="number" class="form-control" id="newCreditLimit__{{$user_setting->id}}" name="new_credit_limit__{{$user_setting->id}}" value="{{$user_setting->similar_audience_credits->credit_limit}}">
              @else
              <input type="number" class="form-control" id="newCreditLimit__{{$user_setting->id}}" value="0">
              @endif
            </div>
            <div class="col-sm-4">
              <button class="btn btn-primary btn-block float-right" id="updateClientCreditLimit__{{$user_setting->id}}" type="button">Save</button>
            </div>
          </div>
          <hr />
          <div class="row pt-2">
            <label for="clearUploads" class="col-sm-8 col-form-label">User Uploads</label>
            <div class="col-sm-4">
              @if($user_setting->client_uploads and $user_setting->client_uploads->uploads)
              <button class="btn btn-warning btn-block float-right" id="clearUploads__{{$user_setting->id}}" value="Reset">
                <i class="fas fa-undo"></i>&nbsp;Reset
              </button>
              @else
              <button class="btn btn-warning btn-block float-right" disabled="disabled" id="clearUploads__{{$user_setting->id}}">
                <i class="fas fa-undo"></i>&nbsp;Reset
              </button>
              @endif
            </div>
          </div>
          <div class="row pt-2">
            <label for="rmvUsrFrmAffRecs" class="col-sm-8 col-form-label">Remove User From Affiliated Records</label>
            <div class="col-sm-4">
              <button class="btn btn-warning btn-block float-right" id="rmvUsrFrmAffRecs__{{$user_setting->id}}">
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

<!-- Modals Company Information -->

@foreach($users as $company_info)

<div class="modal fade" id="CompanyDetails__{{$company_info->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Company Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <h5>Personal Information</h5>
      <hr>
      <form id="saveChangesForm">
      <input type="hidden" name="user_id__{{$company_info->id}}" value="{{$company_info->id}}">
      <div class="form-row mb-2">
          <div class="col">
              <label for="clientFirstName">First Name</label>
              @if($company_info->client_details)
              <input type="text" class="form-control" name="client_first_name__{{$company_info->id}}" id="clientFirstName__{{$company_info->id}}" placeholder="First Name" value="{{$company_info->client_details->client_first_name}}" readonly>

              @else
              <input type="text" class="form-control" name="client_first_name__{{$company_info->id}}" id="clientFirstName__{{$company_info->id}}" placeholder="First Name" value="" readonly>
              @endif
                  <div class="invalid-feedback">
                      Please enter first name.
                  </div>    
          </div>
          <div class="col">
              <label for="clientLastName">Last Name</label>
              @if($company_info->client_details)
              <input type="text" class="form-control" name="client_last_name__{{$company_info->id}}" id="clientLastName__{{$company_info->id}}" placeholder="Last Name" value="{{$company_info->client_details->client_last_name}}" readonly>
              @else
              <input type="text" class="form-control" name="client_last_name__{{$company_info->id}}" id="clientLastName__{{$company_info->id}}" placeholder="Last Name" value="" readonly>
              @endif
                  <div class="invalid-feedback">
                      Please enter a last name.
                  </div>                                
          </div>
      </div>
      <div class="form-row mb-2">
          <div class="col">
              <label for="clientEmailAddress">Email Address</label>
              @if($company_info->client_details)
              <input type="email" name="client_email_address__{{$company_info->id}}" id="clientEmailAddress__{{$company_info->id}}" class="form-control" placeholder="meet@pat.co.za" value="{{$company_info->client_details->client_email_address}}" readonly>

              @else
              <input type="email" name="client_email_address__{{$company_info->id}}" id="clientEmailAddress__{{$company_info->id}}" class="form-control" placeholder="meet@pat.co.za" value="" readonly>

              @endif
                  <div class="invalid-feedback">
                      Please choose a valid email address.
                  </div>                                
          </div>
          <div class="col">
              <label for="clientContactNumber">Contact Number</label>
              @if($company_info->client_details)
              <input type="text" name="client_contact_number__{{$company_info->id}}" pattern="^(\+27)(\s)(\d){2}(\s)(\d){3}(\s)(\d){4}$" id="clientContactNumber__{{$company_info->id}}" class="form-control" placeholder="+27 71 123 4567" value="{{$company_info->client_details->client_contact_number}}" readonly>

              @else
              <input type="text" name="client_contact_number__{{$company_info->id}}" pattern="^(\+27)(\s)(\d){2}(\s)(\d){3}(\s)(\d){4}$" id="clientContactNumber__{{$company_info->id}}" class="form-control" placeholder="+27 71 123 4567" value="" readonly>

              @endif
                  <div class="invalid-feedback">
                      Please choose a valid contact number in the format "+27 01 234 4567" dont leave out the spaces or the "+27".
                  </div>                                
          </div>
      </div>
      <div class="form-group">
          <label for="clientPostalAddress">Postal Address</label>
              @if($company_info->client_details)
              <textarea name="client_postal_address__{{$company_info->id}}" id="clientPostalAddress__{{$company_info->id}}" class="form-control" readonly>{{$company_info->client_details->client_postal_address}}</textarea>
              @else
              <textarea name="client_postal_address__{{$company_info->id}}" id="clientPostalAddress__{{$company_info->id}}" class="form-control" readonly></textarea>
              @endif
              <div class="invalid-feedback">
                  Please enter a postal address.
              </div>                                
      </div>
      <br />
      <h5>Business Information</h5>
      <hr>
      <div class="form-row mb-2">
          <label for="businessRegisteredName">Registered Business Name</label>
          @if($company_info->client_details)
          <input type="text" name="business_registered_name__{{$company_info->id}}" id="businessRegisteredName__{{$company_info->id}}" placeholder="MeetPAT Perfect Audience Targeting" class="form-control" value="{{$company_info->client_details->business_registered_name}}" readonly>

          @else
          <input type="text" name="business_registered_name__{{$company_info->id}}" id="businessRegisteredName__{{$company_info->id}}" placeholder="MeetPAT Perfect Audience Targeting" class="form-control" value="" readonly>

          @endif
              <div class="invalid-feedback">
                  Please enter a registered business name.
              </div>                                
      </div>
      <div class="form-row mb-2">
          <div class="col">
              <label for="contactFirstName">Contact First Name</label>
              @if($company_info->client_details)
              <input type="text" name="contact_first_name__{{$company_info->id}}" id="contactFirstName__{{$company_info->id}}" class="form-control" placeholder="First Name" value="{{$company_info->client_details->contact_first_name}}" readonly>

              @else
              <input type="text" name="contact_first_name__{{$company_info->id}}" id="contactFirstName__{{$company_info->id}}" class="form-control" placeholder="First Name" value="" readonly>

              @endif
                  <div class="invalid-feedback">
                      Please enter a first name.
                  </div>                                
          </div>
          <div class="col">
              <label for="ContactLastName">Contact Last Name</label>
              @if($company_info->client_details)
              <input type="text" name="contact_last_name__{{$company_info->id}}" id="contactLastName__{{$company_info->id}}" class="form-control" placeholder="Last Name" value="{{$company_info->client_details->contact_last_name}}" readonly>

              @else
              <input type="text" name="contact_last_name__{{$company_info->id}}" id="contactLastName__{{$company_info->id}}" class="form-control" placeholder="Last Name" value="" readonly>

              @endif
                  <div class="invalid-feedback">
                      Please enter a last name.
                  </div>                                
          </div>
      </div>
      <div class="form-row mb-2">
          <div class="col">
              <label for="businessEmailAddress">Business Email Address</label>
              @if($company_info->client_details)
              <input type="email" name="contact_email_address__{{$company_info->id}}" id="businessEmailAddress__{{$company_info->id}}" class="form-control" placeholder="meet@pat.co.za" value="{{$company_info->client_details->contact_email_address}}" readonly>

              @else
              <input type="email" name="contact_email_address__{{$company_info->id}}" id="businessEmailAddress__{{$company_info->id}}" class="form-control" placeholder="meet@pat.co.za" value="" readonly>

              @endif
                  <div class="invalid-feedback">
                      Please enter a valid email address.
                  </div>                                
          </div>
          <div class="col">
              <label for="businessContactNumber">Business Contact Number</label>
              @if($company_info->client_details)
              <input type="text" name="business_contact_number__{{$company_info->id}}" pattern="^(\+27)(\s)(\d){2}(\s)(\d){3}(\s)(\d){4}$" id="businessContactNumber__{{$company_info->id}}" class="form-control" placeholder="+27 71 123 4567" value="{{$company_info->client_details->business_contact_number}}" readonly>

              @else
              <input type="text" name="business_contact_number__{{$company_info->id}}" pattern="^(\+27)(\s)(\d){2}(\s)(\d){3}(\s)(\d){4}$" id="businessContactNumber__{{$company_info->id}}" class="form-control" placeholder="+27 71 123 4567" value="" readonly>

              @endif
                  <div class="invalid-feedback">
                      Please choose a valid contact number in the format "+27 01 234 4567" dont leave out the spaces or the "+27".
                  </div>                                
          </div>
      </div>
      <div class="form-group">
          <label for="businessRegistrationNumber">Registration Number</label>
          @if($company_info->client_details)
          <input type="text" name="business_registration_number__{{$company_info->id}}" id="businessRegistrationNumber__{{$company_info->id}}" class="form-control" value="{{$company_info->client_details->business_registration_number}}"  readonly/>

          @else
          <input type="text" name="business_registration_number__{{$company_info->id}}" id="businessRegistrationNumber__{{$company_info->id}}" class="form-control" value=""  readonly/>

          @endif
              <div class="invalid-feedback">
                  Please enter a business registration number.
              </div>                                
      </div>
      <div class="form-group">
          <label for="businessVatNumber">VAT Number</label>
          @if($company_info->client_details)
          <input type="text" name="business_vat_number__{{$company_info->id}}" maxlength="10" minlength="10" id="businessVatNumber__{{$company_info->id}}" class="form-control" value="{{$company_info->client_details->business_vat_number}}"  readonly/>

          @else
          <input type="text" name="business_vat_number__{{$company_info->id}}" maxlength="10" minlength="10" id="businessVatNumber__{{$company_info->id}}" class="form-control" value=""  readonly/>

          @endif
              <div class="invalid-feedback">
                  Please enter a valid VAT number.
              </div>                                
      </div>
      <div class="form-group">
          <label for="businessPhysicalAddress">Physical Address</label>
          @if($company_info->client_details)
          <textarea name="business_physical_address__{{$company_info->id}}" id="businessPhysicalAddress__{{$company_info->id}}" class="form-control" readonly>{{$company_info->client_details->business_physical_address}}</textarea>

          @else
          <textarea name="business_physical_address__{{$company_info->id}}" id="businessPhysicalAddress__{{$company_info->id}}" class="form-control" readonly></textarea>

          @endif
              <div class="invalid-feedback">
                  Please enter a physical address.
              </div>                                
      </div>
      <div class="form-group">
          <label for="businessPostalAddress">Postal Address</label>
          @if($company_info->client_details)
          <textarea name="business_postal_address__{{$company_info->id}}" id="businessPostalAddress__{{$company_info->id}}" class="form-control" readonly>{{$company_info->client_details->business_physical_address}}</textarea>

          @else
          <textarea name="business_postal_address__{{$company_info->id}}" id="businessPostalAddress__{{$company_info->id}}" class="form-control" readonly></textarea>

          @endif
              <div class="invalid-feedback">
                  Please enter a postal address.
              </div>                                
      </div>
      <div class="form-group">
          <button type="button" id="saveChangesBtn__{{$company_info->id}}" class="btn btn-lg btn-primary btn-block d-none">Save Changes</button>
      </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="companyInfoEdit__{{$company_info->id}}" class="btn btn-primary">Edit</button>
      </div>
    </div>
  </div>
</div>

@endforeach
<!-- End Modals Company Information -->

@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('bower_components/tabulator/dist/js/tabulator.min.js')}}" defer></script>
<script type="text/javascript" src="{{ asset('js/admin_user_ctrl.min.js') }}" defer></script>
@endsection

