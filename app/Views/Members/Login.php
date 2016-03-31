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
                  <label class="control-label">Username or Email</label>
                  <input  class="form-control" type="text" id="username" name="username" placeholder="Username or email">
              </div>
              <div class="form-group">
                  <label class="control-label">Password</label>
                  <input class="form-control" type="password" id="password" name="password" placeholder="password">
              </div>
              <div class="form-inline">
                  <label class="control-label">Remember Me</label>
                  <input class="form-control" type="checkbox" id="rememberMe" name="rememberMe">
              </div>
              <input type="hidden" name="token_login" value="<?= $data['csrfToken']; ?>" />
              <button class="btn btn-primary" type="submit" name="submit">Login</button>
          </div>

      </form>

		</div>
		<div class="panel-footer">
					<a class="btn btn-primary btn-sm" name="" href="<?=DIR?>Register">Register</a>
					<a class="btn btn-primary btn-sm" name="" href="<?=DIR?>Forgot-Password">Forgot Password</a>
					<a class="btn btn-primary btn-sm" name="" href="<?=DIR?>Resend-Activation-Email">Resend Activation Email</a>
    </div>
  </div>
</div>
