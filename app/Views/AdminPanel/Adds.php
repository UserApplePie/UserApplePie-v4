<?php
/**
* Admin Panel Adds View
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
          <?php echo PageFunctions::displayPopover('Site Adds Settings', 'Site Adds Settings is used to impliment adds to the site.  You can copy and paste the add code below.  The site will then place that code at given locations on the site.  If left blank, add window will not display.', false, 'btn btn-sm btn-light'); ?>
    		</div>
    		<div class='card-body'>
    			<p><?php echo $data['welcomeMessage'] ?></p>

    			<?php echo Form::open(array('method' => 'post')); ?>

          <!-- Adds Main Top Code -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
        		<div class='input-group-prepend'>
        			<span class='input-group-text'>Adds Top</span>
        		</div>
            <?php echo Form::textBox(array('type' => 'text', 'name' => 'adds_top', 'class' => 'form-control', 'value' => $data['adds_top'], 'placeholder' => 'Main Top Adds Code', 'rows' => '6')); ?>
          </div>

          <!-- Adds Main Bottom Code -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class='input-group-prepend'>
              <span class='input-group-text'>Adds Bottom</span>
            </div>
            <?php echo Form::textBox(array('type' => 'text', 'name' => 'adds_bottom', 'class' => 'form-control', 'value' => $data['adds_bottom'], 'placeholder' => 'Main Bottom Adds Code', 'rows' => '6')); ?>
          </div>

          <!-- Adds Sidebar Top Code -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
        		<div class='input-group-prepend'>
        			<span class='input-group-text'>Adds Sidebar Top</span>
        		</div>
            <?php echo Form::textBox(array('type' => 'text', 'name' => 'adds_sidebar_top', 'class' => 'form-control', 'value' => $data['adds_sidebar_top'], 'placeholder' => 'Sidebar Top Adds Code', 'rows' => '6')); ?>
          </div>

          <!-- Adds Sidebar Bottom Code -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class='input-group-prepend'>
              <span class='input-group-text'>Adds Sidebar Bottom</span>
            </div>
            <?php echo Form::textBox(array('type' => 'text', 'name' => 'adds_sidebar_bottom', 'class' => 'form-control', 'value' => $data['adds_sidebar_bottom'], 'placeholder' => 'Sidebar Bottom Adds Code', 'rows' => '6')); ?>
          </div>

        </div>
    	</div>
    </div>

    <div class='col-lg-12 col-md-12 col-sm-12'>
        <button class="btn btn-md btn-success" name="submit" type="submit">
            Update Adds Settings
        </button>
        <!-- CSRF Token and What is Being Updated -->
        <input type="hidden" name="token_settings" value="<?php echo $data['csrfToken']; ?>" />
        <input type="hidden" name="update_settings" value="true" />
        <?php echo Form::close(); ?><Br><br>
    </div>
  </div>
</div>
