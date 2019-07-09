<?php
/**
* Admin Panel Settings View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/
use Libs\Form,
    Libs\ErrorMessages,
    Libs\SuccessMessages,
    Libs\Language,
    Libs\PageFunctions;

?>
<div class='col-lg-12 col-md-12 col-sm-12'>
  <div class='row'>
    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			<?php echo $data['title'];  ?>
          <?php echo PageFunctions::displayPopover('Site Email Settings', 'Site Email Settings are used for the site to send emails to site users for activation, notifications, password recovery, etc. The system connects to a smtp server to send emails to users.  Fill in the settings below based on what your smtp server requires', false, 'btn btn-sm btn-light'); ?>
    		</div>
    		<div class='card-body'>
    			<p><?php echo $data['welcomeMessage'] ?></p>

    			<?php echo Form::open(array('method' => 'post')); ?>

          <!-- E-Mail Username -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> E-Mail Username</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_email_username', 'class' => 'form-control', 'value' => $site_email_username, 'placeholder' => 'E-Mail Username', 'maxlength' => '100')); ?>
            <?php echo PageFunctions::displayPopover('E-Mail Server Username', 'Enter the username for the email server this site will connect to and send emails.', true, 'input-group-text'); ?>
    			</div>

          <!-- E-Mail Password -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> E-Mail Password</span>
            </div>
    				<?php echo Form::input(array('type' => 'password', 'name' => 'site_email_password', 'class' => 'form-control', 'value' => $site_email_password, 'placeholder' => 'E-Mail Password', 'maxlength' => '100')); ?>
            <?php echo PageFunctions::displayPopover('E-Mail Server Password', 'Enter the password for the email server this site will connect to and send emails.', true, 'input-group-text'); ?>
    			</div>

          <!-- E-Mail From Name -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
      				<span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> E-Mail From Name</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_email_fromname', 'class' => 'form-control', 'value' => $site_email_fromname, 'placeholder' => 'E-Mail From Name', 'maxlength' => '100')); ?>
            <?php echo PageFunctions::displayPopover('E-Mail From Name', 'E-Mail From Name will display this name you chose with from email. ex: My Website No Reply', true, 'input-group-text'); ?>
    			</div>

          <!-- E-Mail Host -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> E-Mail Host</span>
            </div>
              <?php echo Form::input(array('type' => 'text', 'name' => 'site_email_host', 'class' => 'form-control', 'value' => $site_email_host, 'placeholder' => 'E-Mail Host Address', 'maxlength' => '100')); ?>
              <?php echo PageFunctions::displayPopover('E-Mail Server Host', 'E-Mail Server Host is the smtp server address that this site will connect to and send emails from this site.', true, 'input-group-text'); ?>
          </div>

          <!-- E-Mail Port -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> E-Mail Host Port</span>
            </div>
              <?php echo Form::input(array('type' => 'text', 'name' => 'site_email_port', 'class' => 'form-control', 'value' => $site_email_port, 'placeholder' => 'E-Mail Host Port', 'maxlength' => '100')); ?>
              <?php echo PageFunctions::displayPopover('E-Mail Server Host Port', 'E-Mail Server Host Port is the smtp server port address that this site will connect to and send emails from this site.', true, 'input-group-text'); ?>
          </div>

          <!-- E-Mail SMTP Auth Type -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> E-Mail SMTP Auth Type</span>
            </div>
              <?php echo Form::input(array('type' => 'text', 'name' => 'site_email_smtp', 'class' => 'form-control', 'value' => $site_email_smtp, 'placeholder' => 'E-Mail SMTP Auth Type', 'maxlength' => '100')); ?>
              <?php echo PageFunctions::displayPopover('E-Mail SMTP Server Auty Type', 'E-Mail SMTP Server Auth Type is the protocol used to connect securely to the smtp server.  For example: tls ', true, 'input-group-text'); ?>
          </div>

          <!-- E-Mail Site Address -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> E-Mail Site Address</span>
            </div>
              <?php echo Form::input(array('type' => 'text', 'name' => 'site_email_site', 'class' => 'form-control', 'value' => $site_email_site, 'placeholder' => 'E-Mail Site Address', 'maxlength' => '100')); ?>
              <?php echo PageFunctions::displayPopover('E-Mail Site Address', 'E-Mail Site Address is the E-Mail address that users can reply to when they are sent emails from this site.', true, 'input-group-text'); ?>
          </div>

    		</div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
        <button class="btn btn-md btn-success" name="submit" type="submit">
            <?php // echo Language::show('update_profile', 'Auth'); ?>
            Update Site E-Mail Settings
        </button>
        <!-- CSRF Token and What is Being Updated -->
        <input type="hidden" name="token_settings" value="<?php echo $data['csrfToken']; ?>" />
        <input type="hidden" name="update_settings" value="true" />
        <?php echo Form::close(); ?><Br><br>
    </div>
  </div>
</div>
