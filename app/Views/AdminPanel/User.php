<?php
/**
 * Create the members view
 */

use Helpers\Form,
    Helpers\ErrorHelper,
    Helpers\SuccessHelper,
    Core\Language;

?>

<div class='col-lg-8 col-md-8 col-sm-8'>
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<h3 class='jumbotron-heading'><?php echo $data['title']." - ".$data['u_username']  ?></h3>
		</div>
		<div class='panel-body'>
			<p><?php echo $data['welcomeMessage'] ?></p>

			<?php echo Form::open(array('method' => 'post')); ?>

			<!-- User Name -->
			<div class='input-group' style='margin-bottom: 25px'>
				<span class='input-group-addon'><i class='glyphicon glyphicon-user'></i> UserName</span>
				<?php echo Form::input(array('type' => 'text', 'name' => 'au_username', 'class' => 'form-control', 'value' => $data['u_username'], 'placeholder' => 'UserName', 'maxlength' => '100')); ?>
			</div>

				<!-- First Name -->
				<div class='input-group' style='margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-user'></i> First Name</span>
					<?php echo Form::input(array('type' => 'text', 'name' => 'au_firstName', 'class' => 'form-control', 'value' => $data['u_firstName'], 'placeholder' => 'First Name', 'maxlength' => '100')); ?>
				</div>

				<!-- Email -->
				<div class='input-group' style='margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-envelope'></i> Email</span>
					<?php echo Form::input(array('type' => 'text', 'name' => 'au_email', 'class' => 'form-control', 'value' => $data['u_email'], 'placeholder' => 'Email Address', 'maxlength' => '100')); ?>
				</div>

				<!-- Gender -->
				<div class='input-group' style='margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-grain'></i> Gender</span>
					<select class='form-control' id='gender' name='au_gender'>
				    <option value='Male' <?php if($data['u_gender'] == "Male"){echo "SELECTED";}?> >Male</option>
				    <option value='Female' <?php if($data['u_gender'] == "Female"){echo "SELECTED";}?> >Female</option>
				  </select>
				</div>

				<!-- Website -->
				<div class='input-group' style='margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> Website</span>
					<?php echo Form::input(array('type' => 'text', 'name' => 'au_website', 'class' => 'form-control', 'value' => $data['u_website'], 'placeholder' => 'Website URL', 'maxlength' => '100')); ?>
				</div>

				<!-- Profile Image -->
				<div class='input-group' style='margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-picture'></i> Profile Image URL</span>
					<?php echo Form::input(array('type' => 'text', 'name' => 'au_userImage', 'class' => 'form-control', 'value' => $data['u_userImage'], 'placeholder' => 'Profile Image URL', 'maxlength' => '255')); ?>
				</div>

				<!-- About Me -->
				<div class='input-group' style='margin-bottom: 25px'>
					<span class='input-group-addon'><i class='glyphicon glyphicon-book'></i> About Me</span>
					<?php echo Form::textBox(array('type' => 'text', 'name' => 'au_aboutme', 'class' => 'form-control', 'value' => $data['u_aboutme'], 'placeholder' => 'About Me', 'rows' => '6')); ?>
				</div>

				<!-- CSRF Token -->
				<input type="hidden" name="token_user" value="<?php echo $data['csrfToken']; ?>" />
				<input type="hidden" name="au_id" value="<?php echo $data['u_id']; ?>" />
        <input type="hidden" name="update_profile" value="true" />
				<button class="btn btn-md btn-success" name="submit" type="submit">
					<?php // echo Language::show('update_profile', 'Auth'); ?>
					Update Profile
				</button>
			<?php echo Form::close(); ?>

		</div>
	</div>
</div>
