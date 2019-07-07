<?php
/**
* Admin Panel Site Link View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

/** Forum Categories Admin Panel View **/

use Libs\Form,
    Libs\ErrorMessages,
    Libs\SuccessMessages,
    Libs\Language,
    Libs\PageFunctions,
    Libs\Url,
    Libs\CurrentUserData;

?>

<?php if($edit_type == "deletelink"){ ?>

  <div class='col-lg-12 col-md-12 col-sm-12'>
  	<div class='card mb-3'>
  		<div class='card-header h4'>
  			<?php echo $data['title'];  ?>
        <?php echo PageFunctions::displayPopover('Site Link Delete', 'Site Link Delete will remove selected link from the database, therefore removed from the site. This CANNOT be undone.', false, 'btn btn-sm btn-light'); ?>
  		</div>
  		<div class='card-body'>
  			<p><?php echo $data['welcome_message'] ?></p>
        <?php echo Form::open(array('method' => 'post')); ?>
        <!-- CSRF Token -->
        <input type="hidden" name="token_SiteLink" value="<?php echo $data['csrfToken']; ?>" />
        <input type="hidden" name="id" value="<?php echo $link_data[0]->id; ?>" />
        <input type="hidden" name="link_action" value="delete" />
        <button class="btn btn-sm btn-danger" name="submit" type="submit">
          Yes
        </button>
        <a class="btn btn-sm btn-success" href="<?=DIR?>AdminPanel-SiteLinks/">
          No
        </a>
        <?php echo Form::close(); ?>
      </div>
    </div>
  </div>

<?php }else{ ?>

<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'];  ?>
      <?php echo PageFunctions::displayPopover('Site Link', 'Site Link can be created, and edited to fit the site needs.  Admin can set the link data, style, and permissions here.  Settings are Case Sensitive.', false, 'btn btn-sm btn-light'); ?>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>

      <?php echo Form::open(array('method' => 'post')); ?>

        <!-- Link Title -->
        <div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
            <span class='input-group-text'><i class='fas fa-fw fa-link'></i> Link Title</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'title', 'class' => 'form-control', 'value' => $link_data[0]->title, 'placeholder' => 'Title For Link Display', 'maxlength' => '100')); ?>
          <?php echo PageFunctions::displayPopover('Link Title', 'Link Title is displayed on the site in the format it is entered here.  Dispaly matches the case used here.', true, 'input-group-text'); ?>
        </div>

        <!-- URL -->
        <div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
            <span class='input-group-text'><i class='fas fa-fw fa-link'></i> Link URL</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'url', 'class' => 'form-control', 'value' => $link_data[0]->url, 'placeholder' => 'Site URL For Link', 'maxlength' => '100')); ?>
          <?php echo PageFunctions::displayPopover('Link URL', 'Link URL is what the URL for the link will be after the SITE_URL.  The URL used here will let the site know which site route to load.  This is Case Sensitive.', true, 'input-group-text'); ?>
        </div>

        <!-- Alt Text -->
        <div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
            <span class='input-group-text'><i class='fas fa-fw fa-link'></i> Link Alt Text</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'alt_text', 'class' => 'form-control', 'value' => $link_data[0]->alt_text, 'placeholder' => 'Alt Text to Display on Hover', 'maxlength' => '255')); ?>
          <?php echo PageFunctions::displayPopover('Link Alt Text', 'Link Alt Text is used to give a little more information about the link.  For example if a user hovers over the link, most browsers will show a small popup with this data.', true, 'input-group-text'); ?>
        </div>

        <?php
          /** Check to see if this is a drop down link **/
          if($edit_type == "new" || $edit_type == "update"){
        ?>
          <!-- Link For Drop Down Menu -->
          <div class='input-group mb-3' style='margin-bottom: 25px'>
            <div class="input-group-prepend">
              <span class='input-group-text'><i class='fas fa-fw fa-caret-down'></i> Drop Down Menu</span>
            </div>
            <select class='form-control' id='drop_down' name='drop_down'>
              <option value='0' <?php if($link_data[0]->drop_down == "0"){echo "SELECTED";}?> >No</option>
              <option value='1' <?php if($link_data[0]->drop_down == "1"){echo "SELECTED";}?> >Yes</option>
            </select>
            <?php echo PageFunctions::displayPopover('Drop Down Menu Enable', 'Drop Down Menu Enable will set the link as a drop down menu.  URL is not needed if Enabled.  Once Enabled, Drop Down Links will apear below.', true, 'input-group-text'); ?>
          </div>
        <?php } ?>

        <!-- Require Plugin -->
        <div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
            <span class='input-group-text'><i class='fas fa-fw fa-plug'></i> Require Plugin</span>
          </div>
          <select class='form-control' id='require_plugin' name='require_plugin'>
            <option value='' <?php if($link_data[0]->require_plugin == ""){echo "SELECTED";}?> >None</option>
            <option value='Forum' <?php if($link_data[0]->require_plugin == "Forum"){echo "SELECTED";}?> >Forum</option>
            <option value='Friends' <?php if($link_data[0]->require_plugin == "Friends"){echo "SELECTED";}?> >Friends</option>
            <option value='Messages' <?php if($link_data[0]->require_plugin == "Messages"){echo "SELECTED";}?> >Messages</option>
          </select>
          <?php echo PageFunctions::displayPopover('Require Plugin', 'Require Plugin is used to limit the link to display only if the selected plugin is installed.', true, 'input-group-text'); ?>
        </div>

        <!-- Permission -->
        <div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
            <span class='input-group-text'><i class='fas fa-fw fa-users-cog'></i> Permission</span>
          </div>
          <select class='form-control' id='permission' name='permission'>
            <option value='0' <?php if($link_data[0]->permission == "0"){echo "SELECTED";}?> >Public</option>
            <?php
              $getGroups = CurrentUserData::getGroups();
              foreach ($getGroups as $group) {
                echo "<option value='$group->groupID' "; if($link_data[0]->permission == $group->groupID){echo "SELECTED";} echo ">$group->groupName</option>";
              }

            ?>
          </select>
          <?php echo PageFunctions::displayPopover('Link Permission', 'Link Permission sets which user groups can see the link.  If set to Public, then all visitors to the site can view the link.', true, 'input-group-text'); ?>
        </div>

        <!-- Fontawesome icon class -->
        <div class='input-group mb-3' style='margin-bottom: 25px'>
          <div class="input-group-prepend">
            <span class='input-group-text'><i class='fab fa-fw fa-font-awesome'></i> Icon Class</span>
          </div>
          <?php echo Form::input(array('type' => 'text', 'name' => 'icon', 'class' => 'form-control', 'value' => $link_data[0]->icon, 'placeholder' => 'Fontawesome Icon Class : fab fa-font-awesome ', 'maxlength' => '255')); ?>
          <?php echo PageFunctions::displayPopover('Icon Class', 'Icon Class uses Fontawesome 5 icons as listed in www.fontawesome.com.  Copy and paste the class here.  For example: "fas fa-tools" will display the tools icon next to the link.', true, 'input-group-text'); ?>
        </div>

    </div>
    <div class='card-footer'>
        <!-- CSRF Token -->
        <input type="hidden" name="token_SiteLink" value="<?php echo $data['csrfToken']; ?>" />

        <?php if($edit_type == "update"){ ?>
          <input type="hidden" name="id" value="<?php echo $link_data[0]->id; ?>" />
          <input type="hidden" name="link_action" value="update" />
          <button class="btn btn-md btn-success" name="submit" type="submit">
            Update Link
          </button>
        <?php }else if($edit_type == "dropdownnew"){ ?>
          <input type="hidden" name="drop_down_for" value="<?php echo $main_link_id; ?>" />
          <input type="hidden" name="link_action" value="dropdownnew" />
          <button class="btn btn-md btn-success" name="submit" type="submit">
            Create New Drop Down Link
          </button>
        <?php }else if($edit_type == "dropdownupdate"){ ?>
          <input type="hidden" name="dd_link_id" value="<?php echo $dd_link_id; ?>" />
          <input type="hidden" name="link_action" value="dropdownupdate" />
          <button class="btn btn-md btn-success" name="submit" type="submit">
            Update Drop Down Link
          </button>
        <?php }else{ ?>
          <input type="hidden" name="link_action" value="new" />
          <button class="btn btn-sm btn-success" name="submit" type="submit">
            Create Link
          </button>
        <?php } ?>
      <?php echo Form::close(); ?>
      <a href='<?=DIR?>AdminPanel-SiteLink/LinkDelete/<?php echo $link_data[0]->id; ?>/' class='btn btn-sm btn-danger float-right'>Delete</a>
    </div>
	</div>

  <?php
    /** Check if above link has drop down enabled **/
    if($link_data[0]->drop_down == "1"){
  ?>
    <div class='card mb-3'>
      <div class='card-header h4'>
        Drop Down Links for <?php echo $link_data[0]->title; ?>
      </div>
      <table class='table table-hover responsive'>
        <tr>
          <th>Link Title</th><th>URL</th><th>Alt Text</th><th>Location</th><th>Require Plugin</th><th>Permission</th><th>Icon</th><th></th>
        </tr>
        <?php
          if(isset($drop_down_links)){
            foreach ($drop_down_links as $link) {
              echo "<tr>";
              echo "<td>".$link->title."</td>";
              echo "<td>".$link->url."</td>";
              echo "<td>".$link->alt_text."</td>";
              echo "<td>".$link->location."</td>";
              echo "<td>".$link->require_plugin."</td>";
              echo "<td>".CurrentUserData::getGroupData($link->permission)."</td>";
              echo "<td> <i class='$link->icon'></i> </td>";
              echo "<td align='right'>";
              /** Check to see if object is at top **/
              if($link->link_order_drop_down > 1){
                echo "<a href='".DIR."AdminPanel-SiteLink/LinkDDUp/".$link_data[0]->id."/$link->id/' class='btn btn-primary btn-sm' role='button'><span class='fa fa-fw fa-caret-up' aria-hidden='true'></span></a> ";
              }
              /** Check to see if object is at bottom **/
              if($drop_down_order_last != $link->link_order_drop_down){
                echo "<a href='".DIR."AdminPanel-SiteLink/LinkDDDown/".$link_data[0]->id."/$link->id/' class='btn btn-primary btn-sm' role='button'><span class='fa fa-fw fa-caret-down' aria-hidden='true'></span></a> ";
              }
              echo "<a href='".DIR."AdminPanel-SiteLink/DropDownUpdate/".$link_data[0]->id."/$link->id/' class='btn btn-sm btn-success'><span class='fas fa-edit'></span></a>";
              echo "</td>";
              echo "</tr>";
            }
          }
        ?>
      </table>
      <div class='card-footer'>
        <a href='<?=DIR?>AdminPanel-SiteLink/DropDownNew/<?php echo $link_data[0]->id; ?>/' class='btn btn-sm btn-success'>Add New Drop Down Link</a>
      </div>
    </div>
  <?php } ?>
</div>
<?php } ?>
