<?php
/**
* Admin Panel Advanced Settings View
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
    			Site Registration Settings
          <?php echo PageFunctions::displayPopover('Site Registration Settings', 'Site Registration Settings are used to set the security levels for the Registration page.', false, 'btn btn-sm btn-light'); ?>
    		</div>
    		<div class='card-body'>
    			<p><?php echo $data['welcomeMessage'] ?></p>

    			<?php echo Form::open(array('method' => 'post')); ?>

          <!-- Site Activation -->
      	  <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> New User Account Activation</span>
            </div>
            <input type="checkbox" class='form-control' id='site_user_activation' name='site_user_activation' data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="warning" value="true" <?php if($site_user_activation == "true"){echo "CHECKED";}?> >
            <?php echo PageFunctions::displayPopover('New User Account Activation', 'Default: Disabled - Requires new users to confirm their account via E-Mail activation link.', true, 'input-group-text'); ?>
    			</div>

    			<!-- Site Invite Code -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Site Invitation Code</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_user_invite_code', 'class' => 'form-control', 'value' => $site_user_invite_code, 'placeholder' => 'Site Invitation Code', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Site Invitation Code', 'Default: blank - Requires new users to use correct Invitation Code to Register for site.  Site does not require if left blank.', true, 'input-group-text'); ?>
    			</div>

          <!-- Min Username Length -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Min Username Length</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'min_username_length', 'class' => 'form-control', 'value' => $min_username_length, 'placeholder' => 'Min Username Length', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Minimum Username Length', 'Default: 5 - Minimum character length for Usernames.', true, 'input-group-text'); ?>
          </div>

          <!-- Max Username Length -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Max Username Length</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'max_username_length', 'class' => 'form-control', 'value' => $max_username_length, 'placeholder' => 'Max Username Length', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Maximum Username Length', 'Default: 30 - Maximum character length for Usernames.', true, 'input-group-text'); ?>
          </div>

          <!-- Min Username Length -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Min Password Length</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'min_password_length', 'class' => 'form-control', 'value' => $min_password_length, 'placeholder' => 'Min Password Length', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Minimum Password Length', 'Default: 5 - Minimum character length for Passwords.', true, 'input-group-text'); ?>
          </div>

          <!-- Max Username Length -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Max Password Length</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'max_password_length', 'class' => 'form-control', 'value' => $max_password_length, 'placeholder' => 'Max Password Length', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Maximum Password Length', 'Default: 30 - Maximum character length for Passwords.', true, 'input-group-text'); ?>
          </div>

          <!-- Min Email Length -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Min Email Length</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'min_email_length', 'class' => 'form-control', 'value' => $min_email_length, 'placeholder' => 'Min Email Address Length', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Minimum Email Length', 'Default: 5 - Minimum character length for Email Addresses.', true, 'input-group-text'); ?>
          </div>

          <!-- Max Email Length -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Max Email Length</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'max_email_length', 'class' => 'form-control', 'value' => $max_email_length, 'placeholder' => 'Max Email Address Length', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Maximum Email Length', 'Default: 100 - Maximum character length for Email Addresses.', true, 'input-group-text'); ?>
          </div>

          <!-- New User Activation Token -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Activation Token Length</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'random_key_length', 'class' => 'form-control', 'value' => $random_key_length, 'placeholder' => 'Activation Token Length', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Account Activation Token Length', 'Default: 15 - Character length for tokens that are generated for new users when required to activate via email.', true, 'input-group-text'); ?>
          </div>

        </div>
      </div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
      <div class='card mb-3'>
        <div class='card-header h4'>
          Site User Login Settings
          <?php echo PageFunctions::displayPopover('Site User Settings', 'Site User Settings are used to set the security levels for the Login page.', false, 'btn btn-sm btn-light'); ?>
        </div>
        <div class='card-body'>
          <!-- Max Login Attempts -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Max Login Attempts</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'max_attempts', 'class' => 'form-control', 'value' => $max_attempts, 'placeholder' => 'Max Login Attempts', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Max Login Attempts', 'Default: 5 - Sets total number of login attempts before user is locked for a set time.', true, 'input-group-text'); ?>
          </div>

          <!-- Failed Login Attempts Block Time -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Block Failed Login User Duration in Minutes</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'security_duration', 'class' => 'form-control', 'value' => $security_duration, 'placeholder' => 'Block Failed Login User Duration in Minutes', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Block Failed Login User Duration in Minutes', 'Default: 5 - Sets amount of Minutes user is blocked from being able to login.', true, 'input-group-text'); ?>
          </div>

          <!-- Basic User Session Duration -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Basic User Session Duration in Days</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'session_duration', 'class' => 'form-control', 'value' => $session_duration, 'placeholder' => 'How Many Days a User Stays Logged In', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Basic User Session Duration in Days', 'Default: 1 - Sets amount of Days users stay logged in to a basic session.', true, 'input-group-text'); ?>
          </div>

          <!-- Remember Me User Session Duration -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Remember Me Session Duration in Months</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'session_duration_rm', 'class' => 'form-control', 'value' => $session_duration_rm, 'placeholder' => 'How Many Months a User Stays Logged In', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Remember Me Session Duration in Months', 'Default: 1 - Sets amount of Months users stay logged in when they check Remember Me.', true, 'input-group-text'); ?>
          </div>

        </div>
      </div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
      <div class='card mb-3'>
        <div class='card-header h4'>
          Members Settings
          <?php echo PageFunctions::displayPopover('Members Settings', 'Site Members Settings allows admin to edit members settings site wide.', false, 'btn btn-sm btn-light'); ?>
        </div>
        <div class='card-body'>
          <!-- Site Activation -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Online Bubble</span>
            </div>
            <input type="checkbox" class='form-control' id='online_bubble' name='online_bubble' data-toggle="toggle" data-on="Enabled" data-off="Disabled" data-onstyle="success" data-offstyle="warning" value="true" <?php if($online_bubble == "true"){echo "CHECKED";}?> >
            <?php echo PageFunctions::displayPopover('Online Bubble', 'Default: Enabled - When Enabled a small bubble displays next to each username with online status. Green = Online. Red = Offline.', true, 'input-group-text'); ?>
          </div>
        </div>
      </div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
      <div class='card mb-3'>
        <div class='card-header h4'>
          Site Time Zone Settings
          <?php echo PageFunctions::displayPopover('Time Zone Settings', 'Site Time Zone Settings are used to set default Time Zone settings.', false, 'btn btn-sm btn-light'); ?>
        </div>
        <div class='card-body'>

          <!-- Site Default Time Zone -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Default Time Zone</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'default_timezone', 'class' => 'form-control', 'value' => $default_timezone, 'placeholder' => 'Default Time Zone', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Default Site Time Zone', 'Default: America/Chicago - Default Site Time Zone. There is a list of time zones in the correct format on https://www.php.net/manual/en/timezones.php', true, 'input-group-text'); ?>
          </div>

        </div>
      </div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
      <div class='card mb-3'>
        <div class='card-header h4'>
          Paginator Limits
          <?php echo PageFunctions::displayPopover('Site Paginator Settings', 'Site Paginator Settings are used to set limits on Member pages and Friends pages if installed.', false, 'btn btn-sm btn-light'); ?>
        </div>
        <div class='card-body'>


          <!-- Members Paginator Limit -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Members Paginator</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'users_pageinator_limit', 'class' => 'form-control', 'value' => $users_pageinator_limit, 'placeholder' => 'Members Paginator Limit', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Members Paginator Limit', 'Default: 20 - How many Members to list per page on Members Pages.', true, 'input-group-text'); ?>
          </div>

          <?php
          /** Check to see if Friends Plugin is installed, if it is show link **/
          if(file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php')){
          ?>
          <!-- Friends Paginator Limit -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Friends Paginator</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'friends_pageinator_limit', 'class' => 'form-control', 'value' => $friends_pageinator_limit, 'placeholder' => 'Friends Paginator Limit', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Friends Paginator Limit', 'Default: 20 - How many Friends to list per page on Friends Pages.', true, 'input-group-text'); ?>
          </div>
          <?php } ?>

        </div>
      </div>
    </div>

    <?php
    /** Check to see if Private Message Plugin is installed, if it is show link **/
    if(file_exists(ROOTDIR.'app/Plugins/Messages/Controllers/Messages.php')){
    ?>

    <div class='col-lg-12 col-md-12 col-sm-12'>
      <div class='card mb-3'>
        <div class='card-header h4'>
          Messages Plugin Settings
          <?php echo PageFunctions::displayPopover('Messages Plugin Settings', 'Messages Plugin Settings are used to set user limits for private messages.', false, 'btn btn-sm btn-light'); ?>
        </div>
        <div class='card-body'>

          <!-- Messages Quota -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Messages Quota</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'message_quota_limit', 'class' => 'form-control', 'value' => $message_quota_limit, 'placeholder' => 'Messages Quota', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Messages Quota', 'Default: 50 - Messages Quota Limits how many messages each user can have in their Inbox.', true, 'input-group-text'); ?>
          </div>

          <!-- Messages Paginator Limit -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Messages Paginator Limit</span>
            </div>
            <?php echo Form::input(array('type' => 'text', 'name' => 'message_pageinator_limit', 'class' => 'form-control', 'value' => $message_pageinator_limit, 'placeholder' => 'Messages Paginator Limit', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Messages Paginator Limit', 'Default: 10 - How many Messages to list per page on Messages Pages.', true, 'input-group-text'); ?>
          </div>

        </div>
      </div>
    </div>

  <?php } ?>

  <div class='col-lg-12 col-md-12 col-sm-12'>
    <div class='card mb-3'>
      <div class='card-header h4'>
        Sweets Settings
        <?php echo PageFunctions::displayPopover('Sweet Settings', 'Sweet Settings are used to set the title of all Sweets within the site.', false, 'btn btn-sm btn-light'); ?>
      </div>
      <div class='card-body'>

        <!-- Sweets Title -->
        <div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
            <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Sweets Title Display</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'sweet_title_display', 'class' => 'form-control', 'value' => $sweet_title_display, 'placeholder' => 'Sweets Title', 'maxlength' => '255')); ?>
          <?php echo PageFunctions::displayPopover('Sweets Title Display', 'Default: Sweets - Text shown on sweets count displays. EX: Likes/+1s/Hearts', true, 'input-group-text'); ?>
        </div>
        <div style='margin-bottom: 25px'>

        </div>

        <!-- Sweets Button -->
        <div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
            <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Sweets Button Display</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'sweet_button_display', 'class' => 'form-control', 'value' => $sweet_button_display, 'placeholder' => 'Sweets Button', 'maxlength' => '255')); ?>
          <?php echo PageFunctions::displayPopover('Sweets Button Display', 'Default: Sweet - Text shown on Button for sweets. EX: Like/+1/Heart', true, 'input-group-text'); ?>
        </div>

      </div>
    </div>
  </div>

  <div class='col-lg-12 col-md-12 col-sm-12'>
    <div class='card mb-3'>
      <div class='card-header h4'>
        Max Profile Image Size
        <?php echo PageFunctions::displayPopover('Max Profile Image Settings', 'Max Profile Image Settings are used to set the max image size once converted for site.', false, 'btn btn-sm btn-light'); ?>
      </div>
      <div class='card-body'>
        <!-- Max Image Size when uploaded to server -->
        <div class='input-group mb-3'>
          <div class='input-group-prepend'>
            <span class='input-group-text' id='basic-addon1'><i class='fa fa-fw fa-image'></i> Max Image Size</span>
          </div>
          <select class='form-control' id='image_max_size' name='image_max_size'>
            <option value='240,160' <?php if($data['image_max_size'] == "240,160"){echo "SELECTED";}?> >240 x 160</option>
            <option value='320,240' <?php if($data['image_max_size'] == "320,240"){echo "SELECTED";}?> >320 x 160</option>
            <option value='460,309' <?php if($data['image_max_size'] == "460,309"){echo "SELECTED";}?> >460 x 309</option>
            <option value='800,600' <?php if($data['image_max_size'] == "800,600"){echo "SELECTED";}?> >800 x 600</option>
            <option value='1024,768' <?php if($data['image_max_size'] == "1024,768"){echo "SELECTED";}?> >1024 x 768</option>
            <option value='1920,1080' <?php if($data['image_max_size'] == "1920,1080"){echo "SELECTED";}?> >1920 x 1080</option>
          </select>
          <?php echo PageFunctions::displayPopover('Max Profile Image Size', 'Default: 800x600 - Select the default image max resize limit.  The larger the size, the larger the file. Used for User Images, but can be used elsewhere if needed.', true, 'input-group-text'); ?>
        </div>

      </div>
    </div>
  </div>



    <div class='col-lg-12 col-md-12 col-sm-12'>
        <button class="btn btn-md btn-success" name="submit" type="submit">
            Update Site Advanced Settings
        </button>
        <!-- CSRF Token and What is Being Updated -->
        <input type="hidden" name="token_settings" value="<?php echo $data['csrfToken']; ?>" />
        <input type="hidden" name="update_advanced_settings" value="true" />
        <?php echo Form::close(); ?><Br><br>
    </div>
  </div>
</div>
