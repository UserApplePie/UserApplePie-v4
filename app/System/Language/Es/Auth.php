<?php
/**
* Language File
* Auth
* Spanish Language
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.0
*/

return [
	/** Global **/
	'user_not_logged_in' => "Usted debe ser conectado para ver esa página !",
	'forgotpass_button' => "Se te olvidó tu contraseña",
	'deletesession_invalid' => "Inválida Sesión Hash !",
	'sessioninfo_invalid' => "Inválida Sesión Hash !",
	'home_title' => "Casa",

	/** Account Settings **/
	'account_settings_title' => "Configuraciones de la cuenta",

	/** Login Page **/
	'login_page_title' => "Entrar en su cuenta",
	'login_page_welcome_message' => "
		<small>Bienvenido a la página de entrada.<br>
		Por favor rellene los siguientes campos.</small>
	",
	'login_button' => "Iniciar sesión",
	'login_field_username' => "Nombre de usuario o correo electrónico",
	'login_field_password' => "Contraseña",
	'login_field_rememberme' => "Recuérdame",
	'login_lockedout' => "¡Ha sido temporalmente bloqueado!",
	'login_wait' => "Por favor espere %d minutos",
	'login_username_empty' => "¡No es válido el Usuario / Contraseña !",
	'login_username_short' => "¡No es válido el Usuario / Contraseña !",
	'login_username_long' => "¡No es válido el Usuario / Contraseña !",
	'login_password_empty' => "¡No es válido el Usuario / Contraseña !",
	'login_password_short' => "¡No es válido el Usuario / Contraseña !",
	'login_password_long' => "¡No es válido el Usuario / Contraseña !",
	'login_incorrect' => "¡No es válido el Usuario / Contraseña !",
	'login_attempts_remaining' => "¡Quedan %d intentos !",
	'login_account_inactive' => "¡La cuenta no está activada !",
	'login_success' => "¡Ud está conectado !",
	'login_already' => "¡Ud ya estaba conectado !",

	/** Logout Page **/
	'logout' => "Has terminado tu sesion satisfactoriamente",

	/** Register Page **/
	'register_page_title' => "Registra una cuenta",
	'register_page_welcome_message' => "
		<small>Bienvenido a la página de registro.<br>
		Por favor rellene los siguientes campos.</small>
	",
	'register_button' => "Registro",
	'register_field_username' => "Nombre de usuario",
	'register_field_password' => "Contraseña",
	'register_field_confpass' => "Confirmar contraseña",
	'register_field_email' => "E-Mail",
	'register_username_empty' => "Campo nombre usuario está vacio !",
	'register_username_short' => "Nombre de usuario demasiado corto !",
	'register_username_long' => "Nombre e usuario demasiado largo !",
	'register_username_invalid' => "Nombre de usuario invalido !",
	'register_password_empty' => "Campo contraseña está vacio !",
	'register_password_short' => "Contraseña es demasiado corta !",
	'register_password_long' => "Contraseña es demasiado larga !",
	'register_password_nomatch' => "Contraseñas no son iguales !",
	'register_password_username' => "La contraseña no puede contener el nombre de usuario !",
	'register_email_empty' => "Campo de Email está vacio !",
	'register_email_short' => "El email es demasiado corto !",
	'register_email_long' => "El email es demasiado largo !",
	'register_email_invalid' => "El email no es valido !",
	'register_username_exist' => "El nombre de usuario ya está siendo usado !",
	'register_email_exist' => "Ese email ya está siendo usado !",
	'register_success' => "¡Nueva cuenta creada! El email de activación ha sido enviado a su dirección email.",
	'register_success_noact' => "Registro exitoso! Hacer clic <a href='".DIR."Login'>Login</a> iniciar sesión.",
	'register_email_loggedin' => "Ud está conectado actualmente. !",
	'register_error' => "Error de registro : Por favor, inténtelo de nuevo",
	'register_error_recap' => "reCAPTCHA Error : Por favor, inténtelo de nuevo",

	/** Register Email **/
	'regi_email_hello' => "Hola",
	'regi_email_recently_registered' => "Recientemente has registrado una cuenta nueva en",
	'regi_email_to_activate' => "Para activar su cuenta, por favor haga clic en el siguiente enlace",
	'regi_email_act_my_acc' => "Activar mi cuenta",
	'regi_email_you_may_copy' => "Usted puede copiar y pegar esta URL en el navegador de la barra de direcciones",

	/** User Activation Page **/
	'activate_title' => "Activación de cuenta",
	'activate_welcomemessage' => "Bienvenido a la Activación de la cuenta.",
	'activate_send_button' => "Reenviar E-Mail de activación",
	'activate_username_empty' => "URL Inválido !",
	'activate_username_short' => "URL Inválido !",
	'activate_username_long' => "URL Inválido !",
	'activate_key_empty' => "URL Inválido !",
	'activate_key_short' => "URL Inválido !",
	'activate_key_long' => "URL Inválido !",
	'activate_username_incorrect' => "Nombre de usuario es incorrecto !",
	'activate_account_activated' => "La cuenta ya estaba activada !",
	'activate_success' => "La cuenta fue activada exitosamente !",
	'activate_key_incorrect' => "La clave de activación es incorrrecta !",
	'activate_fail' => "Activación de cuenta <strong>Ha fallado</strong>! Por favor, inténtelo de nuevo!",

	/** Change Password Page **/
	'changepass_title' => "Cambia la contraseña",
	'changepass_welcomemessage' => "Rellena el siguiente formulario para cambiar su contraseña.",
	'changepass_username_empty' => "¡Se encontró un error !",
	'changepass_username_short' => "¡Se encontró un error !",
	'changepass_username_long' => "¡Se encontró un error !",
	'changepass_currpass_empty' => "¡Campo de contraseña está vacio !",
	'changepass_currpass_short' => "¡La contraseña es demasiado corta !",
	'changepass_currpass_long' => "¡ La contraseña es demasiado larga !",
	'changepass_newpass_empty' => "¡ Campo de nueva contraseña está vacio !",
	'changepass_newpass_short' => "¡ Nueva contraseña es muy corta !",
	'changepass_newpass_long' => "¡ Nueva contraseña es muy larga !",
	'changepass_password_username' => "¡ La contraseña no puede contener el nombre de usuario !",
	'changepass_password_nomatch' => "¡ Las contraseñas no son iguales !",
	'changepass_username_incorrect' => "¡ Se encontró un error !",
	'changepass_success' => "¡ Contraseña cambiada correctamete !",
	'changepass_currpass_incorrect' => "¡ Contraseña actual es incorrecta !",

	/** Change Email Page **/
	'changeemail_title' => "Cambiar e-mail",
	'changeemail_welcomemessage' => "Rellena el siguiente formulario para cambiar su dirección de e - mail.",
	'changeemail_username_empty' => "¡Se encontró un error !",
	'changeemail_username_short' => "¡Se encontró un error !",
	'changeemail_username_long' => "¡Se encontró un error !",
	'changeemail_email_empty' => "¡Campo de email está vacio!",
	'changeemail_email_short' => "El email es demasiado corto !",
	'changeemail_email_long' => "El email es demasiado largo !",
	'changeemail_email_invalid' => "El email no es valido !",
	'changeemail_username_incorrect' => "¡Se encontró un error !",
	'changeemail_email_match' =>  "Ese email ya está siendo usado !",
	'changeemail_success' => "¡El email ha sido cambiado exitosamente !",
	'changeemail_error' => "Se ha producido un error al cambiar su correo electrónico",

	/** Reset Password Page **/
	'forgotpass_title' => "Se te olvidó tu contraseña",
	'forgotpass_welcomemessage' => "Introduzca su dirección de correo electrónico para enviar correo electrónico de restablecimiento de contraseña.",
	'forgotpass_button' => "Enviar restablecimiento de contraseña E-Mail",
	'resetpass_title' => "Restablecer la contraseña",
	'resetpass_welcomemessage' => "Introduzca su nueva contraseña.",
	'resetpass_lockedout' => "¡Ha sido temporalmente bloqueado!",
	'resetpass_wait' => "Por favor esperar %d min.",
	'resetpass_email_empty' => "¡Campo de email está vacio!",
	'resetpass_email_short' =>  "El email es demasiado corto !",
	'resetpass_email_long' => "El email es demasiado largo !",
	'resetpass_email_invalid' => "El email no es valido !",
	'resetpass_email_incorrect' => "¡El email no es correcto!",
	'resetpass_attempts_remaining' => "¡ faltan %d intentos !",
	'resetpass_email_sent' => "¡La restauración de la contraseña ha sido enviado a su email!",
	'resetpass_email_error' => "Sin correo electrónico está afiliado con ninguna cuenta en este sitio web!",
	'resetpass_key_empty' => "¡El campo de clave de restauración está vacio!",
	'resetpass_key_short' => "¡La clave de restauración es muy corta!",
	'resetpass_key_long' => "¡La clave de restauración es muy larga!",
	'resetpass_newpass_empty' => "¡El campo de contraseña está vacio!",
	'resetpass_newpass_short' => "¡La nueva contraseña es demasiado corta!",
	'resetpass_newpass_long' => "¡La nueva contraseña es muy larga¡",
	'resetpass_newpass_username' => "¡La nueva contraseña no puede contener el nombre de usuario!",
	'resetpass_newpass_nomatch' => "¡Las contraseñas no son iguales !",
	'resetpass_username_incorrect' =>  "¡Se encontró un error !",
	'resetpass_success' => "¡Contraseña cambiada exitosamente!",
	'resetpass_key_incorrect' => "¡La clave de restauración es incorrecta!",
	'resetpass_error' => "Se ha producido un error al cambiar su contraseña!",

	/** Account Actication **/
	'checkresetkey_username_incorrect' => "¡ El usuario es incorrecto !",
	'checkresetkey_key_incorrect' => "¡ La clave de restauración es incorrecta !",
	'checkresetkey_lockedout' => "¡Ha sido temporalmente bloqueado!",
	'checkresetkey_wait' => "Por favor esperar %d min.",
	'checkresetkey_attempts_remaining' => "¡Quedan %d intentos !",

	/** Resend Activation **/
	'resendactivation_title' => "Reenviar E-Mail de activación",
	'resendactivation_welcomemessage' => "Introduzca su dirección de correo electrónico para reenviar correo electrónico de activación.",
	'resendactivation_button' => "Reenviar E-Mail de activación",
	'resendactivation_success' => "Un código de activación ha sido enviada a su correo electrónico!",
	'resendactivation_error' => "No se cuenta está afiliado con el correo electrónico proporcionado o puede que ya se haya activado.",

	/** Delete Account Page **/
	'deleteaccount_username_empty' => "¡Se encontró un error !",
	'deleteaccount_username_short' => "¡Se encontró un error !",
	'deleteaccount_username_long' => "¡Se encontró un error !",
	'deleteaccount_password_empty' => "¡ Campo de nueva contraseña está vacio !",
	'deleteaccount_password_short' => "¡La contraseña es demasiado corta !",
	'deleteaccount_password_long' => "¡ La contraseña es demasiado larga !",
	'deleteaccount_username_incorrect' => "¡Se encontró un error !",
	'deleteaccount_success' => "¡Cuenta borrada exitosamente!",
	'deleteaccount_password_incorrect' => "¡La contraseña es incorrecta!",

	/** Other **/
	'logactivity_username_short' => "¡Se encontró un error !",
	'logactivity_username_long' => "¡Se encontró un error !",
	'logactivity_action_empty' => "¡Se encontró un error !",
	'logactivity_action_short' => "¡Se encontró un error !",
	'logactivity_action_long' => "¡Se encontró un error !",
	'logactivity_addinfo_long' => "¡Se encontró un error !",
];
