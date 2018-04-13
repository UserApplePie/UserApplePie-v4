<?php
/**
* Admin Panel System Route View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

use Libs\Form,
    Libs\ErrorMessages,
    Libs\SuccessMessages,
    Libs\Language;

?>

<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title']." - ".$data['system_route'][0]->controller." - ".$data['system_route'][0]->method;  ?>
		</div>
		<div class='card-body'>

			<p><?php echo $data['welcomeMessage'] ?></p>

			<?php echo Form::open(array('method' => 'post')); ?>

			<!-- Controller -->
			<div class='form-group' style='margin-bottom: 25px'>
				<span class='input-group-addon'><i class='fas fa-tower'></i> Controller</span>
				<?php echo Form::input(array('type' => 'text', 'name' => 'controller', 'class' => 'form-control', 'value' => $data['system_route'][0]->controller, 'placeholder' => 'Controller Class Name', 'maxlength' => '255')); ?>
			</div>

			<!-- Method -->
			<div class='form-group' style='margin-bottom: 25px'>
				<span class='input-group-addon'><i class='fas fa-book'></i> Method</span>
				<?php echo Form::input(array('type' => 'text', 'name' => 'method', 'class' => 'form-control', 'value' => $data['system_route'][0]->method, 'placeholder' => 'Method Function Name', 'maxlength' => '255')); ?>
			</div>

            <!-- URL -->
			<div class='form-group' style='margin-bottom: 25px'>
				<span class='input-group-addon'><i class='fas fa-book'></i> URL</span>
				<?php echo Form::input(array('type' => 'text', 'name' => 'url', 'class' => 'form-control', 'value' => $data['system_route'][0]->url, 'placeholder' => 'URL Address Name', 'maxlength' => '255')); ?>
			</div>

            <!-- Arguments -->
            <div class='form-group' style='margin-bottom: 25px'>
                <span class='input-group-addon'><i class='fas fa-book'></i> Arguments</span>
                <?php echo Form::input(array('type' => 'text', 'name' => 'arguments', 'class' => 'form-control', 'value' => $data['system_route'][0]->arguments, 'placeholder' => 'Route Arguments', 'maxlength' => '255')); ?>
            </div>


        <!-- Group Font Weight -->
				<div class='form-group' style='margin-bottom: 25px'>
					<span class='input-group-addon'><i class='fas fa-book'></i> Route Enabled</span>
          <select class='form-control' id='gender' name='enable'>
            <option value='1' <?php if($data['system_route'][0]->enable == "1"){echo "SELECTED";}?> >Enabled</option>
            <option value='0' <?php if($data['system_route'][0]->enable == "0"){echo "SELECTED";}?> >Disabled</option>
          </select>
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
          echo "<br><br>";
          echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
          echo "<input type='hidden' name='token_route' value='".$data['csrfToken']."'>";
          echo "<input type='hidden' name='delete_route' value='true' />";
          echo "<input type='hidden' name='id' value='".$data['system_route'][0]->id."'>";
          echo "<button class='btn btn-xs btn-danger' name='submit' type='submit'>Delete System Route</button>";
          echo Form::close();
      ?>
		</div>
	</div>
</div>
