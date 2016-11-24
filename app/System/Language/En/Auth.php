<?php
/**
* Language File
* Auth
* English Language
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.0
*/

return [
	/** Global **/
	'user_not_logged_in' => "You must be logged in to view that page!",
	'forgotpass_button' => "Forgot Password",
	'deletesession_invalid' => "Invalid Session Hash!",
	'sessioninfo_invalid' => "Invalid Session Hash!",
	'home_title' => "Home",

	/** Account Settings **/
	'account_settings_title' => "Account Settings",

	/** Login Page **/
	'login_page_title' => "Login to Account",
	'login_page_welcome_message' => "
		<small>Welcome to the Login Page.<br>
		Please fill in the following fields.</small>
	",
	'login_button' => "Login",
	'login_field_username' => "UserName or E-Mail",
	'login_field_password' => "Password",
	'login_field_rememberme' => "Remember Me",
	'login_lockedout' => "You have been temporarily locked out!",
	'login_wait' => "Please wait %d minutes.",
	'login_username_empty' => "Username / Password is invalid!",
	'login_username_short' => "Username / Password is invalid!",
	'login_username_long' => "Username / Password is invalid!",
	'login_password_empty' => "Username / Password is invalid!",
	'login_password_short' => "Username / Password is invalid!",
	'login_password_long' => "Username / Password is invalid!",
	'login_incorrect' => "Username / Password is incorrect!",
	'login_attempts_remaining' => "%d attempts remaining!",
	'login_account_inactive' => "Account is not activated!",
	'login_success' => "You Have Successfully Logged In!",
	'login_already' => "You are already logged in!",

	/** Logout Page **/
	'logout' => "You Have Successfully Logged Out",

	/** Register Page **/
	'register_page_title' => "Register for an Account",
	'register_page_welcome_message' => "
		<small>Welcome to the Registration Page.<br>
		Please fill in the following fields.</small>
	",
	'register_button' => "Register",
	'register_field_username' => "UserName",
	'register_field_password' => "Password",
	'register_field_confpass' => "Confirm Password",
	'register_field_email' => "E-Mail",
	'register_username_empty' => "Username field is empty!",
	'register_username_short' => "Username is too short!",
	'register_username_long' => "Username is too long!",
	'register_password_empty' => "Password field is empty!",
	'register_password_short' => "Password is too short!",
	'register_password_long' => "Password is too long!",
	'register_password_nomatch' => "Passwords do not match!",
	'register_password_username' => "Password cannot contain the username!",
	'register_email_empty' => "Email field is empty!",
	'register_email_short' => "Email is too short!",
	'register_email_long' => "Email is too long!",
	'register_email_invalid' => "Email is invalid!",
	'register_username_exist' => "Username is already in use!",
	'register_email_exist' => "Email is already in use!",
	'register_success' => "Registration Successful! Check Your Email For Activating your Account.",
	'register_success_noact' => "Registration Successful! Click <a href='".DIR."Login'>Login</a> to login.",
	'register_email_loggedin' => "You are currently logged in!",
	'register_error' => "Registration Error: Please try again",
	'register_error_recap' => "reCAPTCHA Error: Please try again",

	/** Register Email **/
	'regi_email_hello' => "Hello",
	'regi_email_recently_registered' => "You recently registered a new account on",
	'regi_email_to_activate' => "To activate your account please click the following link",
	'regi_email_act_my_acc' => "Activate my account",
	'regi_email_you_may_copy' => "You May Copy and Paste this URL in your Browser Address Bar",

	/** User Activation Page **/
	'activate_title' => "Account Activation",
	'activate_welcomemessage' => "Welcome to the Account Activation.",
	'activate_send_button' => "Resend Activation E-Mail",
	'activate_username_empty' => "Invalid URL!",
	'activate_username_short' => "Invalid URL!",
	'activate_username_long' => "Invalid URL!",
	'activate_key_empty' => "Invalid URL!",
	'activate_key_short' => "Invalid URL!",
	'activate_key_long' => "Invalid URL!",
	'activate_username_incorrect' => "Username is incorrect!",
	'activate_account_activated' => "Account is already activated!",
	'activate_success' => "Account successfully activated! ",
	'activate_key_incorrect' => "Activation key is incorrect!",
	'activate_fail' => "Account Activation <strong>Failed</strong>! Please try again!",

	/** Change Password Page **/
	'changepass_title' => "Change Password",
	'changepass_welcomemessage' => "Fill in the following form to change your Password.",
	'changepass_username_empty' => "Error encountered!",
	'changepass_username_short' => "Error encountered!",
	'changepass_username_long' => "Error encountered!",
	'changepass_currpass_empty' => "Current Password field is empty!",
	'changepass_currpass_short' => "Current Password is too short!",
	'changepass_currpass_long' => "Current Password is too long!",
	'changepass_newpass_empty' => "New Password field is empty!",
	'changepass_newpass_short' => "New Password is too short!",
	'changepass_newpass_long' => "New Password is too long!",
	'changepass_password_username' => "Password cannot contain the username!",
	'changepass_password_nomatch' => "Passwords do not match!",
	'changepass_username_incorrect' => "Error encountered!",
	'changepass_success' => "Password successfully changed!",
	'changepass_currpass_incorrect' => "Current Password is incorrect!",

	/** Change Email Page **/
	'changeemail_title' => "Change E-Mail",
	'changeemail_welcomemessage' => "Fill in the following form to change your E-Mail.",
	'changeemail_username_empty' => "Error encountered!",
	'changeemail_username_short' => "Error encountered!",
	'changeemail_username_long' => "Error encountered!",
	'changeemail_email_empty' => "Email field is empty!",
	'changeemail_email_short' => "Email is too short!",
	'changeemail_email_long' => "Email is too long!",
	'changeemail_email_invalid' => "Email is invalid!",
	'changeemail_username_incorrect' => "Error encountered!",
	'changeemail_email_match' => "New email address matches the existing one!",
	'changeemail_success' => "Email address successfully changed!",
	'changeemail_error' => "An error occurred while changing your email",

	/** Reset Password Page **/
	'forgotpass_title' => "Forgot Password",
	'forgotpass_welcomemessage' => "Enter your e-mail address to send reset password email.",
	'forgotpass_button' => "Send Password Reset E-Mail",
	'resetpass_title' => "Reset Password",
	'resetpass_welcomemessage' => "Enter your new password.",
	'resetpass_lockedout' => "You have been temporarily locked out!",
	'resetpass_wait' => "Please wait %d mins.",
	'resetpass_email_empty' => "Email field is empty!",
	'resetpass_email_short' => "Email is too short!",
	'resetpass_email_long' => "Email is too long!",
	'resetpass_email_invalid' => "Email is invalid!",
	'resetpass_email_incorrect' => "Email is incorrect!",
	'resetpass_attempts_remaining' => "%d attempts remaining!",
	'resetpass_email_sent' => "Password Reset Request sent to your email address!",
	'resetpass_email_error' => "No email is affiliated with any accounts on this website!",
	'resetpass_key_empty' => "Reset Key field is empty!",
	'resetpass_key_short' => "Reset Key is too short!",
	'resetpass_key_long' => "Reset Key is too long!",
	'resetpass_newpass_empty' => "New Password field is empty!",
	'resetpass_newpass_short' => "New Password is too short!",
	'resetpass_newpass_long' => "New Password is too long!",
	'resetpass_newpass_username' => "New Password cannot contain username!",
	'resetpass_newpass_nomatch' => "Passwords do not match!",
	'resetpass_username_incorrect' => "Error encountered!",
	'resetpass_success' => "Password successfully changed!",
	'resetpass_key_incorrect' => "Reset Key is incorrect!",
	'resetpass_error' => "An error occurred while changing your password!",

	/** Account Actication **/
	'checkresetkey_username_incorrect' => "Username is incorrect!",
	'checkresetkey_key_incorrect' => "Reset Key is incorrect!",
	'checkresetkey_lockedout' => "You have been temporarily locked out!",
	'checkresetkey_wait30' => "Please wait 30 mins.",
	'checkresetkey_attempts_remaining' => "%d attempts remaining!",

	/** Resend Activation **/
	'resendactivation_title' => "Resend Activation Email",
	'resendactivation_welcomemessage' => "Enter your e-mail address to resend Activation Email.",
	'resendactivation_button' => "Resend Activation Email",
	'resendactivation_success' => "An activation code has been sent to your email!",
	'resendactivation_error' => "No account is affiliated with the email provided or it may have already been activated.",

	/** Delete Account Page **/
	'deleteaccount_username_empty' => "Error encountered!",
	'deleteaccount_username_short' => "Error encountered!",
	'deleteaccount_username_long' => "Error encountered!",
	'deleteaccount_password_empty' => "Password field is empty!",
	'deleteaccount_password_short' => "Password is too short!",
	'deleteaccount_password_long' => "Password is too long!",
	'deleteaccount_username_incorrect' => "Error encountered!",
	'deleteaccount_success' => "Account deleted successfully!",
	'deleteaccount_password_incorrect' => "Password is incorrect!",

	/** Other **/
	'logactivity_username_short' => "Error encountered!",
	'logactivity_username_long' => "Error encountered!",
	'logactivity_action_empty' => "Error encountered!",
	'logactivity_action_short' => "Error encountered!",
	'logactivity_action_long' => "Error encountered!",
	'logactivity_addinfo_long' => "Error encountered!",
];
