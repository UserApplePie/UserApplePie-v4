<?php
/**
* Account Resend Activation View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language, Libs\Form;
?>

<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$title;?>
		</div>
		<div class="card-body">
			<p><?=$welcomeMessage;?></p>

      <form class="form" method="post">
          <div class="col-xs-12">
						<div class="form-group">
							<div class="input-group mb-3">
								<div class='input-group-prepend'>
									<span class='input-group-text'>
										<?=Language::show('register_field_email', 'Auth'); ?>
									</span>
								</div>
								<input  class="form-control" type="email" id="email" name="email" placeholder="<?=Language::show('register_field_email', 'Auth'); ?>">
							</div>
						</div>
              <input type="hidden" name="token_resendactivation" value="<?=$csrfToken;?>" />
              <button class="btn btn-primary" type="submit" name="submit"><?=Language::show('activate_send_button', 'Auth')?></button>
          </div>
      </form>

    </div>
  </div>
</div>
