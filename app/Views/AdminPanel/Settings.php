<?php
/**
* Admin Panel Settings View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/
use Libs\Form,
    Libs\ErrorMessages,
    Libs\SuccessMessages,
    Libs\Language;

?>
<div class='col-lg-12 col-md-12 col-sm-12'>
  <div class='row'>
    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='panel panel-default'>
    		<div class='panel-heading'>
    			<h3 class='jumbotron-heading'><?php echo $data['title'];  ?></h3>
    		</div>
    		<div class='panel-body'>
    			<p><?php echo $data['welcomeMessage'] ?></p>

    			<?php echo Form::open(array('method' => 'post')); ?>

    			<!-- Site Title -->
    			<div class='input-group' style='margin-bottom: 25px'>
    				<span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> Site Title</span>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_title', 'class' => 'form-control', 'value' => $site_title, 'placeholder' => 'Site Title', 'maxlength' => '255')); ?>
    			</div>

                <!-- Site Description -->
                <div class='input-group' style='margin-bottom: 25px'>
                    <span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> Site Description</span>
                    <?php echo Form::input(array('type' => 'text', 'name' => 'site_description', 'class' => 'form-control', 'value' => $site_description, 'placeholder' => 'Site Description' , 'maxlength' => '255')); ?>
                </div>

                <!-- Site Keywords -->
    			<div class='input-group' style='margin-bottom: 25px'>
    				<span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> Site Keywords</span>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_keywords', 'class' => 'form-control', 'value' => $site_keywords, 'placeholder' => 'Site Keywords', 'maxlength' => '255')); ?>
    			</div>

    				<!-- CSRF Token -->
    				<input type="hidden" name="token_settings" value="<?php echo $data['csrfToken']; ?>" />
                    <input type="hidden" name="update_settings" value="true" />

            </div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='panel panel-default'>
    		<div class='panel-heading'>
    			<h3 class='jumbotron-heading'>Site User Account Settings</h3>
    		</div>
    		<div class='panel-body'>
                Site User Account Settings.

                <!-- User Account Activation -->
    			<div class='input-group' style='margin-bottom: 25px'>
    				<span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> User Account Activation</span>
                    <select class='form-control' id='gender' name='site_user_activation'>
                        <option value='true' <?php if($site_user_activation == "true"){echo "SELECTED";}?> >E-Mail Activation Required</option>
                        <option value='false' <?php if($site_user_activation == "false"){echo "SELECTED";}?> >No E-Mail Activation Required</option>
                    </select>
    			</div>

    		</div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='panel panel-default'>
    		<div class='panel-heading'>
    			<h3 class='jumbotron-heading'>Site E-Mail Settings</h3>
    		</div>
    		<div class='panel-body'>
                Site E-Mail Settings and stuff.

                <!-- E-Mail Username -->
    			<div class='input-group' style='margin-bottom: 25px'>
    				<span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> E-Mail Username</span>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_email_username', 'class' => 'form-control', 'value' => $site_email_username, 'placeholder' => 'E-Mail Username', 'maxlength' => '100')); ?>
    			</div>

                <!-- E-Mail Password -->
    			<div class='input-group' style='margin-bottom: 25px'>
    				<span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> E-Mail Password</span>
    				<?php echo Form::input(array('type' => 'password', 'name' => 'site_email_password', 'class' => 'form-control', 'value' => $site_email_password, 'placeholder' => 'E-Mail Password', 'maxlength' => '100')); ?>
    			</div>

                <!-- E-Mail From Name -->
    			<div class='input-group' style='margin-bottom: 25px'>
    				<span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> E-Mail From Name</span>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_email_fromname', 'class' => 'form-control', 'value' => $site_email_fromname, 'placeholder' => 'E-Mail From Name', 'maxlength' => '100')); ?>
    			</div>

                <!-- E-Mail Host -->
                <div class='input-group' style='margin-bottom: 25px'>
                    <span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> E-Mail Host</span>
                    <?php echo Form::input(array('type' => 'text', 'name' => 'site_email_host', 'class' => 'form-control', 'value' => $site_email_host, 'placeholder' => 'E-Mail Host Address', 'maxlength' => '100')); ?>
                </div>

                <!-- E-Mail Port -->
                <div class='input-group' style='margin-bottom: 25px'>
                    <span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> E-Mail Host Port</span>
                    <?php echo Form::input(array('type' => 'text', 'name' => 'site_email_port', 'class' => 'form-control', 'value' => $site_email_port, 'placeholder' => 'E-Mail Host Port', 'maxlength' => '100')); ?>
                </div>

                <!-- E-Mail SMTP Auth Type -->
                <div class='input-group' style='margin-bottom: 25px'>
                    <span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> E-Mail SMTP Auth Type</span>
                    <?php echo Form::input(array('type' => 'text', 'name' => 'site_email_smtp', 'class' => 'form-control', 'value' => $site_email_smtp, 'placeholder' => 'E-Mail SMTP Auth Type', 'maxlength' => '100')); ?>
                </div>

                <!-- E-Mail Site Address -->
                <div class='input-group' style='margin-bottom: 25px'>
                    <span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> E-Mail Site Address</span>
                    <?php echo Form::input(array('type' => 'text', 'name' => 'site_email_site', 'class' => 'form-control', 'value' => $site_email_site, 'placeholder' => 'E-Mail Site Address', 'maxlength' => '100')); ?>
                </div>

    		</div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='panel panel-default'>
    		<div class='panel-heading'>
    			<h3 class='jumbotron-heading'>Site reCAPCHA Settings</h3>
    		</div>
    		<div class='panel-body'>
                Site reCAPCHA Settings.

                <!-- reCAPCHA Public Key -->
    			<div class='input-group' style='margin-bottom: 25px'>
    				<span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> reCAPCHA Public Key</span>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_recapcha_public', 'class' => 'form-control', 'value' => $site_recapcha_public, 'placeholder' => 'reCAPCHA Public Key', 'maxlength' => '100')); ?>
    			</div>

                <!-- reCAPCHA Private Key -->
    			<div class='input-group' style='margin-bottom: 25px'>
    				<span class='input-group-addon'><i class='glyphicon glyphicon-globe'></i> reCAPCHA Private Key</span>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_recapcha_private', 'class' => 'form-control', 'value' => $site_recapcha_private, 'placeholder' => 'reCAPCHA Private Key', 'maxlength' => '100')); ?>
    			</div>

    		</div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
        <button class="btn btn-md btn-success" name="submit" type="submit">
            <?php // echo Language::show('update_profile', 'Auth'); ?>
            Update Site Settings
        </button>
        <?php echo Form::close(); ?><Br><br>
    </div>
  </div>
</div>
