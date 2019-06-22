<?php
/**
* Account Settings View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language;
?>

<div class="col-lg-9 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$title;?>
		</div>
		<div class="card-body">
			<p><?=$welcomeMessage;?></p>
			<hr>
			<a href='<?=DIR?>Edit-Profile' rel='nofollow'><?=Language::show('mem_act_edit_profile', 'Members'); ?></a><br>
			<?=Language::show('mem_act_edit_profile_description', 'Members'); ?>
			<hr>
			<a href='<?=DIR?>Edit-Profile-Images' rel='nofollow'><?=Language::show('mem_act_edit_profile_images', 'Members'); ?></a><br>
			<?=Language::show('mem_act_edit_profile_images_description', 'Members'); ?>
			<hr>
			<a href='<?=DIR?>Change-Email' rel='nofollow'><?=Language::show('mem_act_change_email', 'Members'); ?></a><br>
			<?=Language::show('mem_act_change_email_description', 'Members'); ?>
			<hr>
			<a href='<?=DIR?>Change-Password' rel='nofollow'><?=Language::show('mem_act_change_pass', 'Members'); ?></a><br>
			<?=Language::show('mem_act_change_pass_description', 'Members'); ?>
			<hr>
			<a href='<?=DIR?>Privacy-Settings' rel='nofollow'><?=Language::show('mem_act_privacy_settings', 'Members'); ?></a><br>
			<?=Language::show('mem_act_privacy_settings_description', 'Members'); ?>

    </div>
  </div>
</div>
