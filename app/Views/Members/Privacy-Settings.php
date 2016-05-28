<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1><?=$title;?></h1>
		</div>
		<div class="panel-body">
			<p><?=$welcomeMessage;?></p>
			<hr>
			<h3>Email Settings</h3>
			<hr>
			<?php echo Form::open(array('method' => 'post')); ?>
				<table class='table table-striped table-hover table-bordered responsive'>
					<tr><th class="pull-left">Setting</th><th class="pull-right">Enable</th></tr>
					<tr>
						<td class="pull-left">Allow Admins To Send me Mass Emails</td>
						<td class="pull-right"><input type='checkbox' id='pme' name='privacy_massemail' class='form-control' value='true' <?=$pme_checked?>></td>
					</tr>
					<tr>
						<td class="pull-left">Send Email Notification When User Sends Me a Private Message</td>
						<td class="pull-right"><input type='checkbox' id='ppm' name='privacy_pm' class='form-control' value='true' <?=$ppm_checked?>></td>
					</tr>
				</table>
				<input type="hidden" name="token_editprivacy" value="<?= $data['csrfToken']; ?>" />
				<input type="submit" name="submit" class="btn btn-success" value="Update My Privacy Settings">
			<?php echo Form::close(); ?>
    </div>
  </div>
</div>
