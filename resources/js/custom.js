// Javascript for meetpat-admin/users page

// Tooltips

$('.edit-tooltip').tooltip('enable');
$('.delete-tooltip').tooltip('enable');
$('.active-tooltip').tooltip('enable');
$('.GeneratePassword').tooltip('enable');
$('.ViewPassword').tooltip('enable');

// Get generated password for editing user in admin

$('.collapsePasswordChange').on('show.bs.collapse', function() {
    $('.ChangePasswordBtn').html('Cancel Password Change');
    $('.ChangePasswordBtn').removeClass('btn-warning');
    $('.ChangePasswordBtn').addClass('btn-danger PasswordInput');
});

$('.collapsePasswordChange').on('hidden.bs.collapse', function() {
    $('.ChangePasswordBtn').html('Change Password');
    $('.ChangePasswordBtn').removeClass('btn-danger');
    $('.ChangePasswordBtn').addClass('btn-warning PasswordInput');
    $('.PasswordInput').val('');
    $('.PasswordInput').removeClass('is-valid');
    $('.PasswordInput').removeClass('is-invalid');

});