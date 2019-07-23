<?php
/**
* Admin Panel Terms and Policy View
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
          <?php echo PageFunctions::displayPopover('Terms and Policy Content', 'Site Terms and Policy content allows Owner of the site to setup their Terms and Policy data.  We suggest researching how to use Terms and Policy content on your site via Google.  If there are blank, links will not display in footer. HTML can be used.', false, 'btn btn-sm btn-light'); ?>
    		</div>
    		<div class='card-body'>
    			<p><?php echo $data['welcomeMessage'] ?></p>

    			<?php echo Form::open(array('method' => 'post')); ?>

          <!-- Site Terms Content -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
        		<div class='input-group-prepend'>
        			<span class='input-group-text'>Site Terms Content</span>
        		</div>
            <?php echo Form::textBox(array('type' => 'text', 'name' => 'site_terms_content', 'class' => 'form-control', 'value' => $data['site_terms_content'], 'placeholder' => 'Site Terms and Conditions Content', 'rows' => '8')); ?>
          </div>

          <!-- Site Privacy Content -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class='input-group-prepend'>
              <span class='input-group-text'>Site Privacy Content</span>
            </div>
            <?php echo Form::textBox(array('type' => 'text', 'name' => 'site_privacy_content', 'class' => 'form-control', 'value' => $data['site_privacy_content'], 'placeholder' => 'Site Privacy Content', 'rows' => '8')); ?>
          </div>

        </div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
        <button class="btn btn-md btn-success" name="submit" type="submit">
            Update Terms and Policy Content
        </button>
        <!-- CSRF Token and What is Being Updated -->
        <input type="hidden" name="token_TermsPrivacy" value="<?php echo $data['csrfToken']; ?>" />
        <input type="hidden" name="update_settings" value="true" />
        <?php echo Form::close(); ?><Br><br>
    </div>
  </div>
</div>
