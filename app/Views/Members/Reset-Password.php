<?php
/**
* Account Reset Password View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
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
                        <label class="control-label">New Password</label>
                        <input  class="form-control" type="password" id="password" name="password" placeholder="New Password">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Confirm New Password</label>
                        <input  class="form-control" type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm New Password">
                    </div>
                    <input type="hidden" name="token_resetpassword" value="<?=$csrfToken;?>" />
                    <button class="btn btn-primary" type="submit" name="submit">Change my Password</button>
                </div>

            </form>
        </div>
      </div>
    </div>
  </div>
