<?php
/**
* Admin Panel Page Permissions View
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
			<?php echo $data['title']." - ".$data['page_data'][0]->controller." - ".$data['page_data'][0]->method;  ?>
      <?php echo PageFunctions::displayPopover('Page Permissions', 'Page Permissions allows the Admin to limit who can see the page based on user group. Only Users that are members of the Groups checked may view this page.  If set to Public, everyone can view this page.', false, 'btn btn-sm btn-light'); ?>
		</div>
		<div class='card-body'>

			<p><?php echo $data['welcomeMessage'] ?></p>

			<?php echo Form::open(array('method' => 'post')); ?>

        <h4>Permission Levels for Page: <a href='<?php echo SITE_URL.$data['page_data'][0]->url; ?>' target='_blank'><?=$data['page_data'][0]->url?></a></h4>
        Check the User Groups that are Allowed to view this page.
        <hr>
      <?php
        if(\Libs\PageFunctions::checkPageGroup($data['page_data'][0]->id, '0')){ $checked = "checked"; }

        echo "<div class='custom-control custom-checkbox'>";
          echo "<input type='checkbox' class='custom-control-input' id='group_id_0' name='group_id[]' value='0' $checked>";
          echo "<label class='custom-control-label' for='group_id_0'>Public</label>";
        echo "</div>";
        unset($checked);

        if(isset($site_groups)){
          foreach ($site_groups as $key => $value) {
            $group_display = \Libs\CurrentUserData::getGroupData($value->groupID);
            if(\Libs\PageFunctions::checkPageGroup($data['page_data'][0]->id, $value->groupID)){ $checked = "checked"; }

            echo "<div class='custom-control custom-checkbox'>";
              echo "<input type='checkbox' class='custom-control-input' id='group_id_$value->groupID' name='group_id[$value->groupID]' value='$value->groupID' $checked>";
              echo "<label class='custom-control-label' for='group_id_$value->groupID'>".$group_display."</label>";
            echo "</div>";
            unset($checked);
          }
        }
      ?>

      </div>
      <div class='card-footer'>
				<!-- CSRF Token -->
				<input type="hidden" name="token_pages_permissions" value="<?php echo $data['csrfToken']; ?>" />
				<input type="hidden" name="page_id" value="<?php echo $data['page_data'][0]->id; ?>" />
        <input type="hidden" name="update_page" value="true" />
				<button class="btn btn-md btn-success" name="submit" type="submit">
					Update Page Permission
				</button>
			<?php echo Form::close(); ?>
      <?php
      echo "<a href='#DeleteModal' class='btn btn-sm btn-danger trigger-btn float-right' data-toggle='modal'>Delete</a>";

      echo "
        <div class='modal fade' id='DeleteModal' tabindex='-1' role='dialog' aria-labelledby='DeleteLabel' aria-hidden='true'>
          <div class='modal-dialog' role='document'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h5 class='modal-title' id='DeleteLabel'>Delete Page?</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>
              <div class='modal-body'>
                Do you want to delete this page?<br><br>
                ".$data['page_data'][0]->url."
              </div>
              <div class='modal-footer'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                ";
                echo Form::open(array('method' => 'post', 'class' => 'float-right', 'style' => 'display:inline'));
                echo "<input type='hidden' name='token_pages_permissions' value='".$data['csrfToken']."'>";
                echo "<input type='hidden' name='delete_page' value='true' />";
                echo "<input type='hidden' name='page_id' value='".$data['page_data'][0]->id."'>";
                echo "<button class='btn btn-md btn-danger' name='submit' type='submit'>Delete Page Permission</button>";
                echo Form::close();
                echo "
              </div>
            </div>
          </div>
        </div>
      ";
      ?>
		</div>
	</div>
</div>
