$(document).ready(function() {

$('#passwordInput, #confirmPasswordInput').on('keyup', function(e) {

if($('#passwordInput').val() != '' && $('#confirmPasswordInput').val() != '' && $('#passwordInput').val() != $('#confirmPasswordInput').val())
{
$('#passwordStrength').html('<div class="alert alert-danger" role="alert">' + lang.pass_no_match + '</div>');
$('#password01').html("<i class='fas fa-times text-danger'></i>");
$('#password02').html("<i class='fas fa-times text-danger'></i>");

return false;
}

var min_minus_one = char_limit.password_min - 1;
var max_minus_one = char_limit.password_max - 1;

// Must have capital letter, numbers and lowercase letters
var strongRegex = new RegExp("^(?=.{" + char_limit.password_min + "," + char_limit.password_max + "})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");

// Must have either capitals and lowercase letters or lowercase and numbers
var mediumRegex = new RegExp("^(?=.{" + min_minus_one + "," + max_minus_one + "})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");

// Must be at least 8 characters long
var okRegex = new RegExp("(?=.{" + char_limit.password_min + "," + char_limit.password_max + "}).*", "g");

if (okRegex.test($(this).val()) === false) {
// If ok regex doesn't match the password
$('#passwordStrength').html("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + lang.pass_char_count + "</div>");
$('#password01').html("<i class='fas fa-times text-danger'></i>");
 if($('#confirmPasswordInput').val()){
   $('#password02').html("<i class='fas fa-times text-danger'></i>");
 }
} else if (strongRegex.test($(this).val())) {
// If reg ex matches strong password
$('#passwordStrength').html("");
$('#password01').html("<i class='fas fa-thumbs-up text-success'></i>");
 if($('#confirmPasswordInput').val()){
   $('#password02').html("<i class='fas fa-thumbs-up text-success'></i>");
 }
} else if (mediumRegex.test($(this).val())) {
// If medium password matches the reg ex
$('#passwordStrength').html("<div class='alert alert-info alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + lang.pass_good + "</div>");
$('#password01').html("<i class='fas fa-check text-info'></i>");
 if($('#confirmPasswordInput').val()){
   $('#password02').html("<i class='fas fa-check text-info'></i>");
 }
} else {
// If password is ok
$('#passwordStrength').html("<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>" + lang.pass_weak + "</div>");
$('#password01').html("<i class='fas fa-times text-warning'></i>");
 if($('#confirmPasswordInput').val()){
   $('#password02').html("<i class='fas fa-times text-warning'></i>");
 }
}
return true;
});

});
