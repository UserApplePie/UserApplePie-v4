$(document).ready(function() {

$('#passwordInput, #confirmPasswordInput').on('keyup', function(e) {

if($('#passwordInput').val() != '' && $('#confirmPasswordInput').val() != '' && $('#passwordInput').val() != $('#confirmPasswordInput').val())
{
$('#passwordStrength').html('<div class="alert alert-danger" role="alert">Passwords do not match!</div>');
$('#password01').html("<i class='glyphicon glyphicon-remove text-danger'></i>");
$('#password02').html("<i class='glyphicon glyphicon-remove text-danger'></i>");

return false;
}

// Must have capital letter, numbers and lowercase letters
var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");

// Must have either capitals and lowercase letters or lowercase and numbers
var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");

// Must be at least 8 characters long
var okRegex = new RegExp("(?=.{8,}).*", "g");

if (okRegex.test($(this).val()) === false) {
// If ok regex doesn't match the password
$('#passwordStrength').html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Password must be at least 8 characters long.</div>");
$('#password01').html("<i class='glyphicon glyphicon-remove text-danger'></i>");
 if($('#confirmPasswordInput').val()){
   $('#password02').html("<i class='glyphicon glyphicon-remove text-danger'></i>");
 }
} else if (strongRegex.test($(this).val())) {
// If reg ex matches strong password
$('#passwordStrength').html("");
$('#password01').html("<i class='glyphicon glyphicon-thumbs-up text-success'></i>");
 if($('#confirmPasswordInput').val()){
   $('#password02').html("<i class='glyphicon glyphicon-thumbs-up text-success'></i>");
 }
} else if (mediumRegex.test($(this).val())) {
// If medium password matches the reg ex
$('#passwordStrength').html("<div class='alert alert-info alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Good Password!</div>");
$('#password01').html("<i class='glyphicon glyphicon-ok text-info'></i>");
 if($('#confirmPasswordInput').val()){
   $('#password02').html("<i class='glyphicon glyphicon-ok text-info'></i>");
 }
} else {
// If password is ok
$('#passwordStrength').html("<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Weak Password!</div>");
$('#password01').html("<i class='glyphicon glyphicon-remove text-warning'></i>");
 if($('#confirmPasswordInput').val()){
   $('#password02').html("<i class='glyphicon glyphicon-remove text-warning'></i>");
 }
}
return true;
});

});
