<?php
/**
* Account Change Password View
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
			<?php echo Form::open(array('method' => 'post')); ?>
				<!-- Current Password -->
				<div class='form-group'>
					<div class='form-group'>
						<div class='input-group mb-3'>
							<div class='input-group-prepend'>
								<span class='input-group-text'><i class='fas fa-lock'></i></span>
							</div>
							<?php echo Form::input(array('type' => 'password', 'name' => 'currpassword', 'class' => 'form-control', 'placeholder' => Language::show('current_password', 'Members'))); ?>
						</div>
					</div>
				</div>

				<!-- Password 1 -->
				<div class='form-group'>
					<div class='form-group'>
						<div class='input-group mb-3'>
							<div class='input-group-prepend'>
								<span class='input-group-text'><i class='fas fa-lock'></i></span>
							</div>
							<?php echo Form::input(array('id' => 'passwordInput', 'type' => 'password', 'name' => 'password', 'class' => 'form-control', 'placeholder' => Language::show('new_password', 'Members'))); ?>
							<span id='password01' class='input-group-text'></span>
						</div>
					</div>
				</div>

				<!-- Password 2 -->
				<div class='form-group'>
					<div class='form-group'>
						<div class='input-group mb-3'>
							<div class='input-group-prepend'>
								<span class='input-group-text'><i class='fas fa-lock'></i></span>
							</div>
							<?php echo Form::input(array('id' => 'confirmPasswordInput', 'type' => 'password', 'name' => 'passwordc', 'class' => 'form-control', 'placeholder' => Language::show('confirm_new_password', 'Members'))); ?>
							<span id='password02' class='input-group-text'></span>
						</div>
					</div>
				</div>

				<!-- Display Live Password Status -->
				<span class='label' id='passwordStrength'></span>

				<!-- CSRF Token -->
				<input type="hidden" name="token_changepassword" value="<?=$csrfToken;?>" />
				<button class="btn btn-md btn-success" name="submit" type="submit">
					<?=Language::show('change_password_button', 'Members');?>
				</button>
			<?php echo Form::close(); ?>
    </div>
  </div>
</div>
