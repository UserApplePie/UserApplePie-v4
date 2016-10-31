<?php
/**  
* UserApplePie v4 Forum Plugin
* @author David (DaVaR) Sargent
* @email davar@thedavar.net
* @website http://www.userapplepie.com
* @version 1.0.0
* @release_date 04/27/2016
**/

/** Forum Settings Admin Panel View **/

use Libs\Form,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Core\Language;

?>

<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<h3 class='jumbotron-heading'><?php echo $data['title'];  ?></h3>
		</div>
		<div class='panel-body'>
			<p><?php echo $data['welcome_message'] ?></p>

      <!-- Start main forum Settings -->
        <?php echo Form::open(array('method' => 'post')); ?>

        <!-- Enable / Disable Forum -->
				<div class='input-group'>
					<span class='input-group-addon'><i class='glyphicon glyphicon'></i> Forum ON/OFF</span>
					<select class='form-control' id='forum_on_off' name='forum_on_off'>
				    <option value='Enabled' <?php if($data['forum_on_off'] == "Enabled"){echo "SELECTED";}?> >Enabled</option>
				    <option value='Disabled' <?php if($data['forum_on_off'] == "Disabled"){echo "SELECTED";}?> >Disabled</option>
				  </select>
				</div>
        <div style='margin-bottom: 25px'>
          <i>Default: Disabled</i> - Turn the Forum ON or OFF. Hides the Forum from all users if Disabled.
        </div>

        <!-- Forum Name -->
        <div class='input-group'>
          <span class='input-group-addon'><i class='glyphicon glyphicon'></i> Forum Title</span>
          <?php echo Form::input(array('type' => 'text', 'name' => 'forum_title', 'class' => 'form-control', 'value' => $data['forum_title'], 'placeholder' => 'Global Forum Name/Title', 'maxlength' => '100')); ?>
        </div>
        <div style='margin-bottom: 25px'>
          <i>Default: Forum</i> - Set the Forum Title.
        </div>

        <!-- Forum Description -->
        <div class='input-group'>
          <span class='input-group-addon'><i class='glyphicon glyphicon'></i> Forum Description</span>
          <?php echo Form::textBox(array('type' => 'text', 'name' => 'forum_description', 'class' => 'form-control', 'value' => $data['forum_description'], 'placeholder' => 'Global Forum Description', 'maxlength' => '255')); ?>
        </div>
        <div style='margin-bottom: 25px'>
          <i>Default: Blank</i> - Set the Forum Description.
        </div>

        <hr>

        <!-- Forum Topic Limit Per Page -->
        <div class='input-group'>
          <span class='input-group-addon'><i class='glyphicon glyphicon'></i> Topics Per Page</span>
          <?php echo Form::input(array('type' => 'text', 'name' => 'forum_topic_limit', 'class' => 'form-control', 'value' => $data['forum_topic_limit'], 'placeholder' => 'Topics Per Page Limit', 'maxlength' => '100')); ?>
        </div>
        <div style='margin-bottom: 25px'>
          <i>Default: 20</i> - Set the Forum Topics Limit Per Page.
        </div>

        <!-- Forum Topic Reply Limit Per Page -->
        <div class='input-group'>
          <span class='input-group-addon'><i class='glyphicon glyphicon'></i> Topic Replies Per Page</span>
          <?php echo Form::input(array('type' => 'text', 'name' => 'forum_topic_reply_limit', 'class' => 'form-control', 'value' => $data['forum_topic_reply_limit'], 'placeholder' => 'Topic Replies Per Page Limit', 'maxlength' => '100')); ?>
        </div>
        <div style='margin-bottom: 25px'>
          <i>Default: 10</i> - Set the Forum Topic Replies Limit Per Page.
        </div>
        <?php echo Form::input(array('type' => 'hidden', 'name' => 'token_ForumAdmin', 'value' => $data['csrf_token'])); ?>
        <?php echo Form::input(array('type' => 'hidden', 'name' => 'update_global_settings', 'value' => 'true')); ?>
        <button class='btn btn-sm btn-success' name='submit' type='submit'>Update Forum Settings</button>
        <?php echo Form::close(); ?>
      <!-- End Main forum Settings -->

    </div>
    </div>

  <div class='row'>
  <!-- Start of Forum Users groups -->
  <div class='col-lg-4 col-md-4'>
  	<div class='panel panel-default'>
  		<div class='panel-heading'>
  			<h3 class='jumbotron-heading'>Forum User Groups</h3>
  		</div>

  			<?php
          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are a member of
            if(isset($data['f_users_member_groups'])){
              echo "<th style='background-color: #EEE'>Groups Allowed to Post on Forum: </th>";
              foreach($data['f_users_member_groups'] as $member){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='remove_group_user' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$member[0]->groupID."'>";
                echo "<button class='btn btn-xs btn-danger' name='submit' type='submit'>Remove</button>";
                echo Form::close();
                echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups Allowed to Post on Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";

          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are not a member of
            if(isset($data['f_users_notmember_groups'])){
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Post on Forum: </th>";
              foreach($data['f_users_notmember_groups'] as $notmember){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='add_group_user' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$notmember[0]->groupID."'>";
                echo "<button class='btn btn-xs btn-success' name='submit' type='submit'>Add</button>";
                echo Form::close();
                echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Post on Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";

          // Display Check Box Settings For What Group Can Do on Forum
  //        echo Form::open(array('method' => 'post'));
  //        echo "<table class='table table-hover responsive'>";
  //          echo "<th style='background-color: #EEE'>Forum Users Permissions:</th>";
  //          echo "<tr><td>";
  //            echo "<input type='checkbox' name='forum_view_posts' value='true'> View Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_create_posts' value='true'> Create New Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_edit_posts' value='true'> Edit Their Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_edit_any_posts' value='true'> Edit Any Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_hide_posts' value='true'> Hide Posts";
  //          echo "</td></tr><tr><td>";
  //            echo "<input type='checkbox' name='forum_lock_topics' value='true'> Lock/Unlock Topics";
  //          echo "</td></tr>";
  //        echo "</table>";
  //        echo "<input type='hidden' name='update_users_group_perm' value='true' />";
  //        echo "<button class='btn btn-xs btn-success' name='submit' type='submit'>Update Permissions</button>";
  //        echo Form::close();
        ?>

  	</div>
  </div>

  <!-- Start of Forum Moderator groups -->
  <div class='col-lg-4 col-md-4'>
  	<div class='panel panel-default'>
  		<div class='panel-heading'>
  			<h3 class='jumbotron-heading'>Forum Moderator Groups</h3>
  		</div>

  			<?php
          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are a member of
            if(isset($data['f_mods_member_groups'])){
              echo "<th style='background-color: #EEE'>Groups Allowed to Moderate Forum: </th>";
              foreach($data['f_mods_member_groups'] as $member){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='remove_group_mod' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$member[0]->groupID."'>";
                echo "<button class='btn btn-xs btn-danger' name='submit' type='submit'>Remove</button>";
                echo Form::close();
                echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups Allowed to Moderate Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";

          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are not a member of
            if(isset($data['f_mods_notmember_groups'])){
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Moderate Forum: </th>";
              foreach($data['f_mods_notmember_groups'] as $notmember){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='add_group_mod' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$notmember[0]->groupID."'>";
                echo "<button class='btn btn-xs btn-success' name='submit' type='submit'>Add</button>";
                echo Form::close();
                echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Moderate Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";
        ?>

  	</div>
  </div>

  <!-- Start of Forum Admin groups -->
  <div class='col-lg-4 col-md-4'>
  	<div class='panel panel-default'>
  		<div class='panel-heading'>
  			<h3 class='jumbotron-heading'>Forum Administrator Groups</h3>
  		</div>

  			<?php
          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are a member of
            if(isset($data['f_admins_member_groups'])){
              echo "<th style='background-color: #EEE'>Groups Allowed to Admin Forum: </th>";
              foreach($data['f_admins_member_groups'] as $member){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='remove_group_admin' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$member[0]->groupID."'>";
                echo "<button class='btn btn-xs btn-danger' name='submit' type='submit'>Remove</button>";
                echo Form::close();
                echo " - <font color='".$member[0]->groupFontColor."' style='font-weight: ".$member[0]->groupFontWeight."'>".$member[0]->groupName."</font>";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups Allowed to Admin Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";

          echo "<table class='table table-hover responsive'>";
            // Displays User's Groups they are not a member of
            if(isset($data['f_admins_notmember_groups'])){
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Admin Forum: </th>";
              foreach($data['f_admins_notmember_groups'] as $notmember){
                echo "<tr><td>";
                echo Form::open(array('method' => 'post', 'style' => 'display:inline-block'));
                echo "<input type='hidden' name='token_ForumAdmin' value='".$data['csrf_token']."'>";
                echo "<input type='hidden' name='add_group_admin' value='true' />";
                echo "<input type='hidden' name='groupID' value='".$notmember[0]->groupID."'>";
                echo "<button class='btn btn-xs btn-success' name='submit' type='submit'>Add</button>";
                echo Form::close();
                echo " - <font color='".$notmember[0]->groupFontColor."' style='font-weight: ".$notmember[0]->groupFontWeight."'>".$notmember[0]->groupName."</font> ";
                echo "</td></tr>";
              }
            }else{
              echo "<th style='background-color: #EEE'>Groups NOT Allowed to Admin Forum: </th>";
              echo "<tr><td> None </td></tr>";
            }
          echo "</table>";
        ?>

  	</div>
  </div>
  </div>

</div>
