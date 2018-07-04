<?php
/**
* Account Registration View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
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

			<?php echo Form::open(array('method' => 'post')); ?>

				<!-- Username -->
				<div class='form-group'>
					<div class='form-group'>
						<div class='input-group mb-3'>
							<div class='input-group-prepend'>
								<span class='input-group-text'><i class='fas fa-user'></i></span>
							</div>
							<?php echo Form::input(array('id' => 'username', 'name' => 'username', 'class' => 'form-control', 'placeholder' => Language::show('register_field_username', 'Auth'))); ?>
							<div class='input-group-append'>
								<span id='resultun' class='input-group-text'></span>
							</div>
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
							<?php echo Form::input(array('id' => 'passwordInput', 'type' => 'password', 'name' => 'password', 'class' => 'form-control', 'placeholder' => Language::show('register_field_password', 'Auth'))); ?>
							<div class='input-group-append'>
								<span id='password01' class='input-group-text'></span>
							</div>
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
							<?php echo Form::input(array('id' => 'confirmPasswordInput', 'type' => 'password', 'name' => 'passwordc', 'class' => 'form-control', 'placeholder' => Language::show('register_field_confpass', 'Auth'))); ?>
							<div class='input-group-append'>
								<span id='password02' class='input-group-text'></span>
							</div>
						</div>
					</div>
				</div>

				<!-- Email -->
				<div class='form-group'>
					<div class='form-group'>
						<div class='input-group mb-3'>
							<div class='input-group-prepend'>
								<span class='input-group-text'><i class='fas fa-envelope'></i></span>
							</div>
							<?php echo Form::input(array('id' => 'email', 'type' => 'text', 'name' => 'email', 'class' => 'form-control', 'placeholder' => Language::show('register_field_email', 'Auth'))); ?>
							<div class='input-group-append'>
								<span id='resultemail' class='input-group-text'></span>
							</div>
						</div>
					</div>
				</div>

				<!-- reCAPTCHA -->
				<div id="html_element"></div>

				<!-- CSRF Token -->
				<input type="hidden" name="token_register" value="<?=$csrfToken;?>" />

				<!-- Error Msg Display -->
				<span id='resultun2' class='label'></span>
				<span class='label' id='passwordStrength'></span>
				<span id='resultemail2' class='label'></span>

				<hr>
				<button class="btn btn-md btn-success" name="submit" type="submit">
					<?php echo Language::show('register_button', 'Auth'); ?>
				</button>
			<?php echo Form::close(); ?>

    </div>
  </div>
</div>
