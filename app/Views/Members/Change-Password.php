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
					<?php echo Form::input(array('type' => 'password', 'name' => 'currpassword', 'class' => 'form-control', 'placeholder' => 'Current Password')); ?>
				</div>

				<!-- Password 1 -->
				<div class='input-group' style='width: 80%; margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-lock'></i></span>
					<?php echo Form::input(array('id' => 'passwordInput', 'type' => 'password', 'name' => 'password', 'class' => 'form-control', 'placeholder' => 'Password')); ?>
					<span id='password01' class='input-group-addon'></span>
				</div>

				<!-- Password 2 -->
				<div class='input-group' style='width: 80%; margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-lock'></i></span>
					<?php echo Form::input(array('id' => 'confirmPasswordInput', 'type' => 'password', 'name' => 'passwordc', 'class' => 'form-control', 'placeholder' => 'Confirm Password')); ?>
					<span id='password02' class='input-group-addon'></span>
				</div>

				<!-- Display Live Password Status -->
				<span class='label' id='passwordStrength'></span>

				<!-- CSRF Token -->
				<input type="hidden" name="token_changepassword" value="<?= $data['csrfToken']; ?>" />
				<button class="btn btn-md btn-success" name="submit" type="submit">
					Change my Password
				</button>
			<?php echo Form::close(); ?>
    </div>
  </div>
</div>
