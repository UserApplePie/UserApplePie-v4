<?php
/**
* Account Privacy Settings View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

use Libs\Language, Libs\Form;
?>

<div class="col-lg-8 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$title;?>
		</div>
		<div class="card-body">
			<p><?=$welcomeMessage;?></p>
			<hr>
			<h3><?=Language::show('ps_email_settings', 'Members'); ?></h3>
			<hr>
			<?php echo Form::open(array('method' => 'post')); ?>
				<table class='table table-striped table-hover responsive'>
					<tr><th align='left'><?=Language::show('ps_setting', 'Members'); ?></th><th align='right'><?=Language::show('ps_enable', 'Members'); ?></th></tr>
					<tr>
						<td align='left'><?=Language::show('ps_admin_mail', 'Members'); ?></td>
						<td align='right'><input type='checkbox' id='pme' name='privacy_massemail' value='true' <?=$pme_checked?>></td>
					</tr>
					<tr>
						<td align='left'><?=Language::show('ps_pm_mail', 'Members'); ?></td>
						<td align='right'><input type='checkbox' id='ppm' name='privacy_pm' value='true' <?=$ppm_checked?>></td>
					</tr>
				</table>
				<input type="hidden" name="token_editprivacy" value="<?=$csrfToken;?>" />
				<input type="submit" name="submit" class="btn btn-success" value="<?=Language::show('ps_button', 'Members'); ?>">
			<?php echo Form::close(); ?>
    </div>
  </div>
</div>
