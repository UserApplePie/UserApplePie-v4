<?php
/**
* Checks reCAPTCHA to make sure keys are working
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

require APPDIR.'Install/recaptcha.php';

//reCAPTCHA 2.0 check
$response = null;

// check secret key
$reCaptcha = new ReCaptcha(RECAP_PRIVATE_KEY);

if ($response != null && $response->success) {
$errors = array();
} ?>
<label>If you can see the ReCaptcha, you have entered your keys correctly.</label>
<div class="g-recaptcha" data-sitekey="<?=RECAP_PUBLIC_KEY?>"></div>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php
}
