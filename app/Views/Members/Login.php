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
                  <label class="control-label"><?=Language::show('login_field_username', 'Auth')?></label>
                  <input  class="form-control" type="text" id="username" name="username" placeholder="<?=Language::show('login_field_username', 'Auth')?>">
              </div>
              <div class="form-group">
                  <label class="control-label"><?=Language::show('login_field_password', 'Auth')?></label>
                  <input class="form-control" type="password" id="password" name="password" placeholder="<?=Language::show('login_field_password', 'Auth')?>">
              </div>
              <div class="form-inline">
                  <label class="control-label"><?=Language::show('login_field_rememberme', 'Auth')?></label>
                  <input class="form-control" type="checkbox" id="rememberMe" name="rememberMe">
              </div>
              <input type="hidden" name="token_login" value="<?= $data['csrfToken']; ?>" />
              <button class="btn btn-primary" type="submit" name="submit">Login</button>
          </div>

      </form>

		</div>
		<div class="panel-footer">
					<a class="btn btn-primary btn-sm" name="" href="<?=DIR?>Register"><?=Language::show('register_button', 'Auth')?></a>
					<a class="btn btn-primary btn-sm" name="" href="<?=DIR?>Forgot-Password"><?=Language::show('forgotpass_button', 'Auth')?></a>
					<a class="btn btn-primary btn-sm" name="" href="<?=DIR?>Resend-Activation-Email"><?=Language::show('resendactivation_button', 'Auth')?></a>
    </div>
  </div>
</div>
