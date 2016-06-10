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
				<table class='table table-striped table-hover responsive'>
					<tr><th align='left'>Setting</th><th align='right'>Enable</th></tr>
					<tr>
						<td align='left'>Allow Admins To Send me Mass Emails</td>
						<td align='right'><input type='checkbox' id='pme' name='privacy_massemail' value='true' <?=$pme_checked?>></td>
					</tr>
					<tr>
						<td align='left'>Send Email Notification When User Sends Me a Private Message</td>
						<td align='right'><input type='checkbox' id='ppm' name='privacy_pm' value='true' <?=$ppm_checked?>></td>
					</tr>
				</table>
				<input type="hidden" name="token_editprivacy" value="<?= $data['csrfToken']; ?>" />
				<input type="submit" name="submit" class="btn btn-success" value="Update My Privacy Settings">
			<?php echo Form::close(); ?>
    </div>
  </div>
</div>
