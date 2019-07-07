<?php
/**
* Admin Panel System Route View
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
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title']." - ".$data['system_route'][0]->controller." - ".$data['system_route'][0]->method;  ?>
      <?php echo PageFunctions::displayPopover('Site System Route', 'Site System Route lets the site know which Controller and Method to load based on URL request.', false, 'btn btn-sm btn-light'); ?>
		</div>
		<div class='card-body'>

			<p><?php echo $data['welcomeMessage'] ?></p>

			<?php echo Form::open(array('method' => 'post')); ?>

			<!-- Controller -->
			<div class='input-group mb-3' style='margin-bottom: 25px'>
        <div class="input-group-prepend">
				  <span class='input-group-text'><i class='fas fa-fw fa-gamepad'></i> Controller</span>
        </div>
				<?php echo Form::input(array('type' => 'text', 'name' => 'controller', 'class' => 'form-control', 'value' => $data['system_route'][0]->controller, 'placeholder' => 'Controller Class Name', 'maxlength' => '255')); ?>
        <?php echo PageFunctions::displayPopover('Controller', 'Controller is the File Name and Class Name within the Controllers folder. This is case sensitive.', true, 'input-group-text'); ?>
			</div>

			<!-- Method -->
			<div class='input-group mb-3' style='margin-bottom: 25px'>
        <div class="input-group-prepend">
				  <span class='input-group-text'><i class='fa fa-fw  fa-book'></i> Method</span>
        </div>
				<?php echo Form::input(array('type' => 'text', 'name' => 'method', 'class' => 'form-control', 'value' => $data['system_route'][0]->method, 'placeholder' => 'Method Function Name', 'maxlength' => '255')); ?>
        <?php echo PageFunctions::displayPopover('Method', 'Method is the Function name within the Controller selected above.  This is case sensitive.', true, 'input-group-text'); ?>
			</div>

      <!-- URL -->
			<div class='input-group mb-3' style='margin-bottom: 25px'>
        <div class="input-group-prepend">
				  <span class='input-group-text'><i class='fa fa-fw  fa-book'></i> URL</span>
        </div>
				<?php echo Form::input(array('type' => 'text', 'name' => 'url', 'class' => 'form-control', 'value' => $data['system_route'][0]->url, 'placeholder' => 'URL Address Name', 'maxlength' => '255')); ?>
        <?php echo PageFunctions::displayPopover('Site URL', 'Site URL is what the System Router looks for to know which Controller and Method to load based on the settings within this page. This is case sensitive.', true, 'input-group-text'); ?>
			</div>

      <!-- Arguments -->
      <div class='input-group mb-3' style='margin-bottom: 25px'>
        <div class="input-group-prepend">
          <span class='input-group-text'><i class='fa fa-fw  fa-book'></i> Arguments</span>
        </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'arguments', 'class' => 'form-control', 'value' => $data['system_route'][0]->arguments, 'placeholder' => 'Route Arguments', 'maxlength' => '255')); ?>
          <?php echo PageFunctions::displayPopover('Arguments', 'Arguments lets the System Router what type of arguments and how many can be used for a given controller.  EX: (:any)/(:num)/(:all)', true, 'input-group-text'); ?>
      </div>


        <!-- Group Font Weight -->
				<div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
  				  <span class='input-group-text'><i class='fa fa-fw  fa-book'></i> Route Enabled</span>
          </div>
          <select class='form-control' id='gender' name='enable'>
            <option value='1' <?php if($data['system_route'][0]->enable == "1"){echo "SELECTED";}?> >Enabled</option>
            <option value='0' <?php if($data['system_route'][0]->enable == "0"){echo "SELECTED";}?> >Disabled</option>
          </select>
          <?php echo PageFunctions::displayPopover('Route Enabled', 'Route Enabled lets the System Router know if this route can be used or not.  When disabled it give a error page.', true, 'input-group-text'); ?>
				</div>

				<!-- CSRF Token -->
				<input type="hidden" name="token_route" value="<?php echo $data['csrfToken']; ?>" />
				<input type="hidden" name="id" value="<?php echo $data['system_route'][0]->id; ?>" />
                <input type="hidden" name="update_route" value="true" />
				<button class="btn btn-md btn-success" name="submit" type="submit">
					Update System Route
				</button>
			<?php echo Form::close(); ?>

      <?php
          echo Form::open(array('method' => 'post', 'class' => 'float-right'));
          echo "<input type='hidden' name='token_route' value='".$data['csrfToken']."'>";
          echo "<input type='hidden' name='delete_route' value='true' />";
          echo "<input type='hidden' name='id' value='".$data['system_route'][0]->id."'>";
          echo "<button class='btn btn-sm btn-danger' name='submit' type='submit'>Delete System Route</button>";
          echo Form::close();
      ?>
		</div>
	</div>
</div>
