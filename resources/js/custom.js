// Javascript for ameetpat-dmin/users page

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



var x = document.getElementsByClassName("ellipsis-js")[0];
var text_content = "";

if(x)
{
    text_content = x.textContent;
    x.innerHTML = x.textContent.substr(0, 255) + "<a href='#' onclick='seeMore(event);' id='see_more'>[ Read More ]</a>" ;
}

var seeMore = function(event) {
    if(x)
    {   
        event.preventDefault();
        x.innerHTML = text_content + "<a href='#' onclick='seeLess(event);' id='see_less'>[ Show Less ]</a>";
    }

}

var seeLess = function(event) {
    if(x)
    {
        event.preventDefault();
        x.innerHTML = x.textContent.substr(0, 255) + "<a href='#' onclick='seeMore(event);' id='see_more'>[ Read More ]</a>" ;
    }
 }






