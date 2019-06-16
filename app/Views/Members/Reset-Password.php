<?php
/**
* Account Reset Password View
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
        <div class="row">
            <form class="form" method="post">
                <div class="col-xs-12">
									<div class="form-group">
										<div class="input-group mb-3">
											<div class='input-group-prepend'>
												<span class='input-group-text'>
													<?=Language::show('new_password_label', 'Auth'); ?>
												</span>
											</div>
											<input  class="form-control" type="password" id="password" name="password" placeholder="<?=Language::show('new_password_label', 'Auth'); ?>">
										</div>
									</div>
									<div class="form-group">
										<div class="input-group mb-3">
											<div class='input-group-prepend'>
												<span class='input-group-text'>
													<?=Language::show('confirm_new_password_label', 'Auth'); ?>
												</span>
											</div>
											<input  class="form-control" type="password" id="confirmPassword" name="confirmPassword" placeholder="<?=Language::show('confirm_new_password_label', 'Auth'); ?>">
										</div>
									</div>
    
                    <input type="hidden" name="token_resetpassword" value="<?=$csrfToken;?>" />
                    <button class="btn btn-primary" type="submit" name="submit"><?=Language::show('change_my_password_button', 'Auth'); ?></button>
                </div>

            </form>
        </div>
      </div>
    </div>
  </div>
