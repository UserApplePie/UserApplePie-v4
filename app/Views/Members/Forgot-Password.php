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
                  <label class="control-label">Email</label>
                  <input  class="form-control" type="email" id="email" name="email" placeholder="Email">
              </div>
              <input type="hidden" name="token_forgotpassword" value="<?= $data['csrfToken']; ?>" />
              <button class="btn btn-primary" type="submit" name="submit">Send Password Reset Email</button>
          </div>
      </form>

    </div>
  </div>
</div>
