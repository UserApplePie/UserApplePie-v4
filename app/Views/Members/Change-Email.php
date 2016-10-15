<?php use Libs\Language, Libs\Form; ?>

<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1><?=$title;?></h1>
		</div>
		<div class="panel-body">
			<p><?=$welcomeMessage;?></p>
			<?php echo Form::open(array('method' => 'post')); ?>

				<!-- Current Password -->
				<div class='input-group' style='width: 80%; margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-lock'></i></span>
					<?php echo Form::input(array('type' => 'password', 'name' => 'passwordemail', 'class' => 'form-control', 'placeholder' => Language::show('current_password', 'Members'))); ?>
				</div>

				<!-- Email -->
				<div class='input-group' style='width: 80%; margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-envelope'></i></span>
					<?php echo Form::input(array('id' => 'email', 'type' => 'text', 'name' => 'newemail', 'class' => 'form-control', 'placeholder' => $data['email'])); ?>
					<span id='resultemail' class='input-group-addon'></span>
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
