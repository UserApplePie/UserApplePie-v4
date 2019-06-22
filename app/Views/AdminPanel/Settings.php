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
    Libs\Language;

?>
<div class='col-lg-12 col-md-12 col-sm-12'>
  <div class='row'>
    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			<h3 class='jumbotron-heading'><?php echo $data['title'];  ?></h3>
    		</div>
    		<div class='card-body'>
    			<p><?php echo $data['welcomeMessage'] ?></p>

    			<?php echo Form::open(array('method' => 'post')); ?>

    			<!-- Site Title -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Site Title</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_title', 'class' => 'form-control', 'value' => $site_title, 'placeholder' => 'Site Title', 'maxlength' => '255')); ?>
    			</div>

          <!-- Site Description -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Site Description</span>
            </div>
              <?php echo Form::textarea(array('type' => 'text', 'name' => 'site_description', 'class' => 'form-control', 'value' => $site_description, 'placeholder' => 'Site Description')); ?>
          </div>

                <!-- Site Keywords -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Site Keywords</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_keywords', 'class' => 'form-control', 'value' => $site_keywords, 'placeholder' => 'Site Keywords', 'maxlength' => '255')); ?>
    			</div>

            </div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			<h3 class='jumbotron-heading'>Site User Account Settings</h3>
    		</div>
    		<div class='card-body'>
          Site User Account Settings.
      	  <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> User Account Activation</span>
            </div>
            <select class='form-control' id='site_user_activation' name='site_user_activation'>
                <option value='true' <?php if($site_user_activation == "true"){echo "SELECTED";}?> >E-Mail Activation Required</option>
                <option value='false' <?php if($site_user_activation == "false"){echo "SELECTED";}?> >No E-Mail Activation Required</option>
            </select>
    			</div>
    		</div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			<h3 class='jumbotron-heading'>Site Theme Settings</h3>
    		</div>
    		<div class='card-body'>
          Site Theme provided by <a href='https://bootswatch.com/' target='_blank'>Bootswatch</a>.
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
    			</div>
    		</div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			<h3 class='jumbotron-heading'>Site reCAPCHA Settings</h3>
    		</div>
    		<div class='card-body'>
                Site reCAPCHA Settings.

                <!-- reCAPCHA Public Key -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
    				  <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> reCAPCHA Public Key</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_recapcha_public', 'class' => 'form-control', 'value' => $site_recapcha_public, 'placeholder' => 'reCAPCHA Public Key', 'maxlength' => '100')); ?>
    			</div>

                <!-- reCAPCHA Private Key -->
    			<div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
      				<span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> reCAPCHA Private Key</span>
            </div>
    				<?php echo Form::input(array('type' => 'text', 'name' => 'site_recapcha_private', 'class' => 'form-control', 'value' => $site_recapcha_private, 'placeholder' => 'reCAPCHA Private Key', 'maxlength' => '100')); ?>
    			</div>

    		</div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
    	<div class='card mb-3'>
    		<div class='card-header h4'>
    			<h3 class='jumbotron-heading'>Site Wide Message</h3>
    		</div>
    		<div class='card-body'>
          This message will show to all users on the site.  Let them know about downtime or other site related messages.<Br>
          Info box will not show if the field below is blank.
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fa fa-fw  fa-globe'></i> Site Description</span>
            </div>
              <?php echo Form::textarea(array('type' => 'text', 'name' => 'site_message', 'class' => 'form-control', 'value' => $site_message, 'placeholder' => 'Site Wide Message')); ?>
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
