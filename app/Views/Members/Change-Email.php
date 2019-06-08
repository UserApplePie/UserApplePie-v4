<?php
/**
* Account Change E-Mail View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language, Libs\Form;
?>

<div class="col-lg-9 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$title;?>
		</div>
		<div class="card-body">
			<p><?=$welcomeMessage;?></p>
			<?php echo Form::open(array('method' => 'post')); ?>

				<!-- Current Password -->
				<div class='form-group'>
						<div class='input-group mb-3'>
							<div class='input-group-prepend'>
								<span class='input-group-text'><i class='fas fa-lock'></i></span>
							</div>
							<?php echo Form::input(array('type' => 'password', 'name' => 'passwordemail', 'class' => 'form-control', 'placeholder' => Language::show('current_password', 'Members'))); ?>
						</div>
				</div>

				<!-- Email -->
				<div class='form-group'>
						<div class='input-group mb-3'>
							<div class='input-group-prepend'>
								<span class='input-group-text'><i class='fas fa-envelope'></i></span>
							</div>
							<?php echo Form::input(array('id' => 'email', 'type' => 'text', 'name' => 'email', 'class' => 'form-control', 'placeholder' => $data['email'])); ?>
							<div class='input-group-prepend'>
								<span id='resultemail' class='input-group-text'></span>
							</div>
						</div>
				</div>

				<!-- Error Message Display -->
				<span id='resultemail2' class='label'></span>

				<!-- CSRF Token -->
				<input type="hidden" name="token_changeemail" value="<?php echo $data['csrfToken']; ?>" />
				<button class="btn btn-md btn-success" name="submit" type="submit">
					<?=Language::show('change_email_button', 'Members'); ?>
				</button>
			<?php echo Form::close(); ?>
    </div>
  </div>
</div>
