<?php
/**
* Language File
* Auth
* French Language
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @translator Eddy Beaupré <eddy@beaupre.biz>
* @version 4.3.0
*/

return [
	/** Global **/
	'user_not_logged_in' => "Vous devez être connecté pour voir cette page!",
	'forgotpass_button' => "Mot de passe oublié",
	'deletesession_invalid' => "Hash de session invalide!",
	'sessioninfo_invalid' => "Hash de session invalide!",
	'home_title' => "Accueil",

	/** Account Settings **/
	'account_settings_title' => "Paramètres du compte",

	/** Login Page **/
	'login_page_title' => "Connectez-vous au compte",
	'login_page_welcome_message' => "
		<small>Bienvenue sur la page de connexion.<br>
		S'il vous plaît remplir les champs suivants.</small>
	",
	'login_button' => "S'identifier",
	'login_field_username' => "Nom d'utilisateur ou courriel",
	'login_field_password' => "Mot de passe",
	'login_field_rememberme' => "Se souvenir de moi",
	'login_lockedout' => "Vous avez été temporairement exclu!",
	'login_wait' => "Veuillez patienter %d minutes.",
	'login_username_empty' => "Nom d'utilisateur et/ou Mot de passe invalide!",
	'login_username_short' => "Nom d'utilisateur et/ou Mot de passe invalide!",
	'login_username_long' => "Nom d'utilisateur et/ou Mot de passe invalide!",
	'login_password_empty' => "Nom d'utilisateur et/ou Mot de passe invalide!",
	'login_password_short' => "Nom d'utilisateur et/ou Mot de passe invalide!",
	'login_password_long' => "Nom d'utilisateur et/ou Mot de passe invalide!",
	'login_incorrect' => "Nom d'utilisateur et/ou Mot de passe invalide!",
	'login_attempts_remaining' => "%d tentatives restantes!",
	'login_account_inactive' => "Le compte n'est pas activé!",
	'login_success' => "Vous vous êtes connecté avec succès!",
	'login_already' => "Vous êtes déjà connecté!",

	/** Logout Page **/
	'logout' => "Vous vous êtes déconnecté avec succès!",

	/** Register Page **/
	'register_page_title' => "Inscrivez-vous pour un compte",
	'register_page_welcome_message' => "
		<small>Bienvenue sur la page d'inscription.<br>
		S'il vous plaît remplir les champs suivants.</small>
	",
	'register_button' => "Inscription",
	'register_field_username' => "Nom d'utilisateur",
	'register_field_password' => "Mot de passe",
	'register_field_confpass' => "Confirmez le mot de passe",
	'register_field_email' => "Courriel",
	'register_username_empty' => "Champ nom d'utilisateur est vide!",
	'register_username_short' => "Le nom d'utilisateur est trop court!",
	'register_username_long' => "Le nom d'utilisateur est trop long!",
	'register_username_invalid' => "Nom d'utilisateur invalide!",
	'register_password_empty' => "Champ mot de passe est vide!",
	'register_password_short' => "Le mot de passe est trop court!",
	'register_password_long' => "Le mot de passe est trop long!",
	'register_password_nomatch' => "Les mots de passe ne correspondent pas!",
	'register_password_username' => "Le mot de passe ne peut pas contenir le nom d'utilisateur!",
	'register_email_empty' => "Le champ courriel est vide !",
	'register_email_short' => "Le courriel est trop court!",
	'register_email_long' => "Le courriel est trop long!",
	'register_email_invalid' => "Le courriel est invalide!",
	'register_username_exist' => "Le nom d'utilisateur est déjà utilisé!",
	'register_email_exist' => "Le courriel est déjà utilisé!",
	'register_success' => "Inscription réussi! Vérifiez votre courriel pour activer votre compte.",
	'register_success_noact' => "Inscription réussi! Cliquez sur <a href='".DIR."Login'>Connexion</a> pour vous connecter.",
	'register_email_loggedin' => "Vous êtes actuellement connecté!",
	'register_error' => "Erreur d'enregistrement, veuillez réessayer",
	'register_error_recap' => "Erreur reCAPTCHA, veuillez réessayer",

	/** Register Email **/
	'regi_email_hello' => "Bonjour",
	'regi_email_recently_registered' => "Vous avez récemment enregistré un nouveau compte sur",
	'regi_email_to_activate' => "Pour activer votre compte, veuillez cliquer sur le lien suivant",
	'regi_email_act_my_acc' => "Activer mon compte",
	'regi_email_you_may_copy' => "Vous pouvez copier et coller cette URL dans la barre d'adresse de votre navigateur",

	/** User Activation Page **/
	'activate_title' => "Activation de compte",
	'activate_welcomemessage' => "Bienvenue dans l'activation du compte.",
	'activate_send_button' => "Renvoyer l'email d'activation",
	'activate_username_empty' => "URL invalide!",
	'activate_username_short' => "URL invalide!",
	'activate_username_long' => "URL invalide!",
	'activate_key_empty' => "URL invalide!",
	'activate_key_short' => "URL invalide!",
	'activate_key_long' => "URL invalide!",
	'activate_username_incorrect' => "Le nom d'utilisateur est incorrect!",
	'activate_account_activated' => "Le compte est déjà activé!",
	'activate_success' => "Compte activé avec succès! ",
	'activate_key_incorrect' => "La clé d'activation est incorrecte!",
	'activate_fail' => "Activation du compte <strong>échoué</strong>! Veuillez réessayer!",

	/** Change Password Page **/
	'changepass_title' => "Changer le mot de passe",
	'changepass_welcomemessage' => "Remplissez le formulaire suivant pour changer votre mot de passe.",
	'changepass_username_empty' => "Erreur rencontrée!",
	'changepass_username_short' => "Erreur rencontrée!",
	'changepass_username_long' => "Erreur rencontrée!",
	'changepass_currpass_empty' => "Le mot de passe actuel est vide!",
	'changepass_currpass_short' => "Le mot de passe actuel est trop court!",
	'changepass_currpass_long' => "Le mot de passe actuel est trop long!",
	'changepass_newpass_empty' => "Le nouveau mot de passe est vide!",
	'changepass_newpass_short' => "Le nouveau mot de passe est trop court!",
	'changepass_newpass_long' => "Le nouveau mot de passe est trop long!",
	'changepass_password_username' => "Le mot de passe ne peut pas contenir le nom d'utilisateur!",
	'changepass_password_nomatch' => "Les mots de passe ne correspondent pas!",
	'changepass_username_incorrect' => "Erreur rencontrée!",
	'changepass_success' => "Mot de passe changé avec succès!",
	'changepass_currpass_incorrect' => "Le mot de passe actuel est incorrect!",

	/** Change Email Page **/
	'changeemail_title' => "Changer le courriel",
	'changeemail_welcomemessage' => "Remplissez le formulaire suivant pour changer votre courriel.",
	'changeemail_username_empty' => "Erreur rencontrée!",
	'changeemail_username_short' => "Erreur rencontrée!",
	'changeemail_username_long' => "Erreur rencontrée!",
	'changeemail_email_empty' => "Le courriel actuel est vide!",
	'changeemail_email_short' => "Le courriel actuel est trop court!",
	'changeemail_email_long' => "Le courriel actuel est trop long!",
	'changeemail_email_invalid' => "Le courriel est invalide!",
	'changeemail_username_incorrect' => "Erreur rencontrée!",
	'changeemail_email_match' => "La nouvelle addresse courriel correspond à celle existante!",
	'changeemail_success' => "Courriel changé avec succès!",
	'changeemail_error' => "Une erreur s'est produite lors de la modification de votre courriel",

	/** Reset Password Page **/
	'forgotpass_title' => "Mot de passe oublié",
	'forgotpass_welcomemessage' => "Entrez votre adresse courriel pour envoyer le mot de passe de réinitialisation.",
	'forgotpass_button' => "Envoyer le mot de passe de réinitialisation par courriel",
	'resetpass_title' => "Réinitialiser le mot de passe",
	'resetpass_welcomemessage' => "Entrez votre nouveau mot de passe.",
	'resetpass_lockedout' => "Vous avez été temporairement exclu!",
	'resetpass_wait' => "Veuillez patienter %d minutes.",
	'resetpass_email_empty' => "Le champ courriel est vide!",
	'resetpass_email_short' => "Le courriel est trop court!",
	'resetpass_email_long' => "Le courriel est trop long!",
	'resetpass_email_invalid' => "Le courriel est invalide!",
	'resetpass_email_incorrect' => "Le courriel est incorrect!",
	'resetpass_attempts_remaining' => "%d tentatives restantes!",
	'resetpass_email_sent' => "Demande de réinitialisation du mot de passe envoyée à votre adresse courriel!",
	'resetpass_email_error' => "Le courriel n'est associé à aucun compte sur ce site!",
	'resetpass_key_empty' => "La clé de réinitialisation est vide!",
	'resetpass_key_short' => "La clé de réinitialisation est trop courte!",
	'resetpass_key_long' => "La clé de réinitialisation est trop longue!",
	'resetpass_newpass_empty' => "Le champ Nouveau mot de passe est vide!",
	'resetpass_newpass_short' => "Le nouveau mot de passe est trop court!",
	'resetpass_newpass_long' => "Le nouveau mot de passe est trop long!",
	'resetpass_newpass_username' => "Le nouveau mot de passe ne peut pas contenir de nom d'utilisateur!",
	'resetpass_newpass_nomatch' => "Les mots de passe ne correspondent pas!",
	'resetpass_username_incorrect' => "Erreur rencontrée!",
	'resetpass_success' => "Mot de passe changé avec succès!",
	'resetpass_key_incorrect' => "La clé de réinitialisation est incorrecte!",
	'resetpass_error' => "Une erreur est survenue lors de la modification de votre mot de passe!",

	/** Account Actication **/
	'checkresetkey_username_incorrect' => "Le nom d'utilisateur est incorrect!",
	'checkresetkey_key_incorrect' => "La clé de réinitialisation est incorrecte!",
	'checkresetkey_lockedout' => "Vous avez été temporairement exclu!",
	'checkresetkey_wait30' => "Veuillez patienter 30 minutes.",
	'checkresetkey_attempts_remaining' => "%d tentatives restantes!",

	/** Resend Activation **/
	'resendactivation_title' => "Renvoyer le courriel d'activation",
	'resendactivation_welcomemessage' => "Entrez votre adresse e-mail pour renvoyer le courriel d'activation.",
	'resendactivation_button' => "Renvoyer l'email d'activation",
	'resendactivation_success' => "Un code d'activation a été envoyé à votre courriel!",
	'resendactivation_error' => "Aucun compte n'est affilié au courriel fourni ou il a peut-être déjà été activé.",

	/** Delete Account Page **/
	'deleteaccount_username_empty' => "Erreur rencontrée!",
	'deleteaccount_username_short' => "Erreur rencontrée!",
	'deleteaccount_username_long' => "Erreur rencontrée!",
	'deleteaccount_password_empty' => "Le champ mot de passe est vide!",
	'deleteaccount_password_short' => "Le mot de passe est trop court!",
	'deleteaccount_password_long' => "Le mot de passe est trop court!",
	'deleteaccount_username_incorrect' => "Erreur rencontrée!",
	'deleteaccount_success' => "Compte supprimé avec succès!",
	'deleteaccount_password_incorrect' => "Le mot de passe est incorrect!",

	/** Other **/
	'logactivity_username_short' => "Erreur rencontrée!",
	'logactivity_username_long' => "Erreur rencontrée!",
	'logactivity_action_empty' => "Erreur rencontrée!",
	'logactivity_action_short' => "Erreur rencontrée!",
	'logactivity_action_long' => "Erreur rencontrée!",
	'logactivity_addinfo_long' => "Erreur rencontrée!",

	/** New Stuff - 4.3.0 **/
	'new_password_label' => "Nouveau mot de passe",
	'confirm_new_password_label' => "Confirmer le nouveau mot de passe",
	'change_my_password_button' => "Changer mon mot de passe",
];
