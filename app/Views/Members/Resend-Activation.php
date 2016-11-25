<?php
/**
* Account Resend Activation View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.0
*/

use Libs\Language, Libs\Form;
?>

<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1><?=$title;?></h1>
		</div>
		<div class="panel-body">
			<p><?=$welcomeMessage;?></p>

      <form class="form" method="post">
          <div class="col-xs-12">
              <div class="form-group">
                  <label class="control-label">E-mail</label>
                  <input  class="form-control" type="email" id="email" name="email" placeholder="E-mail">
              </div>
              <input type="hidden" name="token_resendactivation" value="<?=$csrfToken;?>" />
              <button class="btn btn-primary" type="submit" name="submit"><?=Language::show('activate_send_button', 'Auth')?></button>
          </div>
      </form>

    </div>
  </div>
</div>
