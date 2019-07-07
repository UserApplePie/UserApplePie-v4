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
          <?php echo PageFunctions::displayPopover('Site Main Settings', 'Site Main Settings are mainly used for SEO use.', false, 'btn btn-sm btn-light'); ?>
    		</div>
    		<div class='card-body'>
    			<p>
            <?php echo $data['welcomeMessage'] ?>
          </p>

    			<?php echo Form::open(array('method' => 'post')); ?>

    			<!-- Site Title -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Site Title</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_title', 'class' => 'form-control', 'value' => $site_title, 'placeholder' => 'Site Title', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Site Title', 'Site Title is displayed throughout the site where requested. Also displays in the browser title area, and in the Navbar.', true, 'input-group-text'); ?>
    			</div>

          <!-- Site Description -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Site Description</span>
            </div>
              <?php echo Form::textarea(array('type' => 'text', 'name' => 'site_description', 'class' => 'form-control', 'value' => $site_description, 'placeholder' => 'Site Description')); ?>
              <?php echo PageFunctions::displayPopover('Site Description', 'Site Description is used in the description meta tag. Mainly used for Search Engines.', true, 'input-group-text'); ?>
          </div>

          <!-- Site Keywords -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Site Keywords</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_keywords', 'class' => 'form-control', 'value' => $site_keywords, 'placeholder' => 'Site Keywords', 'maxlength' => '255')); ?>
            <?php echo PageFunctions::displayPopover('Site Keywords', 'Site Keywords are used in the keywords meta tag. Mainly used for Search Engines.', true, 'input-group-text'); ?>
    			</div>
        </div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			Site Theme Settings
          <?php echo PageFunctions::displayPopover('Site Themes by Bootswatch', 'To see a sample of each theme, visit the Bootswatch website.', false, 'btn btn-sm btn-light'); ?>
    		</div>
    		<div class='card-body'>
          <p>
            Site Theme provided by <a href='https://bootswatch.com/' target='_blank'>Bootswatch</a>
          </p>
      	  <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Theme</span>
            </div>
            <select class='form-control' id='theme' name='site_theme'>
                <option value='default' <?php if($site_theme == "default"){echo "SELECTED";}?> >Default</option>
                <option value='cerulean' <?php if($site_theme == "cerulean"){echo "SELECTED";}?> >Cerulean</option>
                <option value='cosmo' <?php if($site_theme == "cosmo"){echo "SELECTED";}?> >Cosmo</option>
                <option value='cyborg' <?php if($site_theme == "cyborg"){echo "SELECTED";}?> >Cyborg</option>
                <option value='darkly' <?php if($site_theme == "darkly"){echo "SELECTED";}?> >Darkly</option>
                <option value='flatly' <?php if($site_theme == "flatly"){echo "SELECTED";}?> >Flatly</option>
                <option value='journal' <?php if($site_theme == "journal"){echo "SELECTED";}?> >Journal</option>
                <option value='litera' <?php if($site_theme == "litera"){echo "SELECTED";}?> >Litera</option>
                <option value='lumen' <?php if($site_theme == "lumen"){echo "SELECTED";}?> >Lumen</option>
                <option value='lux' <?php if($site_theme == "lux"){echo "SELECTED";}?> >Lux</option>
                <option value='materia' <?php if($site_theme == "materia"){echo "SELECTED";}?> >Materia</option>
                <option value='minty' <?php if($site_theme == "minty"){echo "SELECTED";}?> >Minty</option>
                <option value='pulse' <?php if($site_theme == "pulse"){echo "SELECTED";}?> >Pulse</option>
                <option value='sandstone' <?php if($site_theme == "sandstone"){echo "SELECTED";}?> >Sandstone</option>
                <option value='simplex' <?php if($site_theme == "simplex"){echo "SELECTED";}?> >Simplex</option>
                <option value='sketchy' <?php if($site_theme == "sketchy"){echo "SELECTED";}?> >Sketchy</option>
                <option value='slate' <?php if($site_theme == "slate"){echo "SELECTED";}?> >Slate</option>
                <option value='solar' <?php if($site_theme == "solar"){echo "SELECTED";}?> >Solar</option>
                <option value='spacelab' <?php if($site_theme == "spacelab"){echo "SELECTED";}?> >Spacelab</option>
                <option value='superhero' <?php if($site_theme == "superhero"){echo "SELECTED";}?> >Superhero</option>
                <option value='united' <?php if($site_theme == "united"){echo "SELECTED";}?> >United</option>
                <option value='yeti' <?php if($site_theme == "yeti"){echo "SELECTED";}?> >Yeti</option>
            </select>
            <?php echo PageFunctions::displayPopover('Site Theme', 'Site Theme changes the look and feel for the site.  It does not affect the admin panel.', true, 'input-group-text'); ?>
    			</div>
    		</div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			Site reCAPCHA Settings
          <?php echo PageFunctions::displayPopover('Google reCAPCHA', 'Visit Google reCAPCHA website to setup your keys and add security to your website.', false, 'btn btn-sm btn-light'); ?>
    		</div>
    		<div class='card-body'>
          <p>
            Site reCAPCHA Settings. <a href='https://www.google.com/recaptcha/'>Get reCAPCHA</a>
          </p>
          <!-- reCAPCHA Public Key -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> reCAPCHA Site Key</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_recapcha_public', 'class' => 'form-control', 'value' => $site_recapcha_public, 'placeholder' => 'reCAPCHA Site Key', 'maxlength' => '100')); ?>
            <?php echo PageFunctions::displayPopover('reCAPCHA Site Key', 'Google reCAPCHA Site Key for robot check.', true, 'input-group-text'); ?>
    			</div>

          <!-- reCAPCHA Private Key -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
      				<span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> reCAPCHA Secret Key</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_recapcha_private', 'class' => 'form-control', 'value' => $site_recapcha_private, 'placeholder' => 'reCAPCHA Secret Key', 'maxlength' => '100')); ?>
            <?php echo PageFunctions::displayPopover('reCAPCHA Secret Key', 'Google reCAPCHA Secret Key for robot check.', true, 'input-group-text'); ?>
    			</div>

    		</div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			Site Wide Message
          <?php echo PageFunctions::displayPopover('Site Wide Message', 'Site Wide Messages settings allows Admin to share important data with all users.', false, 'btn btn-sm btn-light'); ?>
    		</div>
    		<div class='card-body'>

          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Site Wide Message</span>
            </div>
              <?php echo Form::textarea(array('type' => 'text', 'name' => 'site_message', 'class' => 'form-control', 'value' => $site_message, 'placeholder' => 'Site Wide Message')); ?>
              <?php echo PageFunctions::displayPopover('Site Wide Message', 'This message will show to all users on the site.  Let them know about downtime or other site related messages. Info box will not show if the field below is blank.', true, 'input-group-text'); ?>
          </div>
        </div>
      </div>
    </div>


    <div class='col-lg-12 col-md-12 col-sm-12'>
        <button class="btn btn-md btn-success" name="submit" type="submit">
            <?php // echo Language::show('update_profile', 'Auth'); ?>
            Update Site Settings
        </button>
        <!-- CSRF Token and What is Being Updated -->
        <input type="hidden" name="token_settings" value="<?php echo $data['csrfToken']; ?>" />
        <input type="hidden" name="update_settings" value="true" />
        <?php echo Form::close(); ?><Br><br>
    </div>
  </div>
</div>
